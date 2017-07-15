<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 会社基本情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class BasicCompanyInfoDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $companyId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $vision;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $mission;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\PolicyDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\PolicyDTO")
     */
    private $policy;

    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return BasicCompanyInfoDTO
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BasicCompanyInfoDTO
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
     * Set vision
     *
     * @param string $vision
     *
     * @return BasicCompanyInfoDTO
     */
    public function setVision($vision)
    {
        $this->vision = $vision;

        return $this;
    }

    /**
     * Get vision
     *
     * @return string
     */
    public function getVision()
    {
        return $this->vision;
    }

    /**
     * Set mission
     *
     * @param string $mission
     *
     * @return BasicCompanyInfoDTO
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set policy
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\PolicyDTO $policy
     *
     * @return BasicCompanyInfoDTO
     */
    public function setPolicy($policy)
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * Get policy
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\PolicyDTO
     */
    public function getPolicy()
    {
        return $this->policy;
    }
}
