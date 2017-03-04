<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ObjectiveOwner
 *
 * @ORM\Table(name="OBJECTIVE_OWNER", indexes={@ORM\Index(name="fk_OBJECTIVE_OWNER_USER1_idx", columns={"USER_USER_ID"}), @ORM\Index(name="fk_OBJECTIVE_OWNER_GROUP1_idx", columns={"GROUP_GROUP_ID"})})
 * @ORM\Entity
 */
class ObjectiveOwner
{
    /**
     * @var integer
     *
     * @ORM\Column(name="OBJECTIVE_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $objectiveId;

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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="USER_USER_ID", referencedColumnName="USER_ID")
     * })
     */
    private $userUser;



    /**
     * Get objectiveId
     *
     * @return integer
     */
    public function getObjectiveId()
    {
        return $this->objectiveId;
    }

    /**
     * Set groupGroup
     *
     * @param \AppBundle\Entity\Group $groupGroup
     *
     * @return ObjectiveOwner
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

    /**
     * Set userUser
     *
     * @param \AppBundle\Entity\User $userUser
     *
     * @return ObjectiveOwner
     */
    public function setUserUser(\AppBundle\Entity\User $userUser = null)
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
