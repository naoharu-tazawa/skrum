<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 1on1ヘッダー情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OneOnOneHeaderDTO
{
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
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $interviewDate;

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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $okrName;

    /**
     * Set targetDate
     *
     * @param \DateTime $targetDate
     *
     * @return OneOnOneHeaderDTO
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
     * @return OneOnOneHeaderDTO
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
     * Set interviewDate
     *
     * @param \DateTime $interviewDate
     *
     * @return OneOnOneHeaderDTO
     */
    public function setInterviewDate($interviewDate)
    {
        $this->interviewDate = $interviewDate;

        return $this;
    }

    /**
     * Get interviewDate
     *
     * @return \DateTime
     */
    public function getInterviewDate()
    {
        return $this->interviewDate;
    }

    /**
     * Set feedbackType
     *
     * @param string $feedbackType
     *
     * @return OneOnOneHeaderDTO
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
     * @return OneOnOneHeaderDTO
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
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return OneOnOneHeaderDTO
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
     * Set okrName
     *
     * @param string $okrName
     *
     * @return OneOnOneHeaderDTO
     */
    public function setOkrName($okrName)
    {
        $this->okrName = $okrName;

        return $this;
    }

    /**
     * Get okrName
     *
     * @return string
     */
    public function getOkrName()
    {
        return $this->okrName;
    }
}
