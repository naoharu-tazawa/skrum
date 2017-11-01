<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 1on1デフォルト宛先DTO

 * @JSON\ExclusionPolicy("none")
 */
class OneOnOneDefaultDestinationDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $type;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $to;

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return OneOnOneDefaultDestinationDTO
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set to
     *
     * @param array to
     *
     * @return OneOnOneDefaultDestinationDTO
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }
}
