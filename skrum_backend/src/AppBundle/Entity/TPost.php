<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TPost
 *
 * @ORM\Table(name="t_post", indexes={@ORM\Index(name="idx_post_01", columns={"okr_activity_id"}), @ORM\Index(name="idx_post_02", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPostRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TPost
{
    /**
     * @var integer
     *
     * @ORM\Column(name="timeline_owner_group_id", type="integer", nullable=true)
     */
    private $timelineOwnerGroupId;

    /**
     * @var integer
     *
     * @ORM\Column(name="poster_id", type="integer", nullable=true)
     */
    private $posterId;

    /**
     * @var string
     *
     * @ORM\Column(name="post", type="string", length=3072, nullable=true)
     */
    private $post;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_datetime", type="datetime", nullable=false)
     */
    private $postedDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="disclosure_type", type="string", length=2, nullable=false)
     */
    private $disclosureType;

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
     * @var \AppBundle\Entity\TOkrActivity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TOkrActivity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="okr_activity_id", referencedColumnName="id")
     * })
     */
    private $okrActivity;

    /**
     * @var \AppBundle\Entity\TPost
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TPost")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;



    /**
     * Set timelineOwnerGroupId
     *
     * @param integer $timelineOwnerGroupId
     *
     * @return TPost
     */
    public function setTimelineOwnerGroupId($timelineOwnerGroupId)
    {
        $this->timelineOwnerGroupId = $timelineOwnerGroupId;

        return $this;
    }

    /**
     * Get timelineOwnerGroupId
     *
     * @return integer
     */
    public function getTimelineOwnerGroupId()
    {
        return $this->timelineOwnerGroupId;
    }

    /**
     * Set posterId
     *
     * @param integer $posterId
     *
     * @return TPost
     */
    public function setPosterId($posterId)
    {
        $this->posterId = $posterId;

        return $this;
    }

    /**
     * Get posterId
     *
     * @return integer
     */
    public function getPosterId()
    {
        return $this->posterId;
    }

    /**
     * Set post
     *
     * @param string $post
     *
     * @return TPost
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set postedDatetime
     *
     * @param \DateTime $postedDatetime
     *
     * @return TPost
     */
    public function setPostedDatetime($postedDatetime)
    {
        $this->postedDatetime = $postedDatetime;

        return $this;
    }

    /**
     * Get postedDatetime
     *
     * @return \DateTime
     */
    public function getPostedDatetime()
    {
        return $this->postedDatetime;
    }

    /**
     * Set disclosureType
     *
     * @param string $disclosureType
     *
     * @return TPost
     */
    public function setDisclosureType($disclosureType)
    {
        $this->disclosureType = $disclosureType;

        return $this;
    }

    /**
     * Get disclosureType
     *
     * @return string
     */
    public function getDisclosureType()
    {
        return $this->disclosureType;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TPost
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
     * @return TPost
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
     * @return TPost
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

    /**
     * Set okrActivity
     *
     * @param \AppBundle\Entity\TOkrActivity $okrActivity
     *
     * @return TPost
     */
    public function setOkrActivity(\AppBundle\Entity\TOkrActivity $okrActivity = null)
    {
        $this->okrActivity = $okrActivity;

        return $this;
    }

    /**
     * Get okrActivity
     *
     * @return \AppBundle\Entity\TOkrActivity
     */
    public function getOkrActivity()
    {
        return $this->okrActivity;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\TPost $parent
     *
     * @return TPost
     */
    public function setParent(\AppBundle\Entity\TPost $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\TPost
     */
    public function getParent()
    {
        return $this->parent;
    }
}
