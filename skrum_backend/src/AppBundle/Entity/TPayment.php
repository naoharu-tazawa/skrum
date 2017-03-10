<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * TPayment
 *
 * @ORM\Table(name="t_payment", indexes={@ORM\Index(name="idx_payment_01", columns={"contract_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPaymentRepository")
 * @JSON\ExclusionPolicy("all")
 */
class TPayment
{
    /**
     * @var string
     *
     * @ORM\Column(name="charge_amount", type="decimal", precision=15, scale=0, nullable=false)
     */
    private $chargeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_code", type="string", length=8, nullable=true)
     */
    private $bankCode;

    /**
     * @var string
     *
     * @ORM\Column(name="branch_code", type="string", length=4, nullable=true)
     */
    private $branchCode;

    /**
     * @var string
     *
     * @ORM\Column(name="account_type", type="string", length=2, nullable=true)
     */
    private $accountType;

    /**
     * @var string
     *
     * @ORM\Column(name="account_number", type="string", length=8, nullable=true)
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="account_holder", type="string", length=255, nullable=true)
     */
    private $accountHolder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $paymentId;

    /**
     * @var \AppBundle\Entity\TContract
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TContract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="contract_id")
     * })
     */
    private $contract;



    /**
     * Set chargeAmount
     *
     * @param string $chargeAmount
     *
     * @return TPayment
     */
    public function setChargeAmount($chargeAmount)
    {
        $this->chargeAmount = $chargeAmount;

        return $this;
    }

    /**
     * Get chargeAmount
     *
     * @return string
     */
    public function getChargeAmount()
    {
        return $this->chargeAmount;
    }

    /**
     * Set bankCode
     *
     * @param string $bankCode
     *
     * @return TPayment
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * Get bankCode
     *
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * Set branchCode
     *
     * @param string $branchCode
     *
     * @return TPayment
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;

        return $this;
    }

    /**
     * Get branchCode
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Set accountType
     *
     * @param string $accountType
     *
     * @return TPayment
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return TPayment
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set accountHolder
     *
     * @param string $accountHolder
     *
     * @return TPayment
     */
    public function setAccountHolder($accountHolder)
    {
        $this->accountHolder = $accountHolder;

        return $this;
    }

    /**
     * Get accountHolder
     *
     * @return string
     */
    public function getAccountHolder()
    {
        return $this->accountHolder;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TPayment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TPayment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return TPayment
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
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
     * Set contract
     *
     * @param \AppBundle\Entity\TContract $contract
     *
     * @return TPayment
     */
    public function setContract(\AppBundle\Entity\TContract $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return \AppBundle\Entity\TContract
     */
    public function getContract()
    {
        return $this->contract;
    }
}
