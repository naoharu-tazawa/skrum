<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupTag
 *
 * @ORM\Table(name="GROUP_TAG", indexes={@ORM\Index(name="fk_GROUP_TAG_GROUP1_idx", columns={"GROUP_ID"})})
 * @ORM\Entity
 */
class GroupTag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="GROUP_TAG_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $groupTagId;

    /**
     * @var \AppBundle\Entity\Group
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="GROUP_ID", referencedColumnName="GROUP_ID")
     * })
     */
    private $group;



    /**
     * Get groupTagId
     *
     * @return integer
     */
    public function getGroupTagId()
    {
        return $this->groupTagId;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     *
     * @return GroupTag
     */
    public function setGroup(\AppBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
