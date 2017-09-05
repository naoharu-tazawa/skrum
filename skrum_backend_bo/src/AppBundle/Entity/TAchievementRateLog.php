<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TAchievementRateLog
 *
 * @ORM\Table(name="t_achievement_rate_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TAchievementRateLogRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TAchievementRateLog
{
    /**
     * @var string
     *
     * @ORM\Column(name="owner_type", type="string", length=20, nullable=false)
     */
    private $ownerType;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_user_id", type="integer", nullable=true)
     */
    private $ownerUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_group_id", type="integer", nullable=true)
     */
    private $ownerGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner_company_id", type="integer", nullable=true)
     */
    private $ownerCompanyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="timeframe_id", type="integer", nullable=false)
     */
    private $timeframeId;

    /**
     * @var string
     *
     * @ORM\Column(name="achievement_rate", type="decimal", precision=15, scale=1, nullable=false)
     */
    private $achievementRate;

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
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return TAchievementRateLog
     */
    public function setOwnerType($ownerType)
    {
        $this->ownerType = $ownerType;

        return $this;
    }

    /**
     * Get ownerType
     *
     * @return string
     */
    public function getOwnerType()
    {
        return $this->ownerType;
    }

    /**
     * Set ownerUserId
     *
     * @param integer $ownerUserId
     *
     * @return TAchievementRateLog
     */
    public function setOwnerUserId($ownerUserId)
    {
        $this->ownerUserId = $ownerUserId;

        return $this;
    }

    /**
     * Get ownerUserId
     *
     * @return integer
     */
    public function getOwnerUserId()
    {
        return $this->ownerUserId;
    }

    /**
     * Set ownerGroupId
     *
     * @param integer $ownerGroupId
     *
     * @return TAchievementRateLog
     */
    public function setOwnerGroupId($ownerGroupId)
    {
        $this->ownerGroupId = $ownerGroupId;

        return $this;
    }

    /**
     * Get ownerGroupId
     *
     * @return integer
     */
    public function getOwnerGroupId()
    {
        return $this->ownerGroupId;
    }

    /**
     * Set ownerCompanyId
     *
     * @param integer $ownerCompanyId
     *
     * @return TAchievementRateLog
     */
    public function setOwnerCompanyId($ownerCompanyId)
    {
        $this->ownerCompanyId = $ownerCompanyId;

        return $this;
    }

    /**
     * Get ownerCompanyId
     *
     * @return integer
     */
    public function getOwnerCompanyId()
    {
        return $this->ownerCompanyId;
    }

    /**
     * Set timeframeId
     *
     * @param integer $timeframeId
     *
     * @return TAchievementRateLog
     */
    public function setTimeframeId($timeframeId)
    {
        $this->timeframeId = $timeframeId;

        return $this;
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
     * Set achievementRate
     *
     * @param string $achievementRate
     *
     * @return TAchievementRateLog
     */
    public function setAchievementRate($achievementRate)
    {
        $this->achievementRate = $achievementRate;

        return $this;
    }

    /**
     * Get achievementRate
     *
     * @return string
     */
    public function getAchievementRate()
    {
        return $this->achievementRate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TAchievementRateLog
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
     * @return TAchievementRateLog
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
     * @return TAchievementRateLog
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
