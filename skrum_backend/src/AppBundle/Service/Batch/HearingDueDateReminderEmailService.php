<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;
use AppBundle\Entity\TOneOnOne;

/**
 * ヒアリング回答期限日リマインダーメールサービスクラス
 *
 * @author naoharu.tazawa
 */
class HearingDueDateReminderEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param MUser $mUser メール送信対象ユーザエンティティ
     * @param TOneOnOne $tOneOnOne 1on1エンティティ
     * @return void
     */
    private function createEmail(MUser $mUser, TOneOnOne $tOneOnOne)
    {
        $mUserRepos = $this->getMUserRepository();
        $senderUserEntity = $mUserRepos->find($tOneOnOne->getSenderUserId());

        $data = array();
        $data['userName'] = $mUser->getLastName() . ' ' . $mUser->getFirstName();
        $data['senderUserName'] = $senderUserEntity->getLastName() . ' ' . $senderUserEntity->getFirstName();
        $data['body'] = $tOneOnOne->getBody();

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('hearing_due_date_reminder'));
        $tEmailReservation->setBody($this->renderView('mail/hearing_due_date_reminder.txt.twig', ['data' => $data, 'subdomain' => $mUser->getCompany()->getSubdomain()]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(8, 10)));
        $this->persist($tEmailReservation);
    }

    /**
     * ヒアリング回答期限日リマインダーメール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // メール送信対象者を取得
        $mUserRepos = $this->getMUserRepository();
        $userInfoArray = $mUserRepos->getUsersForHearingDueDateReminderEmail();

        // トランザクション開始
        $this->beginTransaction();

        try {
            $count = count($userInfoArray);
            for ($i = 0; $i < $count; $i += 2) {
                // メール作成・登録
                $this->createEmail($userInfoArray[$i], $userInfoArray[$i + 1]);

                // バルクインサート
                if ($i % $bulkSize === 0) {
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
