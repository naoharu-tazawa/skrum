<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 3世代OKRマップDTO

 * @JSON\ExclusionPolicy("none")
 */
class ThreeGensOkrMapDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $selectedOkr;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $parentOkr;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $childrenOkrs;

    /**
     * Set selectedOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $selectedOkr
     *
     * @return ThreeGensOkrMapDTO
     */
    public function setSelectedOkr($selectedOkr)
    {
        $this->selectedOkr = $selectedOkr;

        return $this;
    }

    /**
     * Get selectedOkr
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getSelectedOkr()
    {
        return $this->selectedOkr;
    }

    /**
     * Set parentOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $parentOkr
     *
     * @return ThreeGensOkrMapDTO
     */
    public function setParentOkr($parentOkr)
    {
        $this->parentOkr = $parentOkr;

        return $this;
    }

    /**
     * Get parentOkr
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getParentOkr()
    {
        return $this->parentOkr;
    }

    /**
     * Set childrenOkrs
     *
     * @param array $childrenOkrs
     *
     * @return ThreeGensOkrMapDTO
     */
    public function setChildrenOkrs($childrenOkrs)
    {
        $this->childrenOkrs = $childrenOkrs;

        return $this;
    }

    /**
     * Get childrenOkrs
     *
     * @return array
     */
    public function getChildrenOkrs()
    {
        return $this->childrenOkrs;
    }
}
