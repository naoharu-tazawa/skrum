<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 目標紐付け先情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class AlignmentsInfoDTO
{
    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $ownerType;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO")
     */
    private $user;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO")
     */
    private $group;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO")
     */
    private $company;

    /**
     * Set ownerType
     *
     * @param string $ownerType
     *
     * @return AlignmentsInfoDTO
     */
    public function setOwnerType($ownerType)
    {
        $this->ownerType = $ownerType;

        return $this;
    }

    /**
     * Get ownerType
     *
     * @return string
     */
    public function getOwnerType()
    {
        return $this->ownerType;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO $user
     *
     * @return AlignmentsInfoDTO
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO $group
     *
     * @return AlignmentsInfoDTO
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO $company
     *
     * @return AlignmentsInfoDTO
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO
     */
    public function getCompany()
    {
        return $this->company;
    }
}
