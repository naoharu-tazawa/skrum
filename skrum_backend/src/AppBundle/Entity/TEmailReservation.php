<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TEmailReservation
 *
 * @ORM\Table(name="t_email_reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TEmailReservationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TEmailReservation
{
    /**
     * @var string
     *
     * @ORM\Column(name="to_email_address", type="string", length=255, nullable=false)
     */
    private $toEmailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", length=65535, nullable=false)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reception_datetime", type="datetime", nullable=false)
     */
    private $receptionDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_reservation_datetime", type="datetime", nullable=false)
     */
    private $sendingReservationDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sending_datetime", type="datetime", nullable=true)
     */
    private $sendingDatetime;

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
     * Set toEmailAddress
     *
     * @param string $toEmailAddress
     *
     * @return TEmailReservation
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
     * @return TEmailReservation
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
     * @return TEmailReservation
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
     * Set receptionDatetime
     *
     * @param \DateTime $receptionDatetime
     *
     * @return TEmailReservation
     */
    public function setReceptionDatetime($receptionDatetime)
    {
        $this->receptionDatetime = $receptionDatetime;

        return $this;
    }

    /**
     * Get receptionDatetime
     *
     * @return \DateTime
     */
    public function getReceptionDatetime()
    {
        return $this->receptionDatetime;
    }

    /**
     * Set sendingReservationDatetime
     *
     * @param \DateTime $sendingReservationDatetime
     *
     * @return TEmailReservation
     */
    public function setSendingReservationDatetime($sendingReservationDatetime)
    {
        $this->sendingReservationDatetime = $sendingReservationDatetime;

        return $this;
    }

    /**
     * Get sendingReservationDatetime
     *
     * @return \DateTime
     */
    public function getSendingReservationDatetime()
    {
        return $this->sendingReservationDatetime;
    }

    /**
     * Set sendingDatetime
     *
     * @param \DateTime $sendingDatetime
     *
     * @return TEmailReservation
     */
    public function setSendingDatetime($sendingDatetime)
    {
        $this->sendingDatetime = $sendingDatetime;

        return $this;
    }

    /**
     * Get sendingDatetime
     *
     * @return \DateTime
     */
    public function getSendingDatetime()
    {
        return $this->sendingDatetime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TEmailReservation
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
     * @return TEmailReservation
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
     * @return TEmailReservation
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
