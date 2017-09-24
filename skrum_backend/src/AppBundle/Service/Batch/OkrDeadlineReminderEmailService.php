<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TEmailReservation;

/**
 * 目標期限日リマインダーメールサービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrDeadlineReminderEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param array $userInfo メール送信対象ユーザ情報
     * @param string $mailSendingConditionDatetimeString1 目標期限日リマインダーメール送信条件1（X日後）
     * @param string $mailSendingConditionDatetimeString2 目標期限日リマインダーメール送信条件2（X日後）
     * @return void
     */
    private function createEmail(array $userInfo, string $mailSendingConditionDatetimeString1, string $mailSendingConditionDatetimeString2)
    {
        $data = array();
        $data['userName'] = $userInfo['last_name'] . ' ' . $userInfo['first_name'];

        // 1日後の期限日のOKRを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray1 = $tOkrRepos->findBy(array('ownerType' => DBConstant::OKR_OWNER_TYPE_USER, 'ownerUser' => $userInfo['user_id'], 'endDate' => DateUtility::transIntoDatetime($mailSendingConditionDatetimeString1)));
        $tOkrArray2 = $tOkrRepos->findBy(array('ownerType' => DBConstant::OKR_OWNER_TYPE_USER, 'ownerUser' => $userInfo['user_id'], 'endDate' => DateUtility::transIntoDatetime($mailSendingConditionDatetimeString2)));

        $data['okrNameArray1'] = null;
        if (count($tOkrArray1) !== 0) {
            $data['daysLeft1'] = $this->getParameter('okr_deadline_mail_condition_1');
            foreach ($tOkrArray1 as $key => $tOkr1) {
                $data['okrNameArray1'][$key] = $tOkr1->getName();
            }
        }

        $data['okrNameArray2'] = null;
        if (count($tOkrArray2) !== 0) {
            $data['daysLeft2'] = $this->getParameter('okr_deadline_mail_condition_2');
            foreach ($tOkrArray2 as $key => $tOkr2) {
                $data['okrNameArray2'][$key] = $tOkr2->getName();
            }
        }

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($userInfo['email_address']);
        $tEmailReservation->setTitle($this->getParameter('okr_deadline_reminder'));
        $tEmailReservation->setBody($this->renderView('mail/okr_deadline_reminder.txt.twig', ['data' => $data, 'subdomain' => $userInfo['subdomain']]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(8, 0)));
        $this->persist($tEmailReservation);
    }

    /**
     * 目標期限日リマインダーメール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // リマインダーメール送信条件日時
        $mailSendingConditionDatetimeString1 = DateUtility::getXDaysAfterString($this->getParameter('okr_deadline_mail_condition_1'));
        $mailSendingConditionDatetimeString2 = DateUtility::getXDaysAfterString($this->getParameter('okr_deadline_mail_condition_2'));

        // メール送信対象者を取得
        $mUserRepos = $this->getMUserRepository();
        $userInfoArray = $mUserRepos->getUsersForOkrDeadlineReminderEmail($mailSendingConditionDatetimeString1, $mailSendingConditionDatetimeString2);

        // 二重送信を防ぐ
        $count = count($userInfoArray);
        for ($i = 0; $i < $count; ++$i) {
            if ($i === 0) {
                $previousUserId = $userInfoArray[$i]['user_id'];
            } else {
                if ($previousUserId === $userInfoArray[$i]['user_id']) {
                    unset($userInfoArray[$i]);
                } else {
                    $previousUserId = $userInfoArray[$i]['user_id'];
                }
            }
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            foreach ($userInfoArray as $key => $userInfo) {
                // メール作成・登録
                $this->createEmail($userInfo, $mailSendingConditionDatetimeString1, $mailSendingConditionDatetimeString2);

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
