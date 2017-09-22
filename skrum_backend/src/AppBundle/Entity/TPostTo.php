<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TPostTo
 *
 * @ORM\Table(name="t_post_to", indexes={@ORM\Index(name="fk_post_to_post_id_idx", columns={"post_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPostToRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TPostTo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="timeline_owner_group_id", type="integer", nullable=false)
     */
    private $timelineOwnerGroupId;

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
     * @var \AppBundle\Entity\TPost
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TPost")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     * })
     */
    private $post;



    /**
     * Set timelineOwnerGroupId
     *
     * @param integer $timelineOwnerGroupId
     *
     * @return TPostTo
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TPostTo
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
     * @return TPostTo
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
     * @return TPostTo
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
     * Set post
     *
     * @param \AppBundle\Entity\TPost $post
     *
     * @return TPostTo
     */
    public function setPost(\AppBundle\Entity\TPost $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AppBundle\Entity\TPost
     */
    public function getPost()
    {
        return $this->post;
    }
}
