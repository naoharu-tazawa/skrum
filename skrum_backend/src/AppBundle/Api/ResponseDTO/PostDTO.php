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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $posterId;

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
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $okrId;

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
     * Set posterId
     *
     * @param integer $posterId
     *
     * @return PostDTO
     */
    public function setPosterId($posterId)
    {
        $this->posterId = $posterId;

        return $this;
    }

    /**
     * Get posterId
     *
     * @return integer
     */
    public function getPosterId()
    {
        return $this->posterId;
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
     * Set okrId
     *
     * @param integer $okrId
     *
     * @return PostDTO
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
