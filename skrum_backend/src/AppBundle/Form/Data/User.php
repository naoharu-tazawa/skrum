<?php

namespace AppBundle\Form\Data;

use Symfony\Component\Validator\Constraints AS Assert;

/**
 * Userフォームデータクラス
 *（このクラスはサンプルです）
 */
class User
{
    /**
     * @var integer
     *
     * @Assert\NotBlank
     * @Assert\Length(max=2)
     */
    private $userId;


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
     * @param string $userId
     *
     * @return User
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }
}
