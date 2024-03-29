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
    private $senderUserName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $senderUserImageVersion;

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
     * Set senderUserName
     *
     * @param string $senderUserName
     *
     * @return OneOnOneDTO
     */
    public function setSenderUserName($senderUserName)
    {
        $this->senderUserName = $senderUserName;

        return $this;
    }

    /**
     * Get senderUserName
     *
     * @return string
     */
    public function getSenderUserName()
    {
        return $this->senderUserName;
    }

    /**
     * Set senderUserImageVersion
     *
     * @param integer $senderUserImageVersion
     *
     * @return OneOnOneDTO
     */
    public function setSenderUserImageVersion($senderUserImageVersion)
    {
        $this->senderUserImageVersion = $senderUserImageVersion;

        return $this;
    }

    /**
     * Get senderUserImageVersion
     *
     * @return integer
     */
    public function getSenderUserImageVersion()
    {
        return $this->senderUserImageVersion;
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
