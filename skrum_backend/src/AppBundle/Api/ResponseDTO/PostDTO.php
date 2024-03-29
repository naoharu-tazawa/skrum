<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 投稿情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class PostDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $postId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $posterType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterUserId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $posterUserName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterUserImageVersion;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterUserRoleLevel;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterGroupId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $posterGroupName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterGroupImageVersion;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $posterGroupType;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterCompanyId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $posterCompanyName;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterCompanyImageVersion;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $post;

    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $postedDatetime;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\AutoShareDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\AutoShareDTO")
     */
    private $autoShare;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $likesCount;

    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $likedFlg;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $disclosureType;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $replies;

    /**
     * Set postId
     *
     * @param integer $postId
     *
     * @return PostDTO
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set posterType
     *
     * @param string $posterType
     *
     * @return PostDTO
     */
    public function setPosterType($posterType)
    {
        $this->posterType = $posterType;

        return $this;
    }

    /**
     * Get posterType
     *
     * @return string
     */
    public function getPosterType()
    {
        return $this->posterType;
    }

    /**
     * Set posterUserId
     *
     * @param integer $posterUserId
     *
     * @return PostDTO
     */
    public function setPosterUserId($posterUserId)
    {
        $this->posterUserId = $posterUserId;

        return $this;
    }

    /**
     * Get posterUserId
     *
     * @return integer
     */
    public function getPosterUserId()
    {
        return $this->posterUserId;
    }

    /**
     * Set posterUserName
     *
     * @param string $posterUserName
     *
     * @return PostDTO
     */
    public function setPosterUserName($posterUserName)
    {
        $this->posterUserName = $posterUserName;

        return $this;
    }

    /**
     * Get posterUserName
     *
     * @return string
     */
    public function getPosterUserName()
    {
        return $this->posterUserName;
    }

    /**
     * Set posterUserImageVersion
     *
     * @param integer $posterUserImageVersion
     *
     * @return PostDTO
     */
    public function setPosterUserImageVersion($posterUserImageVersion)
    {
        $this->posterUserImageVersion = $posterUserImageVersion;

        return $this;
    }

    /**
     * Get posterUserImageVersion
     *
     * @return integer
     */
    public function getPosterUserImageVersion()
    {
        return $this->posterUserImageVersion;
    }

    /**
     * Set posterUserRoleLevel
     *
     * @param integer $posterUserRoleLevel
     *
     * @return PostDTO
     */
    public function setPosterUserRoleLevel($posterUserRoleLevel)
    {
        $this->posterUserRoleLevel = $posterUserRoleLevel;

        return $this;
    }

    /**
     * Get posterUserRoleLevel
     *
     * @return integer
     */
    public function getPosterUserRoleLevel()
    {
        return $this->posterUserRoleLevel;
    }

    /**
     * Set posterGroupId
     *
     * @param integer $posterGroupId
     *
     * @return PostDTO
     */
    public function setPosterGroupId($posterGroupId)
    {
        $this->posterGroupId = $posterGroupId;

        return $this;
    }

    /**
     * Get posterGroupId
     *
     * @return integer
     */
    public function getPosterGroupId()
    {
        return $this->posterGroupId;
    }

    /**
     * Set posterGroupName
     *
     * @param string $posterGroupName
     *
     * @return PostDTO
     */
    public function setPosterGroupName($posterGroupName)
    {
        $this->posterGroupName = $posterGroupName;

        return $this;
    }

    /**
     * Get posterGroupName
     *
     * @return string
     */
    public function getPosterGroupName()
    {
        return $this->posterGroupName;
    }

    /**
     * Set posterGroupImageVersion
     *
     * @param integer $posterGroupImageVersion
     *
     * @return PostDTO
     */
    public function setPosterGroupImageVersion($posterGroupImageVersion)
    {
        $this->posterGroupImageVersion = $posterGroupImageVersion;

        return $this;
    }

    /**
     * Get posterGroupImageVersion
     *
     * @return integer
     */
    public function getPosterGroupImageVersion()
    {
        return $this->posterGroupImageVersion;
    }

    /**
     * Set posterGroupType
     *
     * @param string $posterGroupType
     *
     * @return PostDTO
     */
    public function setPosterGroupType($posterGroupType)
    {
        $this->posterGroupType = $posterGroupType;

        return $this;
    }

    /**
     * Get posterGroupType
     *
     * @return string
     */
    public function getPosterGroupType()
    {
        return $this->posterGroupType;
    }

    /**
     * Set posterCompanyId
     *
     * @param integer $posterCompanyId
     *
     * @return PostDTO
     */
    public function setPosterCompanyId($posterCompanyId)
    {
        $this->posterCompanyId = $posterCompanyId;

        return $this;
    }

    /**
     * Get posterCompanyId
     *
     * @return integer
     */
    public function getPosterCompanyId()
    {
        return $this->posterCompanyId;
    }

    /**
     * Set posterCompanyName
     *
     * @param string $posterCompanyName
     *
     * @return PostDTO
     */
    public function setPosterCompanyName($posterCompanyName)
    {
        $this->posterCompanyName = $posterCompanyName;

        return $this;
    }

    /**
     * Get posterGroupName
     *
     * @return string
     */
    public function getPosterCompanyName()
    {
        return $this->posterCompanyName;
    }

    /**
     * Set posterCompanyImageVersion
     *
     * @param integer $posterCompanyImageVersion
     *
     * @return PostDTO
     */
    public function setPosterCompanyImageVersion($posterCompanyImageVersion)
    {
        $this->posterCompanyImageVersion = $posterCompanyImageVersion;

        return $this;
    }

    /**
     * Get posterCompanyImageVersion
     *
     * @return integer
     */
    public function getPosterCompanyImageVersion()
    {
        return $this->posterCompanyImageVersion;
    }

    /**
     * Set post
     *
     * @param string $post
     *
     * @return PostDTO
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set postedDatetime
     *
     * @param \DateTime $postedDatetime
     *
     * @return PostDTO
     */
    public function setPostedDatetime($postedDatetime)
    {
        $this->postedDatetime = $postedDatetime;

        return $this;
    }

    /**
     * Get postedDatetime
     *
     * @return \DateTime
     */
    public function getPostedDatetime()
    {
        return $this->postedDatetime;
    }

    /**
     * Set autoShare
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\AutoShareDTO $autoShare
     *
     * @return PostDTO
     */
    public function setAutoShare($autoShare)
    {
        $this->autoShare = $autoShare;

        return $this;
    }

    /**
     * Get autoShare
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\AutoShareDTO
     */
    public function getAutoShare()
    {
        return $this->autoShare;
    }

    /**
     * Set likesCount
     *
     * @param integer $likesCount
     *
     * @return PostDTO
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;

        return $this;
    }

    /**
     * Get likesCount
     *
     * @return integer
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * Set likedFlg
     *
     * @param integer $likedFlg
     *
     * @return PostDTO
     */
    public function setLikedFlg($likedFlg)
    {
        $this->likedFlg = $likedFlg;

        return $this;
    }

    /**
     * Get likedFlg
     *
     * @return integer
     */
    public function getLikedFlg()
    {
        return $this->likedFlg;
    }

    /**
     * Set disclosureType
     *
     * @param string $disclosureType
     *
     * @return PostDTO
     */
    public function setDisclosureType($disclosureType)
    {
        $this->disclosureType = $disclosureType;

        return $this;
    }

    /**
     * Get disclosureType
     *
     * @return string
     */
    public function getDisclosureType()
    {
        return $this->disclosureType;
    }

    /**
     * Set replies
     *
     * @param array $replies
     *
     * @return PostDTO
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;

        return $this;
    }

    /**
     * Get replies
     *
     * @return array
     */
    public function getReplies()
    {
        return $this->replies;
    }
}
