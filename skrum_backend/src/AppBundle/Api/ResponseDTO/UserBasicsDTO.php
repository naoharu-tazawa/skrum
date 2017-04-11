<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ログイン後初期表示情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class UserBasicsDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO")
     */
    private $user;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $okrs;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $alignmentsInfo;

    /**
     * Set user
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO $user
     *
     * @return UserBasicsDTO
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set okrs
     *
     * @param array $okrs
     *
     * @return UserBasicsDTO
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

    /**
     * Set alignmentsInfo
     *
     * @param array $alignmentsInfo
     *
     * @return UserBasicsDTO
     */
    public function setAlignmentsInfo($alignmentsInfo)
    {
        $this->alignmentsInfo = $alignmentsInfo;

        return $this;
    }

    /**
     * Get alignmentsInfo
     *
     * @return array
     */
    public function getAlignmentsInfo()
    {
        return $this->alignmentsInfo;
    }
}
