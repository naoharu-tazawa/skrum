<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Api\ResponseDTO\OkrDetailsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\AchievementRateChartDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;
use AppBundle\Entity\MCompany;

/**
 * OKR詳細サービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrDetailsService extends BaseService
{
    /**
     * OKR詳細情報を取得
     *
     * @param Auth $auth 認証情報
     * @param TOkr $okrEntity OKRエンティティ
     * @return OkrDetailsDTO
     */
    public function getOkrDetails(Auth $auth, TOkr $okrEntity): OkrDetailsDTO
    {
        // OKRIDを取得
        $okrId = $okrEntity->getOkrId();

        // 指定OKRがキーリザルトだった場合、目標を指定する
        if ($okrEntity->getType() == DBConstant::OKR_TYPE_KEY_RESULT) {
            $parentOkr = $okrEntity->getParentOkr();
            if (empty($parentOkr)) {
                throw new ApplicationException('キーリザルトは表示できません');
            }

            $okrId = $parentOkr->getOkrId();
        }

        // 親OKRとキーリザルトを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getThreeGensOkrs($okrId, $okrEntity->getTimeframe()->getTimeframeId(), $auth->getCompanyId());

        // 会社名を取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());

        // DTOに詰め替える
        $disclosureLogic = $this->getDisclosureLogic();
        $okrDetailsDTO = new OkrDetailsDTO();
        $childrenOkrs = array();
        $chartSetFlg = true;
        foreach ($tOkrArray as $tOkr) {
            if (array_key_exists('childrenOkr', $tOkr)) {
                if (!empty($tOkr['childrenOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['childrenOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['childrenOkr'], $mCompany);
                    $childrenOkrs[] = $basicOkrDTO;
                }
            } elseif (array_key_exists('parentOkr', $tOkr)) {
                if (!empty($tOkr['parentOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['parentOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['parentOkr'], $mCompany);
                    $okrDetailsDTO->setParentOkr($basicOkrDTO);
                }
            } else {
                if (!empty($tOkr['selectedOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['selectedOkr'])) {
                        $chartSetFlg = false;
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['selectedOkr'], $mCompany);
                    $okrDetailsDTO->setObjective($basicOkrDTO);
                }
            }
        }
        $okrDetailsDTO->setKeyResults($childrenOkrs);

        // 達成率チャートを取得
        $chart = array();
        if ($chartSetFlg) {
            $tOkrActivityRepos = $this->getTOkrActivityRepository();
            $tOkrActivityArray = $tOkrActivityRepos->getAchievementRateChart($okrId);
            foreach ($tOkrActivityArray as $tOkrActivity) {
                $achievementRateChartDTO = new AchievementRateChartDTO();
                $achievementRateChartDTO->setDatetime($tOkrActivity['datetime']);
                $achievementRateChartDTO->setAchievementRate($tOkrActivity['achievementRate']);

                $chart[] = $achievementRateChartDTO;
            }
        }

        $okrDetailsDTO->setChart($chart);

        return $okrDetailsDTO;
    }

    /**
     * OKRエンティティをDTOに詰め替える
     *
     * @param TOkr $tOkr OKRエンティティ
     * @param MCompany $mCompany 会社エンティティ
     * @return BasicOkrDTO
     */
    private function repackDTOWithOkrEntity(TOkr $tOkr, MCompany $mCompany = null): BasicOkrDTO
    {
        $basicOkrDTO = new BasicOkrDTO();
        $basicOkrDTO->setOkrId($tOkr->getOkrId());
        $basicOkrDTO->setOkrType($tOkr->getType());
        $basicOkrDTO->setOkrName($tOkr->getName());
        $basicOkrDTO->setOkrDetail($tOkr->getDetail());
        $basicOkrDTO->setTargetValue($tOkr->getTargetValue());
        $basicOkrDTO->setAchievedValue($tOkr->getAchievedValue());
        $basicOkrDTO->setAchievementRate($tOkr->getAchievementRate());
        $basicOkrDTO->setUnit($tOkr->getUnit());
        $basicOkrDTO->setOwnerType($tOkr->getOwnerType());
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            $basicOkrDTO->setOwnerUserId($tOkr->getOwnerUser()->getUserId());
            $basicOkrDTO->setOwnerUserName($tOkr->getOwnerUser()->getLastName() . ' ' . $tOkr->getOwnerUser()->getFirstName());
            $basicOkrDTO->setOwnerUserImageVersion($tOkr->getOwnerUser()->getImageVersion());
            $basicOkrDTO->setOwnerUserRoleLevel($tOkr->getOwnerUser()->getRoleAssignment()->getRoleLevel());
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $basicOkrDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
            $basicOkrDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
            $basicOkrDTO->setOwnerGroupImageVersion($tOkr->getOwnerGroup()->getImageVersion());
            $basicOkrDTO->setOwnerGroupType($tOkr->getOwnerGroup()->getGroupType());
        } else {
            $basicOkrDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
            $basicOkrDTO->setOwnerCompanyName($mCompany->getCompanyName());
            $basicOkrDTO->setOwnerCompanyImageVersion($mCompany->getImageVersion());
        }
        $basicOkrDTO->setStartDate($tOkr->getStartDate());
        $basicOkrDTO->setEndDate($tOkr->getEndDate());
        $basicOkrDTO->setStatus($tOkr->getStatus());
        $basicOkrDTO->setDisclosureType($tOkr->getDisclosureType());
        $basicOkrDTO->setWeightedAverageRatio($tOkr->getWeightedAverageRatio());
        $basicOkrDTO->setRatioLockedFlg($tOkr->getRatioLockedFlg());

        return $basicOkrDTO;
    }
}
