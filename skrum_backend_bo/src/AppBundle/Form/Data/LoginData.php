<?php

namespace AppBundle\Form\Data;

use Symfony\Component\Validator\Constraints AS Assert;

/**
 * Loginフォームデータクラス
 *
 * @author naoharu.tazawa
 */
class LoginData
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     * @Assert\Length(max=20)
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     * @Assert\Length(max=20)
     */
    private $password;

    /**
     * Set id
     *
     * @param string $id
     *
     * @return LoginData
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return LoginData
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
