<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 契約情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class ContractDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $planId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $planName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $userCount;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $priceType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $price;

    /**
     * Set planId
     *
     * @param integer $planId
     *
     * @return ContractDTO
     */
    public function setPlanId($planId)
    {
        $this->planId = $planId;

        return $this;
    }

    /**
     * Get planId
     *
     * @return integer
     */
    public function getPlanId()
    {
        return $this->planId;
    }

    /**
     * Set planName
     *
     * @param string $planName
     *
     * @return ContractDTO
     */
    public function setPlanName($planName)
    {
        $this->planName = $planName;

        return $this;
    }

    /**
     * Get planName
     *
     * @return string
     */
    public function getPlanName()
    {
        return $this->planName;
    }

    /**
     * Set userCount
     *
     * @param integer $userCount
     *
     * @return ContractDTO
     */
    public function setUserCount($userCount)
    {
        $this->userCount = $userCount;

        return $this;
    }

    /**
     * Get userCount
     *
     * @return integer
     */
    public function getUserCount()
    {
        return $this->userCount;
    }

    /**
     * Set priceType
     *
     * @param string $priceType
     *
     * @return ContractDTO
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return ContractDTO
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
}
