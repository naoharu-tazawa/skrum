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
    private $fromToNames;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $imageVersion;

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
}
