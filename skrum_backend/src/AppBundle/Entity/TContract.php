<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * TContract
 *
 * @ORM\Table(name="t_contract", indexes={@ORM\Index(name="idx_contract_01", columns={"company_id"}), @ORM\Index(name="idx_contract_02", columns={"plan_id"}), @ORM\Index(name="idx_contract_03", columns={"plan_start_date"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TContractRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class TContract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="company_id", type="integer", nullable=false)
     */
    private $companyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="plan_id", type="integer", nullable=false)
     */
    private $planId;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="price_type", type="string", length=2, nullable=true)
     */
    private $priceType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plan_start_date", type="date", nullable=true)
     */
    private $planStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="plan_end_date", type="date", nullable=true)
     */
    private $planEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="contract_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $contractId;



    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return TContract
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set planId
     *
     * @param integer $planId
     *
     * @return TContract
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

    /**
     * Set price
     *
     * @param string $price
     *
     * @return TContract
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceType
     *
     * @param string $priceType
     *
     * @return TContract
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
    }

    /**
     * Set planStartDate
     *
     * @param \DateTime $planStartDate
     *
     * @return TContract
     */
    public function setPlanStartDate($planStartDate)
    {
        $this->planStartDate = $planStartDate;

        return $this;
    }

    /**
     * Get planStartDate
     *
     * @return \DateTime
     */
    public function getPlanStartDate()
    {
        return $this->planStartDate;
    }

    /**
     * Set planEndDate
     *
     * @param \DateTime $planEndDate
     *
     * @return TContract
     */
    public function setPlanEndDate($planEndDate)
    {
        $this->planEndDate = $planEndDate;

        return $this;
    }

    /**
     * Get planEndDate
     *
     * @return \DateTime
     */
    public function getPlanEndDate()
    {
        return $this->planEndDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TContract
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TContract
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return TContract
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Get contractId
     *
     * @return integer
     */
    public function getContractId()
    {
        return $this->contractId;
    }
}
