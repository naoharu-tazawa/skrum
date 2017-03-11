<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * TAuthorization
 *
 * @ORM\Table(name="t_authorization")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TAuthorizationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class TAuthorization
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
     * @var integer
     *
     * @ORM\Column(name="contract_id", type="integer", nullable=true)
     */
    private $contractId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="authorization_start_datetime", type="datetime", nullable=false)
     */
    private $authorizationStartDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="authorization_end_datetime", type="datetime", nullable=false)
     */
    private $authorizationEndDatetime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="authorization_stop_flg", type="boolean", nullable=true)
     */
    private $authorizationStopFlg;

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
     * @ORM\Column(name="authorization_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $authorizationId;



    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return TAuthorization
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
     * @return TAuthorization
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
     * Set contractId
     *
     * @param integer $contractId
     *
     * @return TAuthorization
     */
    public function setContractId($contractId)
    {
        $this->contractId = $contractId;

        return $this;
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

    /**
     * Set authorizationStartDatetime
     *
     * @param \DateTime $authorizationStartDatetime
     *
     * @return TAuthorization
     */
    public function setAuthorizationStartDatetime($authorizationStartDatetime)
    {
        $this->authorizationStartDatetime = $authorizationStartDatetime;

        return $this;
    }

    /**
     * Get authorizationStartDatetime
     *
     * @return \DateTime
     */
    public function getAuthorizationStartDatetime()
    {
        return $this->authorizationStartDatetime;
    }

    /**
     * Set authorizationEndDatetime
     *
     * @param \DateTime $authorizationEndDatetime
     *
     * @return TAuthorization
     */
    public function setAuthorizationEndDatetime($authorizationEndDatetime)
    {
        $this->authorizationEndDatetime = $authorizationEndDatetime;

        return $this;
    }

    /**
     * Get authorizationEndDatetime
     *
     * @return \DateTime
     */
    public function getAuthorizationEndDatetime()
    {
        return $this->authorizationEndDatetime;
    }

    /**
     * Set authorizationStopFlg
     *
     * @param boolean $authorizationStopFlg
     *
     * @return TAuthorization
     */
    public function setAuthorizationStopFlg($authorizationStopFlg)
    {
        $this->authorizationStopFlg = $authorizationStopFlg;

        return $this;
    }

    /**
     * Get authorizationStopFlg
     *
     * @return boolean
     */
    public function getAuthorizationStopFlg()
    {
        return $this->authorizationStopFlg;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TAuthorization
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
     * @return TAuthorization
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
     * @return TAuthorization
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
     * Get authorizationId
     *
     * @return integer
     */
    public function getAuthorizationId()
    {
        return $this->authorizationId;
    }
}
