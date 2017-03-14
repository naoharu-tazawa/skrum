<?php

namespace AppBundle\Form\Data;

use Symfony\Component\Validator\Constraints AS Assert;

/**
 * Userフォームデータクラス
 *（このクラスはサンプルです）
 */
class UserData
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     * @Assert\Length(max=3)
     * @Assert\EqualTo(
     *     value = 2000
     * )
     */
    private $userId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     * @Assert\Length(max=10)
     */
    private $userName;


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
     * Set userId
     *
     * @param int $userId
     *
     * @return User
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return UserData
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }
}
