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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $imageVersion;

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
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $lastUpdate;

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
     * Set imageVersion
     *
     * @param integer $imageVersion
     *
     * @return BasicCompanyInfoDTO
     */
    public function setImageVersion($imageVersion)
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    /**
     * Get imageVersion
     *
     * @return integer
     */
    public function getImageVersion()
    {
        return $this->imageVersion;
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
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return BasicCompanyInfoDTO
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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
