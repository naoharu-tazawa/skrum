<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MNormalTimeframeDetail
 *
 * @ORM\Table(name="m_normal_timeframe_detail", indexes={@ORM\Index(name="idx_normal_timeframe_detail_02", columns={"start_date"}), @ORM\Index(name="idx_normal_timeframe_detail_03", columns={"end_date"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MNormalTimeframeDetailRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class MNormalTimeframeDetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="normal_timeframe_id", type="integer", nullable=false)
     */
    private $normalTimeframeId;

    /**
     * @var string
     *
     * @ORM\Column(name="timeframe_name", type="string", length=45, nullable=false)
     */
    private $timeframeName;

    /**
     * @var string
     *
     * @ORM\Column(name="start_date", type="string", length=5, nullable=false)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="end_date", type="string", length=5, nullable=false)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="display_order", type="string", length=45, nullable=false)
     */
    private $displayOrder;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set normalTimeframeId
     *
     * @param integer $normalTimeframeId
     *
     * @return MNormalTimeframeDetail
     */
    public function setNormalTimeframeId($normalTimeframeId)
    {
        $this->normalTimeframeId = $normalTimeframeId;

        return $this;
    }

    /**
     * Get normalTimeframeId
     *
     * @return integer
     */
    public function getNormalTimeframeId()
    {
        return $this->normalTimeframeId;
    }

    /**
     * Set timeframeName
     *
     * @param string $timeframeName
     *
     * @return MNormalTimeframeDetail
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
     * @param string $startDate
     *
     * @return MNormalTimeframeDetail
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param string $endDate
     *
     * @return MNormalTimeframeDetail
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set displayOrder
     *
     * @param string $displayOrder
     *
     * @return MNormalTimeframeDetail
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get displayOrder
     *
     * @return string
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MNormalTimeframeDetail
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
     * @return MNormalTimeframeDetail
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
     * @return MNormalTimeframeDetail
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
