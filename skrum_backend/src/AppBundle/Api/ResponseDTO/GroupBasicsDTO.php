<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * グループ目標管理情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupBasicsDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO")
     */
    private $group;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $okrs;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $alignmentsInfo;

    /**
     * Set group
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO $group
     *
     * @return GroupBasicsDTO
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set okrs
     *
     * @param array $okrs
     *
     * @return GroupBasicsDTO
     */
    public function setOkrs($okrs)
    {
        $this->okrs = $okrs;

        return $this;
    }

    /**
     * Get okrs
     *
     * @return array
     */
    public function getOkrs()
    {
        return $this->okrs;
    }

    /**
     * Set alignmentsInfo
     *
     * @param array $alignmentsInfo
     *
     * @return GroupBasicsDTO
     */
    public function setAlignmentsInfo($alignmentsInfo)
    {
        $this->alignmentsInfo = $alignmentsInfo;

        return $this;
    }

    /**
     * Get alignmentsInfo
     *
     * @return array
     */
    public function getAlignmentsInfo()
    {
        return $this->alignmentsInfo;
    }
}
