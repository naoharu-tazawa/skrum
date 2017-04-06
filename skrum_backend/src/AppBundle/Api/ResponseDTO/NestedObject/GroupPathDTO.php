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
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupTreePath;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $groupTreePathName;

    /**
     * Set groupTreePath
     *
     * @param string $groupTreePath
     *
     * @return GroupPathDTO
     */
    public function setGroupTreePath($groupTreePath)
    {
        $this->groupTreePath = $groupTreePath;

        return $this;
    }

    /**
     * Get groupTreePath
     *
     * @return string
     */
    public function getGroupTreePath()
    {
        return $this->groupTreePath;
    }

    /**
     * Set groupTreePathName
     *
     * @param string $firstName
     *
     * @return GroupPathDTO
     */
    public function setGroupTreePathName($groupTreePathName)
    {
        $this->groupTreePathName = $groupTreePathName;

        return $this;
    }

    /**
     * Get groupTreePathName
     *
     * @return string
     */
    public function getGroupTreePathName()
    {
        return $this->groupTreePathName;
    }
}
