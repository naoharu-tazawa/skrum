<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;

/**
 * グループ進捗状況レポートメールサービスクラス
 *
 * @author naoharu.tazawa
 */
class GroupReportEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param MUser $mUser ユーザエンティティ
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function createEmail(MUser $mUser, string $subdomain)
    {
        // デフォルトタイムフレームエンティティを取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframe = $tTimeframeRepos->findOneBy(array('company' => $mUser->getCompany()->getCompanyId(), 'defaultFlg' => DBConstant::FLG_TRUE));

        // 所属グループの進捗率（前日・前々日）を取得
        $tGroupMemberRepos = $this->getTGroupMemberRepository();
        $groupLogInfoArray = $tGroupMemberRepos->getGroupAchievementRateLog($mUser->getUserId(), $tTimeframe->getTimeframeId());

        // メール本文記載変数
        $data = array();

        foreach ($groupLogInfoArray as $key => $groupLogInfo) {
            // グループ情報をメール本文記載変数配列に格納
            $data[$key]['groupName'] = $groupLogInfo['groupName'];
            $data[$key]['achievementRateToday'] = $groupLogInfo['achievementRateToday'];
            $data[$key]['achievementRateYesterday'] = $groupLogInfo['achievementRateYesterday'];
        }

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('group_achievement_rate_report'));
        $tEmailReservation->setBody($this->renderView('mail/group_achievement_rate_report.txt.twig', ['data' => $data, 'subdomain' => $subdomain]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(8, 0)));
        $this->persist($tEmailReservation);
    }

    /**
     * メンバー進捗状況レポートメール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // メール送信対象者を取得
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUsersForGroupAchievementReportEmail();

        // トランザクション開始
        $this->beginTransaction();

        try {
            foreach ($mUserArray as $key => $mUser) {
                // メール作成・登録
                $this->createEmail($mUser, $mUser->getCompany()->getSubdomain());

                // バルクインサート
                if (($key + 1) % $bulkSize === 0) {
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
