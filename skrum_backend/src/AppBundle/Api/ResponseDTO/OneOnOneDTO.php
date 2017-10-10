<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 1on1情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OneOnOneDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $oneOnOneId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $oneOnOneType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $senderUserId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $fromName;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $fromToNames;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $imageVersion;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $toNames;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $targetDate;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $dueDate;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $feedbackType;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $intervieweeUserName;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $lastUpdate;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $partOfText;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $text;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $readFlg;

    /**
     * Set oneOnOneId
     *
     * @param integer $oneOnOneId
     *
     * @return OneOnOneDTO
     */
    public function setOneOnOneId($oneOnOneId)
    {
        $this->oneOnOneId = $oneOnOneId;

        return $this;
    }

    /**
     * Get oneOnOneId
     *
     * @return integer
     */
    public function getOneOnOneId()
    {
        return $this->oneOnOneId;
    }

    /**
     * Set oneOnOneType
     *
     * @param string $oneOnOneType
     *
     * @return OneOnOneDTO
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
     * @return OneOnOneDTO
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
     * Set fromName
     *
     * @param string $fromName
     *
     * @return OneOnOneDTO
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get fromName
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set fromToNames
     *
     * @param string $fromToNames
     *
     * @return OneOnOneDTO
     */
    public function setFromToNames($fromToNames)
    {
        $this->fromToNames = $fromToNames;

        return $this;
    }

    /**
     * Get fromToNames
     *
     * @return string
     */
    public function getFromToNames()
    {
        return $this->fromToNames;
    }

    /**
     * Set imageVersion
     *
     * @param integer $imageVersion
     *
     * @return OneOnOneDTO
     */
    public function setImageVersion($imageVersion)
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    /**
     * Get imageVersion
     *
     * @return integer
     */
    public function getImageVersion()
    {
        return $this->imageVersion;
    }

    /**
     * Set toNames
     *
     * @param string $toNames
     *
     * @return OneOnOneDTO
     */
    public function setToNames($toNames)
    {
        $this->toNames = $toNames;

        return $this;
    }

    /**
     * Get toNames
     *
     * @return string
     */
    public function getToNames()
    {
        return $this->toNames;
    }

    /**
     * Set targetDate
     *
     * @param \DateTime $targetDate
     *
     * @return OneOnOneDTO
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
     * @return OneOnOneDTO
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
     * @return OneOnOneDTO
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
     * Set intervieweeUserName
     *
     * @param string $intervieweeUserName
     *
     * @return OneOnOneDTO
     */
    public function setIntervieweeUserName($intervieweeUserName)
    {
        $this->intervieweeUserName = $intervieweeUserName;

        return $this;
    }

    /**
     * Get intervieweeUserName
     *
     * @return string
     */
    public function getIntervieweeUserName()
    {
        return $this->intervieweeUserName;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return OneOnOneDTO
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set partOfText
     *
     * @param string $partOfText
     *
     * @return OneOnOneDTO
     */
    public function setPartOfText($partOfText)
    {
        $this->partOfText = $partOfText;

        return $this;
    }

    /**
     * Get partOfText
     *
     * @return string
     */
    public function getPartOfText()
    {
        return $this->partOfText;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return OneOnOneDTO
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get readFlg
     *
     * @return integer
     */
    public function getReadFlg()
    {
        return $this->readFlg;
    }

    /**
     * Set readFlg
     *
     * @param string $readFlg
     *
     * @return OneOnOneDTO
     */
    public function setReadFlg($readFlg)
    {
        $this->readFlg = $readFlg;

        return $this;
    }
}
