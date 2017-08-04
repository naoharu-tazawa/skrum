<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TEmailReservation;

/**
 * 進捗登録リマインダーメールサービスクラス
 *
 * @author naoharu.tazawa
 */
class AchievementRegistrationReminderEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param array $userInfo メール送信対象ユーザ情報
     * @return void
     */
    private function createEmail(array $userInfo)
    {
        $data = array();
        $data['userName'] = $userInfo['last_name'] . ' ' . $userInfo['first_name'];

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($userInfo['email_address']);
        $tEmailReservation->setTitle($this->getParameter('achievement_registration_reminder'));
        $tEmailReservation->setBody($this->renderView('mail/achievement_registration_reminder.txt.twig', ['data' => $data]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(15, 0)));
        $this->persist($tEmailReservation);
    }

    /**
     * 進捗登録リマインダーメール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // リマインダーメール送信条件日時
        $mailSendingConditionDatetimeString = DateUtility::getXDaysBeforeString($this->getParameter('achievement_registration_mail_condition'));

        // メール送信対象者を取得
        $mUserRepos = $this->getMUserRepository();
        $userInfoArray = $mUserRepos->getUsersForAchievementRegistrationReminderEmail($mailSendingConditionDatetimeString);

        // トランザクション開始
        $this->beginTransaction();

        try {
            foreach ($userInfoArray as $key => $userInfo) {
                // メール作成・登録
                $this->createEmail($userInfo);

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
