<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * OKR詳細情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OkrDetailsDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $objective;

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
    private $keyResults;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $chart;

    /**
     * Set objective
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $objective
     *
     * @return OkrDetailsDTO
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set parentOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $parentOkr
     *
     * @return OkrDetailsDTO
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
     * Set keyResults
     *
     * @param array $keyResults
     *
     * @return OkrDetailsDTO
     */
    public function setKeyResults($keyResults)
    {
        $this->keyResults = $keyResults;

        return $this;
    }

    /**
     * Get keyResults
     *
     * @return array
     */
    public function getKeyResults()
    {
        return $this->keyResults;
    }

    /**
     * Set chart
     *
     * @param array $chart
     *
     * @return OkrDetailsDTO
     */
    public function setChart($chart)
    {
        $this->chart = $chart;

        return $this;
    }

    /**
     * Get chart
     *
     * @return array
     */
    public function getChart()
    {
        return $this->chart;
    }
}
