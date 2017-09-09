<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TPayment
 *
 * @ORM\Table(name="t_payment", indexes={@ORM\Index(name="idx_payment_01", columns={"contract_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPaymentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TPayment
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="date", nullable=false)
     */
    private $paymentDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=2, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="charge_amount", type="decimal", precision=15, scale=0, nullable=false)
     */
    private $chargeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="settlement_amount", type="decimal", precision=15, scale=0, nullable=true)
     */
    private $settlementAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
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
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return TPayment
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
     * @return TPayment
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
     * Set settlementAmount
     *
     * @param string $settlementAmount
     *
     * @return TPayment
     */
    public function setSettlementAmount($settlementAmount)
    {
        $this->settlementAmount = $settlementAmount;

        return $this;
    }

    /**
     * Get settlementAmount
     *
     * @return string
     */
    public function getSettlementAmount()
    {
        return $this->settlementAmount;
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
