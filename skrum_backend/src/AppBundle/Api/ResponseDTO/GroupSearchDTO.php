<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * グループ検索DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupSearchDTO
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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $imageVersion;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupType;

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return GroupSearchDTO
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
     * @return GroupSearchDTO
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
     * Set imageVersion
     *
     * @param integer $imageVersion
     *
     * @return GroupSearchDTO
     */
    public function setImageVersion($imageVersion)
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    /**
     * Get imageVersion
     *
     * @return integer
     */
    public function getImageVersion()
    {
        return $this->imageVersion;
    }

    /**
     * Set groupType
     *
     * @param string $groupType
     *
     * @return GroupSearchDTO
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
}
