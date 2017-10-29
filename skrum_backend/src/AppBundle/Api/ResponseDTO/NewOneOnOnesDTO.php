<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 1on1新着履歴情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class NewOneOnOnesDTO
{
    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $unreadFlgCounts;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $data;

    /**
     * Set unreadFlgCounts
     *
     * @param array $unreadFlgCounts
     *
     * @return NewOneOnOnesDTO
     */
    public function setUnreadFlgCounts($unreadFlgCounts)
    {
        $this->unreadFlgCounts = $unreadFlgCounts;

        return $this;
    }

    /**
     * Get unreadFlgCounts
     *
     * @return array
     */
    public function getUnreadFlgCounts()
    {
        return $this->unreadFlgCounts;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return NewOneOnOnesDTO
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
