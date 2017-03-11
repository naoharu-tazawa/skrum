<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * TMailReservation
 *
 * @ORM\Table(name="t_mail_reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TMailReservationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class TMailReservation
{
    /**
     * @var string
     *
     * @ORM\Column(name="email_id", type="string", length=10, nullable=true)
     */
    private $emailId;

    /**
     * @var string
     *
     * @ORM\Column(name="to_email_address", type="string", length=45, nullable=true)
     */
    private $toEmailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=65535, nullable=true)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reception_date", type="datetime", nullable=true)
     */
    private $receptionDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_reservation_time", type="datetime", nullable=true)
     */
    private $sendingReservationTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_date", type="datetime", nullable=true)
     */
    private $sendingDate;

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
     * Set emailId
     *
     * @param string $emailId
     *
     * @return TMailReservation
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;

        return $this;
    }

    /**
     * Get emailId
     *
     * @return string
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * Set toEmailAddress
     *
     * @param string $toEmailAddress
     *
     * @return TMailReservation
     */
    public function setToEmailAddress($toEmailAddress)
    {
        $this->toEmailAddress = $toEmailAddress;

        return $this;
    }

    /**
     * Get toEmailAddress
     *
     * @return string
     */
    public function getToEmailAddress()
    {
        return $this->toEmailAddress;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TMailReservation
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return TMailReservation
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
     * Set receptionDate
     *
     * @param \DateTime $receptionDate
     *
     * @return TMailReservation
     */
    public function setReceptionDate($receptionDate)
    {
        $this->receptionDate = $receptionDate;

        return $this;
    }

    /**
     * Get receptionDate
     *
     * @return \DateTime
     */
    public function getReceptionDate()
    {
        return $this->receptionDate;
    }

    /**
     * Set sendingReservationTime
     *
     * @param \DateTime $sendingReservationTime
     *
     * @return TMailReservation
     */
    public function setSendingReservationTime($sendingReservationTime)
    {
        $this->sendingReservationTime = $sendingReservationTime;

        return $this;
    }

    /**
     * Get sendingReservationTime
     *
     * @return \DateTime
     */
    public function getSendingReservationTime()
    {
        return $this->sendingReservationTime;
    }

    /**
     * Set sendingDate
     *
     * @param \DateTime $sendingDate
     *
     * @return TMailReservation
     */
    public function setSendingDate($sendingDate)
    {
        $this->sendingDate = $sendingDate;

        return $this;
    }

    /**
     * Get sendingDate
     *
     * @return \DateTime
     */
    public function getSendingDate()
    {
        return $this->sendingDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TMailReservation
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
     * @return TMailReservation
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
     * @return TMailReservation
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
