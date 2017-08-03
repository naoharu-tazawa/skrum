<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;

/**
 * フィードバック対象者報告メールサービスクラス
 *
 * @author naoharu.tazawa
 */
class FeedbackTargetReportEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param MUser $mUser ユーザエンティティ
     * @return void
     */
    private function createEmail(MUser $mUser)
    {
        // デフォルトタイムフレームエンティティを取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframe = $tTimeframeRepos->findOneBy(array('company' => $mUser->getCompany()->getCompanyId(), 'defaultFlg' => DBConstant::FLG_TRUE));

        // 最低進捗率を計算
        $minimumAchievementRate = null;
        // デフォルトタイムフレームの期間中である、かつタイムフレーム経過日数が10日以上である場合、最低進捗率を計算
        if ((DateUtility::transIntoDateString($tTimeframe->getStartDate()) <= DateUtility::getCurrentDateString()) &&
                (DateUtility::transIntoDateString($tTimeframe->getEndDate()) >= DateUtility::getCurrentDateString()) &&
                (((strtotime(DateUtility::getCurrentDateString()) - strtotime(DateUtility::transIntoDateString($tTimeframe->getStartDate()))) / 86400) >= $this->getParameter('feedback_target_condition_low_achievement_rate_required_days'))) {
            $idealAchievementRate = ((strtotime(DateUtility::getCurrentDateString()) - strtotime(DateUtility::transIntoDateString($tTimeframe->getStartDate()))) / (strtotime((DateUtility::transIntoDateString($tTimeframe->getEndDate()))) - strtotime(DateUtility::transIntoDateString($tTimeframe->getStartDate())))) * 100;
            $minimumAchievementRate = $idealAchievementRate * $this->getParameter('feedback_target_condition_low_achievement_rate');
        }

        // 所属グループを取得
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $groupInfoArray = $tGroupMemberRepos->getAllGroups($mUser->getUserId());

        // メール本文記載変数
        $data = array();
        $tLoginRepos = $this->getTLoginRepository();
        $tOkrActivityRepos = $this->getTOkrActivityRepository();

        foreach ($groupInfoArray as $key1 => $groupInfo) {
            // グループ情報をメール本文記載変数配列に格納
            $data[$key1]['groupName'] = $groupInfo['groupName'];

            // 所属メンバーのフィードバック対象者を取得
            $userInfoArray = $tGroupMemberRepos->getFeedbackTargetMembers($groupInfo['groupId'], $tTimeframe->getTimeframeId());

            foreach ($userInfoArray as $key2 => $userInfo) {
                // 最低進捗率条件
                if ($minimumAchievementRate !== null) {
                    if ($userInfo['achievementRate'] < $minimumAchievementRate) {
                        // メンバー情報をメール本文記載変数配列に格納
                        $memberName = $userInfo['lastName'] . ' ' . $userInfo['firstName'];
                        $data[$key1]['members'][$key2]['memberName'] = $memberName;
                        $data[$key1]['members'][$key2]['reason'] = $this->getParameter('feedback_target_reason_low_achievement_rate');

                        continue;
                    }
                }

                // 進捗登録日数条件
                $okrActivityInfo = $tOkrActivityRepos->getLatestEntityInAchievementRegistration($userInfo['userId'], $tTimeframe->getTimeframeId());
                $noRegistrationDays = (strtotime(DateUtility::getCurrentDateString()) - strtotime(DateUtility::transIntoDateString($okrActivityInfo['activityDatetime']))) / 86400;
                if ($noRegistrationDays >= $this->getParameter('feedback_target_condition_achievement_registration')) {
                    // メンバー情報をメール本文記載変数配列に格納
                    $memberName = $userInfo['lastName'] . ' ' . $userInfo['firstName'];
                    $data[$key1]['members'][$key2]['memberName'] = $memberName;
                    $data[$key1]['members'][$key2]['reason'] = sprintf($this->getParameter('feedback_target_reason_achievement_registration'), $this->getParameter('feedback_target_condition_achievement_registration'));

                    continue;
                }

                // 最終ログイン日時条件
                $noLoginDays = (strtotime(DateUtility::getCurrentDateString()) - strtotime(DateUtility::transIntoDateString($tLoginRepos->getLastLogin($userInfo['userId'])))) / 86400;
                if ($noLoginDays >= $this->getParameter('feedback_target_condition_login')) {
                    // メンバー情報をメール本文記載変数配列に格納
                    $memberName = $userInfo['lastName'] . ' ' . $userInfo['firstName'];
                    $data[$key1]['members'][$key2]['memberName'] = $memberName;
                    $data[$key1]['members'][$key2]['reason'] = sprintf($this->getParameter('feedback_target_reason_login'), $this->getParameter('feedback_target_condition_login'));

                    continue;
                }
            }
        }

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('feedback_target_report'));
        $tEmailReservation->setBody($this->renderView('mail/feedback_target_report.txt.twig', ['data' => $data]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(7, 30)));
        $this->persist($tEmailReservation);
    }

    /**
     * フィードバック対象者報告メール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // メール送信対象者を取得
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUsersForFeedbackTargetReportEmail();

        // トランザクション開始
        $this->beginTransaction();

        try {
            foreach ($mUserArray as $key => $mUser) {
                // メール作成・登録
                $this->createEmail($mUser);

                // バルクインサート
                if ($key % $bulkSize === 0) {
                    $this->flush();
                    $this->clear();
                }
            }

            $this->flush();
            $this->clear();
            $this->close();

            $this->commit();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logAlert('DB登録エラーが発生したためロールバックします');
            return DBConstant::EXIT_CODE_ERROR;
        }

        return $exitCode;
    }
}
