<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 会社目標管理情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class CompanyBasicsDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO")
     */
    private $company;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $okrs;

    /**
     * Set company
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO $company
     *
     * @return CompanyBasicsDTO
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set okrs
     *
     * @param array $okrs
     *
     * @return CompanyBasicsDTO
     */
    public function setOkrs($okrs)
    {
        $this->okrs = $okrs;

        return $this;
    }

    /**
     * Get okrs
     *
     * @return array
     */
    public function getOkrs()
    {
        return $this->okrs;
    }
}
