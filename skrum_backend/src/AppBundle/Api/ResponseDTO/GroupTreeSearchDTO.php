<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * グループツリー検索DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupTreeSearchDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $groupPathId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupPathName;

    /**
     * Set groupPathId
     *
     * @param integer $groupPathId
     *
     * @return GroupTreeSearchDTO
     */
    public function setGroupPathId($groupPathId)
    {
        $this->groupPathId = $groupPathId;

        return $this;
    }

    /**
     * Get groupPathId
     *
     * @return integer
     */
    public function getGroupPathId()
    {
        return $this->groupPathId;
    }

    /**
     * Set groupPathName
     *
     * @param string $groupPathName
     *
     * @return GroupTreeSearchDTO
     */
    public function setGroupPathName($groupPathName)
    {
        $this->groupPathName = $groupPathName;

        return $this;
    }

    /**
     * Get groupPathName
     *
     * @return string
     */
    public function getGroupPathName()
    {
        return $this->groupPathName;
    }
}
