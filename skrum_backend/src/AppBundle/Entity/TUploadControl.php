<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TUploadControl
 *
 * @ORM\Table(name="t_upload_control", indexes={@ORM\Index(name="idx_upload_control_01", columns={"company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TUploadControlRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TUploadControl
{
    /**
     * @var integer
     *
     * @ORM\Column(name="company_id", type="integer", nullable=false)
     */
    private $companyId;

    /**
     * @var string
     *
     * @ORM\Column(name="upload_type", type="string", length=2, nullable=false)
     */
    private $uploadType;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="upload_user_id", type="integer", nullable=false)
     */
    private $uploadUserId;

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
     * Set companyId
     *
     * @param integer $companyId
     *
     * @return TUploadControl
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
     * Set uploadType
     *
     * @param string $uploadType
     *
     * @return TUploadControl
     */
    public function setUploadType($uploadType)
    {
        $this->uploadType = $uploadType;

        return $this;
    }

    /**
     * Get uploadType
     *
     * @return string
     */
    public function getUploadType()
    {
        return $this->uploadType;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return TUploadControl
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set uploadUserId
     *
     * @param integer $uploadUserId
     *
     * @return TUploadControl
     */
    public function setUploadUserId($uploadUserId)
    {
        $this->uploadUserId = $uploadUserId;

        return $this;
    }

    /**
     * Get uploadUserId
     *
     * @return integer
     */
    public function getUploadUserId()
    {
        return $this->uploadUserId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TUploadControl
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
     * @return TUploadControl
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
     * @return TUploadControl
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
