<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * グループメンバーDTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupMemberDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO")
     */
    private $group;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $members;

    /**
     * Set group
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO $group
     *
     * @return GroupMemberDTO
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set members
     *
     * @param array $members
     *
     * @return GroupMemberDTO
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }
}
