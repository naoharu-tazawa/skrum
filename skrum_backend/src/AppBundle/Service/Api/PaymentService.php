<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\PaymentDTO;

/**
 * 請求サービスクラス
 *
 * @author naoharu.tazawa
 */
class PaymentService extends BaseService
{
    /**
     * 契約プラン情報取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getPaymentInfo($companyId)
    {
        // 請求プラン情報を取得
        $tPaymentRepos = $this->getTPaymentRepository();
        $tPaymentArray = $tPaymentRepos->getPayments($companyId);

        $paymentDTOArray = array();
        foreach ($tPaymentArray as $tPayment) {
            $paymentDTO = new PaymentDTO();
            $paymentDTO->setPaymentId($tPayment->getPaymentId());
            $paymentDTO->setPaymentDate($tPayment->getPaymentDate());
            $paymentDTO->setStatus($tPayment->getStatus());
            $paymentDTO->setChargeAmount($tPayment->getChargeAmount());

            $paymentDTOArray[] = $paymentDTO;
        }

        return $paymentDTOArray;
    }
}
