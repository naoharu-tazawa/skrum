<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * グループパスDTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupPathDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $groupTreeId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupPath;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupPathName;

    /**
     * Set groupTreeId
     *
     * @param integer $groupTreeId
     *
     * @return GroupPathDTO
     */
    public function setGroupTreeId($groupTreeId)
    {
        $this->groupTreeId = $groupTreeId;

        return $this;
    }

    /**
     * Get groupTreeId
     *
     * @return integer
     */
    public function getGroupTreeId()
    {
        return $this->groupTreeId;
    }

    /**
     * Set groupPath
     *
     * @param string groupPath
     *
     * @return GroupPathDTO
     */
    public function setGroupPath($groupPath)
    {
        $this->groupPath = $groupPath;

        return $this;
    }

    /**
     * Get groupPath
     *
     * @return string
     */
    public function getGroupPath()
    {
        return $this->groupPath;
    }

    /**
     * Set groupPathName
     *
     * @param string $groupPathName
     *
     * @return GroupPathDTO
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
