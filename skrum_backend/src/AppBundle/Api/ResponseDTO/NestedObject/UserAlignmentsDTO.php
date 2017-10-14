<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 目標紐付け先（ユーザ）情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class UserAlignmentsDTO
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserAlignmentsDTO
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
     * @return UserAlignmentsDTO
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
     * @return UserAlignmentsDTO
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
     * @return UserAlignmentsDTO
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
