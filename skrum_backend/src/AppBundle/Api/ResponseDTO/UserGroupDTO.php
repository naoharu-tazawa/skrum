<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ユーザ所属グループ一覧DTO

 * @JSON\ExclusionPolicy("none")
 */
class UserGroupDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO")
     */
    private $user;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $groups;

    /**
     * Set user
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO $user
     *
     * @return UserGroupDTO
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set groups
     *
     * @param array $groups
     *
     * @return UserGroupDTO
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
