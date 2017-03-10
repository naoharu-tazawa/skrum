<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * TTimeframe
 *
 * @ORM\Table(name="t_timeframe", indexes={@ORM\Index(name="idx_timeframe_01", columns={"company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TTimeframeRepository")
 * @JSON\ExclusionPolicy("all")
 */
class TTimeframe
{
    /**
     * @var string
     *
     * @ORM\Column(name="timeframe_name", type="string", length=255, nullable=false)
     */
    private $timeframeName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_flg", type="boolean", nullable=true)
     */
    private $defaultFlg;

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
     * @ORM\Column(name="timeframe_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $timeframeId;

    /**
     * @var \AppBundle\Entity\MCompany
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="company_id")
     * })
     */
    private $company;



    /**
     * Set timeframeName
     *
     * @param string $timeframeName
     *
     * @return TTimeframe
     */
    public function setTimeframeName($timeframeName)
    {
        $this->timeframeName = $timeframeName;

        return $this;
    }

    /**
     * Get timeframeName
     *
     * @return string
     */
    public function getTimeframeName()
    {
        return $this->timeframeName;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return TTimeframe
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return TTimeframe
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set defaultFlg
     *
     * @param boolean $defaultFlg
     *
     * @return TTimeframe
     */
    public function setDefaultFlg($defaultFlg)
    {
        $this->defaultFlg = $defaultFlg;

        return $this;
    }

    /**
     * Get defaultFlg
     *
     * @return boolean
     */
    public function getDefaultFlg()
    {
        return $this->defaultFlg;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TTimeframe
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
     * @return TTimeframe
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
     * @return TTimeframe
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
     * Get timeframeId
     *
     * @return integer
     */
    public function getTimeframeId()
    {
        return $this->timeframeId;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\MCompany $company
     *
     * @return TTimeframe
     */
    public function setCompany(\AppBundle\Entity\MCompany $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\MCompany
     */
    public function getCompany()
    {
        return $this->company;
    }
}
