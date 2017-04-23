<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 請求情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class PaymentDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $paymentId;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $paymentDate;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $status;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $chargeAmount;

    /**
     * Set paymentId
     *
     * @param integer $paymentId
     *
     * @return PaymentDTO
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return integer
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return PaymentDTO
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return PaymentDTO
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set chargeAmount
     *
     * @param integer $chargeAmount
     *
     * @return PaymentDTO
     */
    public function setChargeAmount($chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;

        return $this;
    }

    /**
     * Get chargeAmount
     *
     * @return integer
     */
    public function getChargeAmount()
    {
        return $this->chargeAmount;
    }
}
