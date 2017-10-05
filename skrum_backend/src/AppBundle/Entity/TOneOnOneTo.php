<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TOneOnOneTo
 *
 * @ORM\Table(name="t_one_on_one_to", indexes={@ORM\Index(name="fk_one_on_one_to_one_on_one_id_idx", columns={"one_on_one_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TOneOnOneToRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TOneOnOneTo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

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
     * @var \AppBundle\Entity\TOneOnOne
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TOneOnOne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="one_on_one_id", referencedColumnName="id")
     * })
     */
    private $oneOnOne;



    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return TOneOnOneTo
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TOneOnOneTo
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
     * @return TOneOnOneTo
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
     * @return TOneOnOneTo
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
     * Set oneOnOne
     *
     * @param \AppBundle\Entity\TOneOnOne $oneOnOne
     *
     * @return TOneOnOneTo
     */
    public function setOneOnOne(\AppBundle\Entity\TOneOnOne $oneOnOne = null)
    {
        $this->oneOnOne = $oneOnOne;

        return $this;
    }

    /**
     * Get oneOnOne
     *
     * @return \AppBundle\Entity\TOneOnOne
     */
    public function getOneOnOne()
    {
        return $this->oneOnOne;
    }
}
