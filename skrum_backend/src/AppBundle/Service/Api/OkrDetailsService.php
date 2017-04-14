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
use AppBundle\Entity\TPost;
use AppBundle\Api\ResponseDTO\OkrDetailsDTO;
use AppBundle\Api\ResponseDTO\NestedObject\AchievementRateChartDTO;

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
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param integer $okrId OKRID
     * @param integer $timeframeId タイムフレームID
     * @return \AppBundle\Api\ResponseDTO\OkrDetailsDTO
     */
    public function getOkrDetails($auth, $okrId, $timeframeId)
    {
        // 親OKRとキーリザルトを取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getThreeGensOkrs($okrId, $timeframeId, $auth->getCompanyId());

        // 会社名を取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());
        $companyName = $mCompany->getCompanyName();

        // DTOに詰め替える
        $disclosureLogic = $this->getDisclosureLogic();
        $okrDetailsDTO = new OkrDetailsDTO();
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
                    $okrDetailsDTO->setParentOkr($basicOkrDTO);
                }
            } else {
                if (!empty($tOkr['selectedOkr'])) {
                    // 閲覧権限をチェック
                    if (!$disclosureLogic->checkOkr($auth->getUserId(), $auth->getRoleLevel(), $tOkr['selectedOkr'])) {
                        continue;
                    }
                    $basicOkrDTO = $this->repackDTOWithOkrEntity($tOkr['selectedOkr'], $companyName);
                    $okrDetailsDTO->setObjective($basicOkrDTO);
                }
            }
        }
        $okrDetailsDTO->setKeyResults($childrenOkrs);

        // 達成率チャートを取得
        $tOkrActivityRepos = $this->getTOkrActivityRepository();
        $tOkrActivityArray = $tOkrActivityRepos->getAchievementRateChart($okrId);
        $chart = array();
        foreach ($tOkrActivityArray as $tOkrActivity) {
            $achievementRateChartDTO = new AchievementRateChartDTO();
            $achievementRateChartDTO->setDatetime($tOkrActivity['datetime']);
            $achievementRateChartDTO->setAchievementRate($tOkrActivity['achievementRate']);

            $chart[] = $achievementRateChartDTO;
        }

        $okrDetailsDTO->setChart($chart);

        return $okrDetailsDTO;
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
        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            $basicOkrDTO->setOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
            $basicOkrDTO->setOwnerGroupName($tOkr->getOwnerGroup()->getGroupName());
        } else {
            $basicOkrDTO->setOwnerCompanyId($tOkr->getOwnerCompanyId());
            $basicOkrDTO->setOwnerCompanyName($companyName);
        }
        $basicOkrDTO->setStartDate($tOkr->getStartDate());
        $basicOkrDTO->setEndDate($tOkr->getEndDate());
        $basicOkrDTO->setStatus($tOkr->getStatus());
        $basicOkrDTO->setWeightedAverageRatio($tOkr->getWeightedAverageRatio());
        $basicOkrDTO->setRatioLockedFlg($tOkr->getRatioLockedFlg());

        return $basicOkrDTO;
    }
}
