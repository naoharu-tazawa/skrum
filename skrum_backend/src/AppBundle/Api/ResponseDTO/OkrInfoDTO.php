<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * OKR進捗登録/削除/新規登録情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OkrInfoDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $targetOkr;

    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO")
     */
    private $parentOkr;

    /**
     * Set targetOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $targetOkr
     *
     * @return OkrInfoDTO
     */
    public function setTargetOkr($targetOkr)
    {
        $this->targetOkr = $targetOkr;

        return $this;
    }

    /**
     * Get $targetOkr
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getTargetOkr()
    {
        return $this->targetOkr;
    }

    /**
     * Set parentOkr
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO $parentOkr
     *
     * @return OkrInfoDTO
     */
    public function setParentOkr($parentOkr)
    {
        $this->parentOkr = $parentOkr;

        return $this;
    }

    /**
     * Get parentOkr
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    public function getParentOkr()
    {
        return $this->parentOkr;
    }
}
