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
     * @var array
     *
     * @JSON\Type("array")
     */
    private $groupPath;

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
     * @param array groupPath
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
     * @return array
     */
    public function getGroupPath()
    {
        return $this->groupPath;
    }
}
