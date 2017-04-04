<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * MRoleAssignment
 *
 * @ORM\Table(name="m_role_assignment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MRoleAssignmentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class MRoleAssignment
{
    /**
     * @var string
     *
     * @ORM\Column(name="role_id", type="string", length=45, nullable=false)
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="role_level", type="string", length=45, nullable=false)
     */
    private $roleLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="company_id", type="integer", nullable=false)
     */
    private $companyId;

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
     * @ORM\Column(name="role_assignment_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $roleAssignmentId;



    /**
     * Set roleId
     *
     * @param string $roleId
     *
     * @return MRoleAssignment
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set roleLevel
     *
     * @param string $roleLevel
     *
     * @return MRoleAssignment
     */
    public function setRoleLevel($roleLevel)
    {
        $this->roleLevel = $roleLevel;

        return $this;
    }

    /**
     * Get roleLevel
     *
     * @return string
     */
    public function getRoleLevel()
    {
        return $this->roleLevel;
    }

    /**
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return MRoleAssignment
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MRoleAssignment
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
     * @return MRoleAssignment
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
     * @return MRoleAssignment
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
     * Get roleAssignmentId
     *
     * @return integer
     */
    public function getRoleAssignmentId()
    {
        return $this->roleAssignmentId;
    }
}
