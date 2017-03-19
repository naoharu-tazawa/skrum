<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * サンプルDTO

 * @JSON\ExclusionPolicy("all")
 */
class SampleDTO
{
    /**
     * @var integer
     *
     * @JSON\Expose()
     * @JSON\Type("integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $lastName;

    /**
     * @var string
     *
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $firstName;

    /**
     * @var string
     *
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $emailAddress;

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return SampleDTO
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getuserId()
    {
        return $this->userId;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return SampleDTO
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return SampleDTO
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return SampleDTO
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
