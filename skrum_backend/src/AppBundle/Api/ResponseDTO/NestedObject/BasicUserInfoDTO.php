<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * ユーザ基本情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class BasicUserInfoDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $name;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $departments;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $position;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $emailAddress;

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return BasicUserInfoDTO
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BasicUserInfoDTO
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
     * Set departments
     *
     * @param array $departments
     *
     * @return BasicUserInfoDTO
     */
    public function setDepartments($departments)
    {
        $this->departments = $departments;

        return $this;
    }

    /**
     * Get departments
     *
     * @return array
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return BasicUserInfoDTO
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return BasicUserInfoDTO
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return BasicUserInfoDTO
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
}
