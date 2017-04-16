<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Constant;
use AppBundle\Entity\TOkr;
use AppBundle\Api\ResponseDTO\ThreeGensOkrMapDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;

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

            // 会社名を取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($companyId);
            $companyName = $mCompany->getCompanyName();
        }

        // ユーザ目標をDTOに詰め替える
        $disclosureLogic = $this->getDisclosureLogic();
        $basicOkrDTOArray = array();
        foreach ($tOkrArray as $tOkr) {
            // 閲覧権限をチェック
            if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr)) {
                continue;
            }

            $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr, $companyName);

            $basicOkrDTOArray[] = $basicOkrDTO;
        }

        return $basicOkrDTOArray;
    }

    /**
     * 3世代OKRを取得
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $okrId 取得対象OKRID
     * @param integer $timeframeId タイムフレームID
     * @return \AppBundle\Api\ResponseDTO\ThreeGensOkrMapDTO
     */
    public function getThreeGensOkrMap($auth, $okrId, $timeframeId)
    {
        // 3世代OKRを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getThreeGensOkrs($okrId, $timeframeId, $auth->getCompanyId());

        // 会社名を取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());
        $companyName = $mCompany->getCompanyName();

        // DTOに詰め替える
        $disclosureLogic = $this->getDisclosureLogic();
        $threeGensOkrMapDTO = new ThreeGensOkrMapDTO();
        $childrenOkrs = array();
        foreach ($tOkrArray as $tOkr) {
            if (array_key_exists('childrenOkr', $tOkr)) {
                if (!empty($tOkr['childrenOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['childrenOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['childrenOkr'], $companyName);
                    $childrenOkrs[] = $basicOkrDTO;
                }
            } elseif (array_key_exists('parentOkr', $tOkr)) {
                if (!empty($tOkr['parentOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['parentOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['parentOkr'], $companyName);
                    $threeGensOkrMapDTO->setParentOkr($basicOkrDTO);
                }
            } else {
                if (!empty($tOkr['selectedOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['selectedOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['selectedOkr'], $companyName);
                    $threeGensOkrMapDTO->setSelectedOkr($basicOkrDTO);
                }
            }
        }
        $threeGensOkrMapDTO->setChildrenOkrs($childrenOkrs);

        return $threeGensOkrMapDTO;
    }

    /**
     * OKRエンティティをDTOに詰め替える
     *
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @param string $companyName 会社名
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO
     */
    private function repackDTOWithOkrEntity($tOkr, $companyName = null)
    {
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
            $basicOkrDTO->setOwnerCompanyName($companyName);
        }
        $basicOkrDTO->setStatus($tOkr->getStatus());

        return $basicOkrDTO;
    }
}
