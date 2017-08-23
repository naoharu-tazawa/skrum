<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\DateUtility;

/**
 * メール送信サービスクラス
 *
 * @author naoharu.tazawa
 */
class EmailSendingService extends BaseService
{
    /**
     * メール送信処理
     *
     * @param string $fromAddress Fromアドレス
     * @param string $toAddress Toアドレス
     * @param string $subject 件名
     * @param string $body 本文
     * @return boolean メール送信結果
     */
    private function sendEmail(string $fromAddress, string $toAddress, string $subject, string $body): bool
    {
        // メッセージ作成
        $message = \Swift_Message::newInstance()
            ->setFrom(array($fromAddress => 'Skrum'))
            ->setTo($toAddress)
            ->setSubject($subject)
            ->setBody($body);

        // メール送信
        $result = $this->getContainer()->get('mailer')->send($message);

        return $result;
    }

    /**
     * メール送信
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        $exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // 送信対象メール送信
        $tEmailReservationRepos = $this->getTEmailReservationRepository();
        $tEmailReservationArray = $tEmailReservationRepos->getSendingEmails($bulkSize);

        foreach ($tEmailReservationArray as $tEmailReservation) {
            // Eメール送信処理
            $result = $this->sendEmail(
                    $this->getParameter('from_address'),
                    $tEmailReservation->getToEmailAddress(),
                    $tEmailReservation->getTitle(),
                    $tEmailReservation->getBody()
                );

            try {
                if ($result) {
                    $tEmailReservation->setSendingDatetime(DateUtility::getCurrentDatetime());
                    $this->flush();
                } else {
                    $this->logAlert('メールの送信に失敗しました');
                    $exitCode = DBConstant::EXIT_CODE_RETRY;
                }
            } catch (\Exception $e) {
                $this->logAlert('DBエラーが発生しました');
                $exitCode = DBConstant::EXIT_CODE_ERROR;
            }
        }

        return $exitCode;
    }
}
