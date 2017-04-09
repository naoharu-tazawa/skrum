<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\NestedObject\TimeframeDTO;
use AppBundle\Utils\DBConstant;

/**
 * タイムフレームサービスクラス
 *
 * @author naoharu.tazawa
 */
class TimeframeService extends BaseService
{
    /**
     * タイムフレーム取得
     *
     * @param string $companyId 会社ID
     * @return void
     */
    public function getTimeframes($companyId)
    {
        // タイムフレーム取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframeArray = $tTimeframeRepos->findBy(array('company' => $companyId), array('timeframeId' => 'DESC'));

        // DTOに詰め替える
        $timeframeDTOArray = array();
        foreach ($tTimeframeArray as $tTimeframe) {
            $timeframeDTO = new TimeframeDTO();
            $timeframeDTO->setTimeframeId($tTimeframe->getTimeframeId());
            $timeframeDTO->setTimeframeName($tTimeframe->getTimeframeName());
            if ($tTimeframe->getDefaultFlg() === null) {
                $timeframeDTO->setDefaultFlg(DBConstant::FLG_FALSE);
            } else {
                $timeframeDTO->setDefaultFlg($tTimeframe->getDefaultFlg());
            }
            $timeframeDTOArray[] = $timeframeDTO;
        }

        return $timeframeDTOArray;
    }
}
