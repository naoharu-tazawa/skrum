<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TPreUser
 *
 * @ORM\Table(name="t_pre_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TPreUserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TPreUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="urltoken", type="string", length=128, nullable=false)
     */
    private $urltoken;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=255, nullable=false)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", length=45, nullable=false)
     */
    private $subdomain;

    /**
     * @var integer
     *
     * @ORM\Column(name="company_id", type="integer", nullable=true)
     */
    private $companyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="role_assignment_id", type="integer", nullable=true)
     */
    private $roleAssignmentId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="initial_user_flg", type="boolean", nullable=false)
     */
    private $initialUserFlg = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="invalid_flg", type="boolean", nullable=false)
     */
    private $invalidFlg = '0';

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set urltoken
     *
     * @param string $urltoken
     *
     * @return TPreUser
     */
    public function setUrltoken($urltoken)
    {
        $this->urltoken = $urltoken;

        return $this;
    }

    /**
     * Get urltoken
     *
     * @return string
     */
    public function getUrltoken()
    {
        return $this->urltoken;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return TPreUser
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set subdomain
     *
     * @param string $subdomain
     *
     * @return TPreUser
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return TPreUser
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
     * Set roleAssignmentId
     *
     * @param integer $roleAssignmentId
     *
     * @return TPreUser
     */
    public function setRoleAssignmentId($roleAssignmentId)
    {
        $this->roleAssignmentId = $roleAssignmentId;

        return $this;
    }

    /**
     * Get roleAssignmentId
     *
     * @return integer
     */
    public function getRoleAssignmentId()
    {
        return $this->roleAssignmentId;
    }

    /**
     * Set initialUserFlg
     *
     * @param boolean $initialUserFlg
     *
     * @return TPreUser
     */
    public function setInitialUserFlg($initialUserFlg)
    {
        $this->initialUserFlg = $initialUserFlg;

        return $this;
    }

    /**
     * Get initialUserFlg
     *
     * @return boolean
     */
    public function getInitialUserFlg()
    {
        return $this->initialUserFlg;
    }

    /**
     * Set invalidFlg
     *
     * @param boolean $invalidFlg
     *
     * @return TPreUser
     */
    public function setInvalidFlg($invalidFlg)
    {
        $this->invalidFlg = $invalidFlg;

        return $this;
    }

    /**
     * Get invalidFlg
     *
     * @return boolean
     */
    public function getInvalidFlg()
    {
        return $this->invalidFlg;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TPreUser
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
     * @return TPreUser
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
     * @return TPreUser
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
