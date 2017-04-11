<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TOkr;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\AlignmentsInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\UserAlignmentsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\CompanyAlignmentsDTO;

/**
 * OKRマップサービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrMapService extends BaseService
{
    /**
     * 目標一覧を取得
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getUserObjectives($auth, $userId, $timeframeId, $companyId)
    {
        // ユーザ目標を取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getUserObjectives($userId, $timeframeId, $companyId);

        // ユーザ目標をDTOに詰め替える
        $okrDisclosureLogic = $this->getOkrDisclosureLogic();
        $basicOkrDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            // 閲覧権限をチェック
            if (!$okrDisclosureLogic->checkDisclosure($auth->getUserId(), $auth->getRoleLevel(), $tOkr)) {
                continue;
            }

            $basicOkrDTO = new BasicOkrDTO();
            $basicOkrDTO->setOkrId($tOkr->getOkrId());
            $basicOkrDTO->setOkrName($tOkr->getName());
            $basicOkrDTO->setTargetValue($tOkr->getTargetValue());
            $basicOkrDTO->setAchievedValue($tOkr->getAchievedValue());
            $basicOkrDTO->setAchievementRate($tOkr->getAchievementRate());
            $basicOkrDTO->setUnit($tOkr->getUnit());
            $basicOkrDTO->setOwnerType($tOkr->getOwnerType());
            $basicOkrDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
            $basicOkrDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
            $basicOkrDTO->setStatus($tOkr->getStatus());

            $basicOkrDTOArray[] = $basicOkrDTO;
        }

        return $basicOkrDTOArray;
    }
}
