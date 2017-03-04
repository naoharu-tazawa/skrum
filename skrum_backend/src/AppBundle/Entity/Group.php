<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="GROUP")
 * @ORM\Entity
 */
class Group
{
    /**
     * @var string
     *
     * @ORM\Column(name="GROUP_NAME", type="string", length=45, nullable=false)
     */
    private $groupName;

    /**
     * @var integer
     *
     * @ORM\Column(name="GROUP_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $groupId;



    /**
     * Set groupName
     *
     * @param string $groupName
     *
     * @return Group
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
     * Get groupId
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->groupId;
    }
}
