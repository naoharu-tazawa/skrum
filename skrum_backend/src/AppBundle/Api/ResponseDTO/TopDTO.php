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
    private $users;

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
     * Set company
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO $company
     *
     * @return TopDTO
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
     * Set users
     *
     * @param array $users
     *
     * @return TopDTO
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }
}
