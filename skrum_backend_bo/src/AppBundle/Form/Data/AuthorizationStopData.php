<?php

namespace AppBundle\Form\Data;

use Symfony\Component\Validator\Constraints AS Assert;

/**
 * AuthorizationStopフォームデータクラス
 *
 * @author naoharu.tazawa
 */
class AuthorizationStopData
{
    /**
     * @var integer
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     */
    private $authorizationStopFlg;

    /**
     * Set authorizationStopFlg
     *
     * @param integer $authorizationStopFlg
     *
     * @return AuthorizationStopData
     */
    public function setAuthorizationStopFlg($authorizationStopFlg)
    {
        $this->authorizationStopFlg = $authorizationStopFlg;

        return $this;
    }

    /**
     * Get authorizationStopFlg
     *
     * @return integer
     */
    public function getAuthorizationStopFlg()
    {
        return $this->authorizationStopFlg;
    }
}
