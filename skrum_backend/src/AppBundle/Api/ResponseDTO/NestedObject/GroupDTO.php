<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * グループ情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $groupId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupName;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupType;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $achievementRate;

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return GroupDTO
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
     * Set groupName
     *
     * @param string $groupName
     *
     * @return GroupDTO
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
     * Set groupType
     *
     * @param string $groupType
     *
     * @return GroupDTO
     */
    public function setGroupType($groupType)
    {
        $this->groupType = $groupType;

        return $this;
    }

    /**
     * Get groupType
     *
     * @return string
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * Set achievementRate
     *
     * @param string $achievementRate
     *
     * @return GroupDTO
     */
    public function setAchievementRate($achievementRate)
    {
        $this->achievementRate = $achievementRate;

        return $this;
    }

    /**
     * Get achievementRate
     *
     * @return string
     */
    public function getAchievementRate()
    {
        return $this->achievementRate;
    }
}
