<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TOneOnOne
 *
 * @ORM\Table(name="t_one_on_one", indexes={@ORM\Index(name="idx_one_on_one_01", columns={"parent_id"}), @ORM\Index(name="idx_one_on_one_02", columns={"sender_user_id"}), @ORM\Index(name="idx_one_on_one_03", columns={"new_arrival_datetime"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TOneOnOneRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TOneOnOne
{
    /**
     * @var string
     *
     * @ORM\Column(name="one_on_one_type", type="string", length=2, nullable=false)
     */
    private $oneOnOneType;

    /**
     * @var integer
     *
     * @ORM\Column(name="sender_user_id", type="integer", nullable=false)
     */
    private $senderUserId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="target_date", type="date", nullable=true)
     */
    private $targetDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback_type", type="string", length=2, nullable=true)
     */
    private $feedbackType;

    /**
     * @var integer
     *
     * @ORM\Column(name="interviewee_user_id", type="integer", nullable=true)
     */
    private $intervieweeUserId;

    /**
     * @var integer
     *
     * @ORM\Column(name="okr_id", type="integer", nullable=true)
     */
    private $okrId;

    /**
     * @var integer
     *
     * @ORM\Column(name="okr_activity_id", type="bigint", nullable=true)
     */
    private $okrActivityId;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=65535, nullable=false)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="new_arrival_datetime", type="datetime", nullable=true)
     */
    private $newArrivalDatetime;

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
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\TOneOnOne
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TOneOnOne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;



    /**
     * Set oneOnOneType
     *
     * @param string $oneOnOneType
     *
     * @return TOneOnOne
     */
    public function setOneOnOneType($oneOnOneType)
    {
        $this->oneOnOneType = $oneOnOneType;

        return $this;
    }

    /**
     * Get oneOnOneType
     *
     * @return string
     */
    public function getOneOnOneType()
    {
        return $this->oneOnOneType;
    }

    /**
     * Set senderUserId
     *
     * @param integer $senderUserId
     *
     * @return TOneOnOne
     */
    public function setSenderUserId($senderUserId)
    {
        $this->senderUserId = $senderUserId;

        return $this;
    }

    /**
     * Get senderUserId
     *
     * @return integer
     */
    public function getSenderUserId()
    {
        return $this->senderUserId;
    }

    /**
     * Set targetDate
     *
     * @param \DateTime $targetDate
     *
     * @return TOneOnOne
     */
    public function setTargetDate($targetDate)
    {
        $this->targetDate = $targetDate;

        return $this;
    }

    /**
     * Get targetDate
     *
     * @return \DateTime
     */
    public function getTargetDate()
    {
        return $this->targetDate;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return TOneOnOne
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set feedbackType
     *
     * @param string $feedbackType
     *
     * @return TOneOnOne
     */
    public function setFeedbackType($feedbackType)
    {
        $this->feedbackType = $feedbackType;

        return $this;
    }

    /**
     * Get feedbackType
     *
     * @return string
     */
    public function getFeedbackType()
    {
        return $this->feedbackType;
    }

    /**
     * Set intervieweeUserId
     *
     * @param integer $intervieweeUserId
     *
     * @return TOneOnOne
     */
    public function setIntervieweeUserId($intervieweeUserId)
    {
        $this->intervieweeUserId = $intervieweeUserId;

        return $this;
    }

    /**
     * Get intervieweeUserId
     *
     * @return integer
     */
    public function getIntervieweeUserId()
    {
        return $this->intervieweeUserId;
    }

    /**
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return TOneOnOne
     */
    public function setOkrId($okrId)
    {
        $this->okrId = $okrId;

        return $this;
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
     * Set okrActivityId
     *
     * @param integer $okrActivityId
     *
     * @return TOneOnOne
     */
    public function setOkrActivityId($okrActivityId)
    {
        $this->okrActivityId = $okrActivityId;

        return $this;
    }

    /**
     * Get okrActivityId
     *
     * @return integer
     */
    public function getOkrActivityId()
    {
        return $this->okrActivityId;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return TOneOnOne
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set newArrivalDatetime
     *
     * @param \DateTime $newArrivalDatetime
     *
     * @return TOneOnOne
     */
    public function setNewArrivalDatetime($newArrivalDatetime)
    {
        $this->newArrivalDatetime = $newArrivalDatetime;

        return $this;
    }

    /**
     * Get newArrivalDatetime
     *
     * @return \DateTime
     */
    public function getNewArrivalDatetime()
    {
        return $this->newArrivalDatetime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TOneOnOne
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
     * @return TOneOnOne
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
     * @return TOneOnOne
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
     * Set parent
     *
     * @param \AppBundle\Entity\TOneOnOne $parent
     *
     * @return TOneOnOne
     */
    public function setParent(\AppBundle\Entity\TOneOnOne $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\TOneOnOne
     */
    public function getParent()
    {
        return $this->parent;
    }
}
