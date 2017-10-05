<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * 1on1ダイアログ情報DTO

 * @JSON\ExclusionPolicy("none")
 */
class OneOnOneDialogDTO
{
    /**
     * @var \AppBundle\Api\ResponseDTO\NestedObject\OneOnOneHeaderDTO
     *
     * @JSON\Type("AppBundle\Api\ResponseDTO\NestedObject\OneOnOneHeaderDTO")
     */
    private $header;

    /**
     * @var array
     *
     * @JSON\Type("array")
     */
    private $dialog;

    /**
     * Set header
     *
     * @param \AppBundle\Api\ResponseDTO\NestedObject\OneOnOneHeaderDTO $header
     *
     * @return OneOnOneDialogDTO
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return \AppBundle\Api\ResponseDTO\NestedObject\OneOnOneHeaderDTO
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set dialog
     *
     * @param array $dialog
     *
     * @return OneOnOneDialogDTO
     */
    public function setDialog($dialog)
    {
        $this->dialog = $dialog;

        return $this;
    }

    /**
     * Get dialog
     *
     * @return array
     */
    public function getDialog()
    {
        return $this->dialog;
    }
}
