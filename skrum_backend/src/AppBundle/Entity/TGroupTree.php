<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * TGroupTree
 *
 * @ORM\Table(name="t_group_tree", uniqueConstraints={@ORM\UniqueConstraint(name="ui_group_tree_01", columns={"group_tree_path"})}, indexes={@ORM\Index(name="idx_group_tree_01", columns={"group_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TGroupTreeRepository")
 * @JSON\ExclusionPolicy("all")
 */
class TGroupTree
{
    /**
     * @var string
     *
     * @ORM\Column(name="group_tree_path", type="string", length=3072, nullable=false)
     */
    private $groupTreePath;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\MGroup
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="group_id")
     * })
     */
    private $group;



    /**
     * Set groupTreePath
     *
     * @param string $groupTreePath
     *
     * @return TGroupTree
     */
    public function setGroupTreePath($groupTreePath)
    {
        $this->groupTreePath = $groupTreePath;

        return $this;
    }

    /**
     * Get groupTreePath
     *
     * @return string
     */
    public function getGroupTreePath()
    {
        return $this->groupTreePath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TGroupTree
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
     * @return TGroupTree
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
     * @return TGroupTree
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
     * Set group
     *
     * @param \AppBundle\Entity\MGroup $group
     *
     * @return TGroupTree
     */
    public function setGroup(\AppBundle\Entity\MGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\MGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
