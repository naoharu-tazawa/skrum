<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * グループパス要素DTO

 * @JSON\ExclusionPolicy("none")
 */
class GroupPathElementDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $name;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return GroupPathElementDTO
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return GroupPathElementDTO
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
}
