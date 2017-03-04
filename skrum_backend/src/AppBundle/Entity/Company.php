<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="COMPANY", indexes={@ORM\Index(name="fk_COMPANY_OBJECTIVE_OWNER1_idx", columns={"OWNER_ID"}), @ORM\Index(name="fk_COMPANY_PLAN1_idx", columns={"PLAN_ID"}), @ORM\Index(name="fk_COMPANY_PAYMENT1_idx", columns={"PAYMENT_ID"})})
 * @ORM\Entity
 */
class Company
{
    /**
     * @var integer
     *
     * @ORM\Column(name="COMPANY_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $companyId;

    /**
     * @var \AppBundle\Entity\ObjectiveOwner
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ObjectiveOwner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OWNER_ID", referencedColumnName="OBJECTIVE_ID")
     * })
     */
    private $owner;

    /**
     * @var \AppBundle\Entity\Payment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Payment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PAYMENT_ID", referencedColumnName="PAYMENT_ID")
     * })
     */
    private $payment;

    /**
     * @var \AppBundle\Entity\Plan
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PLAN_ID", referencedColumnName="PLAN_ID")
     * })
     */
    private $plan;



    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\ObjectiveOwner $owner
     *
     * @return Company
     */
    public function setOwner(\AppBundle\Entity\ObjectiveOwner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\ObjectiveOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set payment
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return Company
     */
    public function setPayment(\AppBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \AppBundle\Entity\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set plan
     *
     * @param \AppBundle\Entity\Plan $plan
     *
     * @return Company
     */
    public function setPlan(\AppBundle\Entity\Plan $plan = null)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \AppBundle\Entity\Plan
     */
    public function getPlan()
    {
        return $this->plan;
    }
}
