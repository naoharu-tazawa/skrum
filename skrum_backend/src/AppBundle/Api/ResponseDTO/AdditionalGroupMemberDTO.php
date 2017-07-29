<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * グループメンバー追加DTO

 * @JSON\ExclusionPolicy("none")
 */
class AdditionalGroupMemberDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\MemberDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\MemberDTO")
     */
    private $user;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\GroupDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\GroupDTO")
     */
    private $group;

    /**
     * Set user
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\MemberDTO $user
     *
     * @return AdditionalGroupMemberDTO
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\MemberDTO
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\GroupDTO $group
     *
     * @return AdditionalGroupMemberDTO
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\GroupDTO
     */
    public function getGroup()
    {
        return $this->group;
    }
}
