<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * ポリシー情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class PolicyDTO
{
    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $defaultDisclosureType;

    /**
     * Set defaultDisclosureType
     *
     * @param string $defaultDisclosureType
     *
     * @return PolicyDTO
     */
    public function setDefaultDisclosureType($defaultDisclosureType)
    {
        $this->defaultDisclosureType = $defaultDisclosureType;

        return $this;
    }

    /**
     * Get defaultDisclosureType
     *
     * @return string
     */
    public function getDefaultDisclosureType()
    {
        return $this->defaultDisclosureType;
    }
}
