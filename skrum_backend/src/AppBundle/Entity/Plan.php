<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plan
 *
 * @ORM\Table(name="PLAN")
 * @ORM\Entity
 */
class Plan
{
    /**
     * @var string
     *
     * @ORM\Column(name="PLAN_NAME", type="string", length=45, nullable=false)
     */
    private $planName;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRICE_M", type="integer", nullable=false)
     */
    private $priceM;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRICE_C", type="integer", nullable=false)
     */
    private $priceC;

    /**
     * @var integer
     *
     * @ORM\Column(name="ACTIVE_FLG", type="integer", nullable=false)
     */
    private $activeFlg = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="DELETED_FLG", type="integer", nullable=false)
     */
    private $deletedFlg = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="BASE_FLG", type="integer", nullable=true)
     */
    private $baseFlg;

    /**
     * @var integer
     *
     * @ORM\Column(name="PLAN_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $planId;



    /**
     * Set planName
     *
     * @param string $planName
     *
     * @return Plan
     */
    public function setPlanName($planName)
    {
        $this->planName = $planName;

        return $this;
    }

    /**
     * Get planName
     *
     * @return string
     */
    public function getPlanName()
    {
        return $this->planName;
    }

    /**
     * Set priceM
     *
     * @param integer $priceM
     *
     * @return Plan
     */
    public function setPriceM($priceM)
    {
        $this->priceM = $priceM;

        return $this;
    }

    /**
     * Get priceM
     *
     * @return integer
     */
    public function getPriceM()
    {
        return $this->priceM;
    }

    /**
     * Set priceC
     *
     * @param integer $priceC
     *
     * @return Plan
     */
    public function setPriceC($priceC)
    {
        $this->priceC = $priceC;

        return $this;
    }

    /**
     * Get priceC
     *
     * @return integer
     */
    public function getPriceC()
    {
        return $this->priceC;
    }

    /**
     * Set activeFlg
     *
     * @param integer $activeFlg
     *
     * @return Plan
     */
    public function setActiveFlg($activeFlg)
    {
        $this->activeFlg = $activeFlg;

        return $this;
    }

    /**
     * Get activeFlg
     *
     * @return integer
     */
    public function getActiveFlg()
    {
        return $this->activeFlg;
    }

    /**
     * Set deletedFlg
     *
     * @param integer $deletedFlg
     *
     * @return Plan
     */
    public function setDeletedFlg($deletedFlg)
    {
        $this->deletedFlg = $deletedFlg;

        return $this;
    }

    /**
     * Get deletedFlg
     *
     * @return integer
     */
    public function getDeletedFlg()
    {
        return $this->deletedFlg;
    }

    /**
     * Set baseFlg
     *
     * @param integer $baseFlg
     *
     * @return Plan
     */
    public function setBaseFlg($baseFlg)
    {
        $this->baseFlg = $baseFlg;

        return $this;
    }

    /**
     * Get baseFlg
     *
     * @return integer
     */
    public function getBaseFlg()
    {
        return $this->baseFlg;
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
