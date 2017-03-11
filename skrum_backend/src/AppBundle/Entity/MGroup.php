<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * MGroup
 *
 * @ORM\Table(name="m_group", indexes={@ORM\Index(name="idx_group_01", columns={"company_id"}), @ORM\Index(name="idx_group_02", columns={"leader_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MGroupRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class MGroup
{
    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string", length=255, nullable=false)
     */
    private $groupName;

    /**
     * @var string
     *
     * @ORM\Column(name="group_type", type="string", length=2, nullable=false)
     */
    private $groupType;

    /**
     * @var integer
     *
     * @ORM\Column(name="leader_user_id", type="integer", nullable=false)
     */
    private $leaderUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="mission", type="string", length=255, nullable=true)
     */
    private $mission;

    /**
     * @var boolean
     *
     * @ORM\Column(name="company_flg", type="boolean", nullable=true)
     */
    private $companyFlg;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=45, nullable=true)
     */
    private $imagePath;

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
     * @ORM\Column(name="group_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $groupId;

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
     * Set groupName
     *
     * @param string $groupName
     *
     * @return MGroup
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Set groupType
     *
     * @param string $groupType
     *
     * @return MGroup
     */
    public function setGroupType($groupType)
    {
        $this->groupType = $groupType;

        return $this;
    }

    /**
     * Get groupType
     *
     * @return string
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * Set leaderUserId
     *
     * @param integer $leaderUserId
     *
     * @return MGroup
     */
    public function setLeaderUserId($leaderUserId)
    {
        $this->leaderUserId = $leaderUserId;

        return $this;
    }

    /**
     * Get leaderUserId
     *
     * @return integer
     */
    public function getLeaderUserId()
    {
        return $this->leaderUserId;
    }

    /**
     * Set mission
     *
     * @param string $mission
     *
     * @return MGroup
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set companyFlg
     *
     * @param boolean $companyFlg
     *
     * @return MGroup
     */
    public function setCompanyFlg($companyFlg)
    {
        $this->companyFlg = $companyFlg;

        return $this;
    }

    /**
     * Get companyFlg
     *
     * @return boolean
     */
    public function getCompanyFlg()
    {
        return $this->companyFlg;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return MGroup
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MGroup
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
     * @return MGroup
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
     * @return MGroup
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
     * Get groupId
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\MCompany $company
     *
     * @return MGroup
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
