<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ログイン後初期表示情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class TopDTO
{
    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $timeframes;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $teams;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $departments;

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
     * Set timeframes
     *
     * @param array $timeframes
     *
     * @return TopDTO
     */
    public function setTimeframes($timeframes)
    {
        $this->timeframes = $timeframes;

        return $this;
    }

    /**
     * Get timeframes
     *
     * @return array
     */
    public function getTimeframes()
    {
        return $this->timeframes;
    }

    /**
     * Set teams
     *
     * @param array $teams
     *
     * @return TopDTO
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * Get teams
     *
     * @return array
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set departments
     *
     * @param array $departments
     *
     * @return TopDTO
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
     * Set user
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicUserInfoDTO $user
     *
     * @return TopDTO
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
     * @return TopDTO
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
