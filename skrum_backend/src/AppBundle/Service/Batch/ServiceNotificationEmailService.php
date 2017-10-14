<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;

/**
 * サービスお知らせメールサービスクラス
 *
 * @author naoharu.tazawa
 */
class ServiceNotificationEmailService extends BaseService
{
    /**
     * メール作成・登録
     *
     * @param MUser $mUser ユーザエンティティ
     * @param string $body 送信メール本文
     * @param MCompany $mCompany 会社エンティティ
     * @return void
     */
    private function createEmail(MUser $mUser, string $body, MCompany $mCompany)
    {
        // メール本文記載変数
        $data = array();
        $data['userName'] = $mUser->getLastName() . $mUser->getFirstName();
        $data['companyName'] = $mCompany->getCompanyName();
        $data['body'] = $body;

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('service_notification'));
        $tEmailReservation->setBody($this->renderView('mail/service_notification.txt.twig', ['data' => $data, 'subdomain' => $mCompany->getSubdomain()]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::transIntoDatetime(DateUtility::getTodayXYTimeDatetimeString(12, 30)));
        $this->persist($tEmailReservation);
    }

    /**
     * サービスお知らせメール
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // 送信するサービスお知らせメールを取得
        try {
            $body = file_get_contents(__DIR__ . '/../../../../app/service_notification_email/email.txt');
        } catch (\Exception $e) {
            $body = null;
        }

        // ファイルが無い場合処理をしない
        if ($body) {
            // メール送信対象者を取得
            $mUserRepos = $this->getMUserRepository();
            $mUserArray = $mUserRepos->getUsersForServiceNotificationEmail();

            // トランザクション開始
            $this->beginTransaction();

            try {
                foreach ($mUserArray as $key => $mUser) {
                    // メール作成・登録
                    $this->createEmail($mUser, $body, $mUser->getCompany());

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
        }

        return $exitCode;
    }
}
