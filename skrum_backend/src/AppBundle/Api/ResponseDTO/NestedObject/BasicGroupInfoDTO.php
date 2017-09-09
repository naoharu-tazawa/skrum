<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * グループ基本情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class BasicGroupInfoDTO
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
    private $name;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $groupPaths;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $mission;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $leaderUserId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $leaderName;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $lastUpdate;

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return BasicGroupInfoDTO
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
     * Set name
     *
     * @param string $name
     *
     * @return BasicGroupInfoDTO
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set groupPaths
     *
     * @param array $groupPaths
     *
     * @return BasicGroupInfoDTO
     */
    public function setGroupPaths($groupPaths)
    {
        $this->groupPaths = $groupPaths;

        return $this;
    }

    /**
     * Get groupPaths
     *
     * @return array
     */
    public function getGroupPaths()
    {
        return $this->groupPaths;
    }

    /**
     * Set mission
     *
     * @param string $mission
     *
     * @return BasicGroupInfoDTO
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set leaderUserId
     *
     * @param string $leaderUserId
     *
     * @return BasicGroupInfoDTO
     */
    public function setLeaderUserId($leaderUserId)
    {
        $this->leaderUserId = $leaderUserId;

        return $this;
    }

    /**
     * Get leaderUserId
     *
     * @return string
     */
    public function getLeaderUserId()
    {
        return $this->leaderUserId;
    }

    /**
     * Set leaderName
     *
     * @param string $leaderName
     *
     * @return BasicGroupInfoDTO
     */
    public function setLeaderName($leaderName)
    {
        $this->leaderName = $leaderName;

        return $this;
    }

    /**
     * Get leaderName
     *
     * @return string
     */
    public function getLeaderName()
    {
        return $this->leaderName;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return BasicGroupInfoDTO
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }
}
