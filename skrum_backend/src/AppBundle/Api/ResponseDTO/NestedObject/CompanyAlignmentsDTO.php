<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 目標紐付け先（会社）情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class CompanyAlignmentsDTO
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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $numberOfOkrs;

    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return CompanyAlignmentsDTO
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
     * @return CompanyAlignmentsDTO
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
     * @return CompanyAlignmentsDTO
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
     * Set numberOfOkrs
     *
     * @param integer $numberOfOkrs
     *
     * @return CompanyAlignmentsDTO
     */
    public function setNumberOfOkrs($numberOfOkrs)
    {
        $this->numberOfOkrs = $numberOfOkrs;

        return $this;
    }

    /**
     * Get numberOfOkrs
     *
     * @return integer
     */
    public function getNumberOfOkrs()
    {
        return $this->numberOfOkrs;
    }
}
