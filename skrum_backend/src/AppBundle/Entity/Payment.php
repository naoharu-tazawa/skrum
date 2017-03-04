<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="PAYMENT")
 * @ORM\Entity
 */
class Payment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PAYMENT_FROM", type="integer", nullable=false)
     */
    private $paymentFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK_NAME", type="string", length=45, nullable=true)
     */
    private $bankName;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK_BRANCH_NAME", type="string", length=45, nullable=true)
     */
    private $bankBranchName;

    /**
     * @var integer
     *
     * @ORM\Column(name="BANK_BRANCH_NUM", type="integer", nullable=true)
     */
    private $bankBranchNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="ACCOUNT_TYPE", type="integer", nullable=true)
     */
    private $accountType;

    /**
     * @var integer
     *
     * @ORM\Column(name="ACCOUNT_NUM", type="integer", nullable=true)
     */
    private $accountNum;

    /**
     * @var string
     *
     * @ORM\Column(name="ACCOUNT_HOLDER_NAME", type="string", length=45, nullable=true)
     */
    private $accountHolderName;

    /**
     * @var integer
     *
     * @ORM\Column(name="PAYMENT_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $paymentId;



    /**
     * Set paymentFrom
     *
     * @param integer $paymentFrom
     *
     * @return Payment
     */
    public function setPaymentFrom($paymentFrom)
    {
        $this->paymentFrom = $paymentFrom;

        return $this;
    }

    /**
     * Get paymentFrom
     *
     * @return integer
     */
    public function getPaymentFrom()
    {
        return $this->paymentFrom;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return Payment
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set bankBranchName
     *
     * @param string $bankBranchName
     *
     * @return Payment
     */
    public function setBankBranchName($bankBranchName)
    {
        $this->bankBranchName = $bankBranchName;

        return $this;
    }

    /**
     * Get bankBranchName
     *
     * @return string
     */
    public function getBankBranchName()
    {
        return $this->bankBranchName;
    }

    /**
     * Set bankBranchNum
     *
     * @param integer $bankBranchNum
     *
     * @return Payment
     */
    public function setBankBranchNum($bankBranchNum)
    {
        $this->bankBranchNum = $bankBranchNum;

        return $this;
    }

    /**
     * Get bankBranchNum
     *
     * @return integer
     */
    public function getBankBranchNum()
    {
        return $this->bankBranchNum;
    }

    /**
     * Set accountType
     *
     * @param integer $accountType
     *
     * @return Payment
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return integer
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set accountNum
     *
     * @param integer $accountNum
     *
     * @return Payment
     */
    public function setAccountNum($accountNum)
    {
        $this->accountNum = $accountNum;

        return $this;
    }

    /**
     * Get accountNum
     *
     * @return integer
     */
    public function getAccountNum()
    {
        return $this->accountNum;
    }

    /**
     * Set accountHolderName
     *
     * @param string $accountHolderName
     *
     * @return Payment
     */
    public function setAccountHolderName($accountHolderName)
    {
        $this->accountHolderName = $accountHolderName;

        return $this;
    }

    /**
     * Get accountHolderName
     *
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->accountHolderName;
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
}
