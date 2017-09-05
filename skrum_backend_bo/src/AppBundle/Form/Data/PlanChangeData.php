<?php

namespace AppBundle\Form\Data;

use Symfony\Component\Validator\Constraints AS Assert;

/**
 * PlanChangeフォームデータクラス
 *
 * @author naoharu.tazawa
 */
class PlanChangeData
{
    /**
     * @var integer
     *
     * @Assert\NotBlank(message="Please enter a specialityValue")
     */
    private $planId;

    /**
     * Set planId
     *
     * @param integer $planId
     *
     * @return PlanChangeData
     */
    public function setPlanId($planId)
    {
        $this->planId = $planId;

        return $this;
    }

    /**
     * Get planId
     *
     * @return integer
     */
    public function getPlanId()
    {
        return $this->planId;
    }
}
