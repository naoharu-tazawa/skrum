<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 目標紐付け先（グループ）情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupAlignmentsDTO
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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $numberOfOkrs;

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return GroupAlignmentsDTO
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
     * @return GroupAlignmentsDTO
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
     * Set numberOfOkrs
     *
     * @param integer $numberOfOkrs
     *
     * @return GroupAlignmentsDTO
     */
    public function setNumberOfOkrs($numberOfOkrs)
    {
        $this->numberOfOkrs = $numberOfOkrs;

        return $this;
    }

    /**
     * Get numberOfOkrs
     *
     * @return integer
     */
    public function getNumberOfOkrs()
    {
        return $this->numberOfOkrs;
    }
}
