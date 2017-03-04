<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Period
 *
 * @ORM\Table(name="period", indexes={@ORM\Index(name="fk_period_USER1_idx", columns={"USER_USER_ID"})})
 * @ORM\Entity
 */
class Period
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="PERIOD_START", type="datetime", nullable=true)
     */
    private $periodStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="PERIOD_END", type="datetime", nullable=true)
     */
    private $periodEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="PERIOD_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $periodId;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="USER_USER_ID", referencedColumnName="USER_ID")
     * })
     */
    private $userUser;



    /**
     * Set periodStart
     *
     * @param \DateTime $periodStart
     *
     * @return Period
     */
    public function setPeriodStart($periodStart)
    {
        $this->periodStart = $periodStart;

        return $this;
    }

    /**
     * Get periodStart
     *
     * @return \DateTime
     */
    public function getPeriodStart()
    {
        return $this->periodStart;
    }

    /**
     * Set periodEnd
     *
     * @param \DateTime $periodEnd
     *
     * @return Period
     */
    public function setPeriodEnd($periodEnd)
    {
        $this->periodEnd = $periodEnd;

        return $this;
    }

    /**
     * Get periodEnd
     *
     * @return \DateTime
     */
    public function getPeriodEnd()
    {
        return $this->periodEnd;
    }

    /**
     * Set periodId
     *
     * @param integer $periodId
     *
     * @return Period
     */
    public function setPeriodId($periodId)
    {
        $this->periodId = $periodId;

        return $this;
    }

    /**
     * Get periodId
     *
     * @return integer
     */
    public function getPeriodId()
    {
        return $this->periodId;
    }

    /**
     * Set userUser
     *
     * @param \AppBundle\Entity\User $userUser
     *
     * @return Period
     */
    public function setUserUser(\AppBundle\Entity\User $userUser)
    {
        $this->userUser = $userUser;

        return $this;
    }

    /**
     * Get userUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserUser()
    {
        return $this->userUser;
    }
}
