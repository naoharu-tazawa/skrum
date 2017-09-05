<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TUpload
 *
 * @ORM\Table(name="t_upload", indexes={@ORM\Index(name="idx_upload_01", columns={"upload_control_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TUploadRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TUpload
{
    /**
     * @var integer
     *
     * @ORM\Column(name="line_number", type="integer", nullable=false)
     */
    private $lineNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="line_data", type="string", length=255, nullable=true)
     */
    private $lineData;

    /**
     * @var string
     *
     * @ORM\Column(name="batch_execution_status", type="string", length=2, nullable=true)
     */
    private $batchExecutionStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="result", type="boolean", nullable=true)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="temporary_password", type="string", length=255, nullable=true)
     */
    private $temporaryPassword;

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
     * @var \AppBundle\Entity\TUploadControl
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TUploadControl")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="upload_control_id", referencedColumnName="id")
     * })
     */
    private $uploadControl;



    /**
     * Set lineNumber
     *
     * @param integer $lineNumber
     *
     * @return TUpload
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    /**
     * Get lineNumber
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Set lineData
     *
     * @param string $lineData
     *
     * @return TUpload
     */
    public function setLineData($lineData)
    {
        $this->lineData = $lineData;

        return $this;
    }

    /**
     * Get lineData
     *
     * @return string
     */
    public function getLineData()
    {
        return $this->lineData;
    }

    /**
     * Set batchExecutionStatus
     *
     * @param string $batchExecutionStatus
     *
     * @return TUpload
     */
    public function setBatchExecutionStatus($batchExecutionStatus)
    {
        $this->batchExecutionStatus = $batchExecutionStatus;

        return $this;
    }

    /**
     * Get batchExecutionStatus
     *
     * @return string
     */
    public function getBatchExecutionStatus()
    {
        return $this->batchExecutionStatus;
    }

    /**
     * Set result
     *
     * @param boolean $result
     *
     * @return TUpload
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return TUpload
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set temporaryPassword
     *
     * @param string $temporaryPassword
     *
     * @return TUpload
     */
    public function setTemporaryPassword($temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;

        return $this;
    }

    /**
     * Get temporaryPassword
     *
     * @return string
     */
    public function getTemporaryPassword()
    {
        return $this->temporaryPassword;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TUpload
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
     * @return TUpload
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
     * @return TUpload
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

    /**
     * Set uploadControl
     *
     * @param \AppBundle\Entity\TUploadControl $uploadControl
     *
     * @return TUpload
     */
    public function setUploadControl(\AppBundle\Entity\TUploadControl $uploadControl = null)
    {
        $this->uploadControl = $uploadControl;

        return $this;
    }

    /**
     * Get uploadControl
     *
     * @return \AppBundle\Entity\TUploadControl
     */
    public function getUploadControl()
    {
        return $this->uploadControl;
    }
}
