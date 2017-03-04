<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGroup
 *
 * @ORM\Table(name="USER_GROUP", indexes={@ORM\Index(name="fk_USER_GROUP_USER_idx", columns={"USER_ID"}), @ORM\Index(name="fk_USER_GROUP_GROUP1_idx", columns={"GROUP_GROUP_ID"})})
 * @ORM\Entity
 */
class UserGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="GROUP_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $groupId;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="USER_ID", referencedColumnName="USER_ID")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Group
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="GROUP_GROUP_ID", referencedColumnName="GROUP_ID")
     * })
     */
    private $groupGroup;



    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return UserGroup
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserGroup
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set groupGroup
     *
     * @param \AppBundle\Entity\Group $groupGroup
     *
     * @return UserGroup
     */
    public function setGroupGroup(\AppBundle\Entity\Group $groupGroup = null)
    {
        $this->groupGroup = $groupGroup;

        return $this;
    }

    /**
     * Get groupGroup
     *
     * @return \AppBundle\Entity\Group
     */
    public function getGroupGroup()
    {
        return $this->groupGroup;
    }
}
