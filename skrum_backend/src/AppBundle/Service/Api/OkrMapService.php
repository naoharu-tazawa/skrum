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
     * @param string $subjectType 主体種別
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getObjectives($subjectType, $auth, $userId, $groupId, $timeframeId, $companyId)
    {
        // 目標を取得
        $tOkrRepos = $this->getTOkrRepository();
        if ($subjectType == Constant::SUBJECT_TYPE_USER) {
            $tOkrArray = $tOkrRepos->getUserObjectives($userId, $timeframeId, $companyId);
        } elseif ($subjectType == Constant::SUBJECT_TYPE_GROUP) {
            $tOkrArray = $tOkrRepos->getGroupObjectives($groupId, $timeframeId, $companyId);
        } else {
            $tOkrArray = $tOkrRepos->getCompanyObjectives($companyId, $timeframeId);

            // 会社エンティティを取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($companyId);
        }

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
            if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
                $basicOkrDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
                $basicOkrDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
            } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                $basicOkrDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $basicOkrDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
            } else {
                $basicOkrDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
                $basicOkrDTO->setOwnerCompanyName($mCompany->getCompanyName());
            }
            $basicOkrDTO->setStatus($tOkr->getStatus());

            $basicOkrDTOArray[] = $basicOkrDTO;
        }

        return $basicOkrDTOArray;
    }
}
