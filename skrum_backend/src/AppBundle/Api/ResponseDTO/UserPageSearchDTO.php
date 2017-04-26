<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ユーザ検索（ページング）DTO

 * @JSON\ExclusionPolicy("none")
 */
class UserPageSearchDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $count;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $results;

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return UserPageSearchDTO
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set results
     *
     * @param array $results
     *
     * @return UserPageSearchDTO
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }
}
