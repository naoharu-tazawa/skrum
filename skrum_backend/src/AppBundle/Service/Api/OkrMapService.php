<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Api\ResponseDTO\OkrMapDTO;
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
     * @param Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getObjectives(string $subjectType, Auth $auth, int $userId = null, int $groupId = null, int $timeframeId, int $companyId): array
    {
        // 目標を取得
        $tOkrRepos = $this->getTOkrRepository();
        $companyName = null;
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
     * 目標一覧マップを取得
     *
     * @param string $subjectType 主体種別
     * @param Auth $auth 認証情報
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $timeframeId タイムフレームID
     * @param integer $companyId 会社ID
     * @return OkrMapDTO
     */
    public function getDescendantsOfMultipleParents(string $subjectType, Auth $auth, int $userId = null, int $groupId = null, int $timeframeId, int $companyId): OkrMapDTO
    {
        // 目標を取得
        $tOkrRepos = $this->getTOkrRepository();
        $companyName = null;
        if ($subjectType == Constant::SUBJECT_TYPE_USER) {
            $objectiveArray = $tOkrRepos->getUserObjectives($userId, $timeframeId, $companyId);
        } elseif ($subjectType == Constant::SUBJECT_TYPE_GROUP) {
            $objectiveArray = $tOkrRepos->getGroupObjectives($groupId, $timeframeId, $companyId);
        } else {
            $objectiveArray = $tOkrRepos->getCompanyObjectives($companyId, $timeframeId);

            // 会社名を取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($companyId);
            $companyName = $mCompany->getCompanyName();
        }

        $okrOperationLogic = $this->getOkrOperationLogic();
        $children = array();
        foreach ($objectiveArray as $objective) {
            // OKRマップを取得
            $tOkrArray = $tOkrRepos->getOkrAndAllAlignmentOkrs($objective->getTreeLeft(), $objective->getTreeRight(), $timeframeId, $auth->getCompanyId());

            // 階層構造に変換
            $okrMapDTO = $okrOperationLogic->tree($auth, $tOkrArray, $companyName);

            $children[] = $okrMapDTO;
        }

        $retOkrMapDTO = new OkrMapDTO();
        $retOkrMapDTO->setOkrId(1);
        $retOkrMapDTO->setAchievementRate(1);
        $retOkrMapDTO->setStatus('0');
        $retOkrMapDTO->setChildren($children);
        $retOkrMapDTO->setHidden(true);

        return $retOkrMapDTO;
    }

    /**
     * 単一OKRマップ取得
     *
     * @param Auth $auth 認証情報
     * @param TOkr $tOkr 取得対象OKRエンティティ
     * @param integer $timeframeId タイムフレームID
     * @return ThreeGensOkrMapDTO
     */
    public function getDescendants(Auth $auth, TOkr $tOkr, int $timeframeId): OkrMapDTO
    {
        // OKRマップを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getOkrAndAllAlignmentOkrs($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $timeframeId, $auth->getCompanyId());

        // 会社名を取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());
        $companyName = $mCompany->getCompanyName();

        // 階層構造に変換
        $okrOperationLogic = $this->getOkrOperationLogic();
        $okrMapDTO = $okrOperationLogic->tree($auth, $tOkrArray, $companyName);

        return $okrMapDTO;
    }

    /**
     * 3世代OKRを取得
     *
     * @param Auth $auth 認証情報
     * @param integer $okrId 取得対象OKRID
     * @param integer $timeframeId タイムフレームID
     * @return ThreeGensOkrMapDTO
     */
    public function getThreeGensOkrMap(Auth $auth, int $okrId, int $timeframeId): ThreeGensOkrMapDTO
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
     * @param TOkr $tOkr OKRエンティティ
     * @param string $companyName 会社名
     * @return BasicOkrDTO
     */
    private function repackDTOWithOkrEntity(TOkr $tOkr, string $companyName = null): BasicOkrDTO
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
