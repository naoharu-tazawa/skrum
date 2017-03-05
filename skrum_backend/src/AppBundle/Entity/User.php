<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;

/**
 * User
 *
 * @ORM\Table(name="USER")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @JSON\ExclusionPolicy("all")
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="USER_NAME_L", type="string", length=45, nullable=false)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $userNameL;

    /**
     * @var string
     *
     * @ORM\Column(name="USER_NAME_F", type="string", length=45, nullable=true)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $userNameF;

    /**
     * @var string
     *
     * @ORM\Column(name="USER_PWD", type="string", length=45, nullable=true)
     */
    private $userPwd;

    /**
     * @var string
     *
     * @ORM\Column(name="USER_EMAIL", type="string", length=45, nullable=true)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $userEmail;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JSON\Expose()
     * @JSON\Type("integer")
     */
    private $userId;



    /**
     * Set userNameL
     *
     * @param string $userNameL
     *
     * @return User
     */
    public function setUserNameL($userNameL)
    {
        $this->userNameL = $userNameL;

        return $this;
    }

    /**
     * Get userNameL
     *
     * @return string
     */
    public function getUserNameL()
    {
        return $this->userNameL;
    }

    /**
     * Set userNameF
     *
     * @param string $userNameF
     *
     * @return User
     */
    public function setUserNameF($userNameF)
    {
        $this->userNameF = $userNameF;

        return $this;
    }

    /**
     * Get userNameF
     *
     * @return string
     */
    public function getUserNameF()
    {
        return $this->userNameF;
    }

    /**
     * Set userPwd
     *
     * @param string $userPwd
     *
     * @return User
     */
    public function setUserPwd($userPwd)
    {
        $this->userPwd = $userPwd;

        return $this;
    }

    /**
     * Get userPwd
     *
     * @return string
     */
    public function getUserPwd()
    {
        return $this->userPwd;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return User
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
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
}
