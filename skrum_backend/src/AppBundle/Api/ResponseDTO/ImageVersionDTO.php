<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 画像バージョンDTO

 * @JSON\ExclusionPolicy("none")
 */
class ImageVersionDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $imageVersion;

    /**
     * Set imageVersion
     *
     * @param integer imageVersion
     *
     * @return ImageVersionDTO
     */
    public function setImageVersion($imageVersion)
    {
        $this->$imageVersion = $imageVersion;

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
}
