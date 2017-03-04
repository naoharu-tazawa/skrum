<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OkrStructure
 *
 * @ORM\Table(name="OKR_STRUCTURE", indexes={@ORM\Index(name="fk_OKR_STRUCTUER_OBJECTIVE_OWNER1_idx", columns={"OBJECTIVE_ID"}), @ORM\Index(name="fk_OKR_STRUCTURE_period1_idx", columns={"PERIOD_ID"})})
 * @ORM\Entity
 */
class OkrStructure
{
    /**
     * @var string
     *
     * @ORM\Column(name="OKR_NAME", type="string", length=200, nullable=true)
     */
    private $okrName;

    /**
     * @var string
     *
     * @ORM\Column(name="BASE_VALUE", type="string", length=10, nullable=false)
     */
    private $baseValue;

    /**
     * @var string
     *
     * @ORM\Column(name="ACHIEVEMENT_VALUE", type="string", length=10, nullable=true)
     */
    private $achievementValue;

    /**
     * @var string
     *
     * @ORM\Column(name="UNIT_VALUE", type="string", length=45, nullable=true)
     */
    private $unitValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="OKR_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $okrId;

    /**
     * @var \AppBundle\Entity\ObjectiveOwner
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ObjectiveOwner")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OBJECTIVE_ID", referencedColumnName="OBJECTIVE_ID")
     * })
     */
    private $objective;

    /**
     * @var \AppBundle\Entity\Period
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Period")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PERIOD_ID", referencedColumnName="PERIOD_ID")
     * })
     */
    private $period;



    /**
     * Set okrName
     *
     * @param string $okrName
     *
     * @return OkrStructure
     */
    public function setOkrName($okrName)
    {
        $this->okrName = $okrName;

        return $this;
    }

    /**
     * Get okrName
     *
     * @return string
     */
    public function getOkrName()
    {
        return $this->okrName;
    }

    /**
     * Set baseValue
     *
     * @param string $baseValue
     *
     * @return OkrStructure
     */
    public function setBaseValue($baseValue)
    {
        $this->baseValue = $baseValue;

        return $this;
    }

    /**
     * Get baseValue
     *
     * @return string
     */
    public function getBaseValue()
    {
        return $this->baseValue;
    }

    /**
     * Set achievementValue
     *
     * @param string $achievementValue
     *
     * @return OkrStructure
     */
    public function setAchievementValue($achievementValue)
    {
        $this->achievementValue = $achievementValue;

        return $this;
    }

    /**
     * Get achievementValue
     *
     * @return string
     */
    public function getAchievementValue()
    {
        return $this->achievementValue;
    }

    /**
     * Set unitValue
     *
     * @param string $unitValue
     *
     * @return OkrStructure
     */
    public function setUnitValue($unitValue)
    {
        $this->unitValue = $unitValue;

        return $this;
    }

    /**
     * Get unitValue
     *
     * @return string
     */
    public function getUnitValue()
    {
        return $this->unitValue;
    }

    /**
     * Get okrId
     *
     * @return integer
     */
    public function getOkrId()
    {
        return $this->okrId;
    }

    /**
     * Set objective
     *
     * @param \AppBundle\Entity\ObjectiveOwner $objective
     *
     * @return OkrStructure
     */
    public function setObjective(\AppBundle\Entity\ObjectiveOwner $objective = null)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return \AppBundle\Entity\ObjectiveOwner
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set period
     *
     * @param \AppBundle\Entity\Period $period
     *
     * @return OkrStructure
     */
    public function setPeriod(\AppBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \AppBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
    }
}
