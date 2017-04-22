<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\ContractDTO;
use AppBundle\Utils\DBConstant;

/**
 * 契約サービスクラス
 *
 * @author naoharu.tazawa
 */
class ContractService extends BaseService
{
    /**
     * 契約プラン情報取得
     *
     * @param integer $companyId 会社ID
     * @return \AppBundle\Api\ResponseDTO\ContractDTO
     */
    public function getContractInfo($companyId)
    {
        // ユーザ数を取得
        $mUserRepos = $this->getMUserRepository();
        $userCount = $mUserRepos->getUserCount($companyId);

        // 契約プラン情報を取得
        $tContractRepos = $this->getTContractRepository();
        $contractPlanInfo = $tContractRepos->getContract($companyId);

        $contractDTO = new ContractDTO();
        if (count($contractPlanInfo) >= 1) {
            // 契約情報がある場合
            $contractDTO->setPlanId($contractPlanInfo[0]->getPlanId());
            $contractDTO->setPlanName($contractPlanInfo[1]->getName());
            $contractDTO->setUserCount($userCount);
            $contractDTO->setPriceType($contractPlanInfo[0]->getPriceType());
            $contractDTO->setPrice($contractPlanInfo[0]->getPrice());
        } else {
            // 契約情報がない場合
            // お試しプランをプランマスタから直接取得
            $mPlanRepos = $this->getMPlanRepository();
            $mPlan = $mPlanRepos->find(DBConstant::PLAN_ID_TRIAL_PLAN);

            $contractDTO->setPlanId($mPlan->getPlanId());
            $contractDTO->setPlanName($mPlan->getName());
            $contractDTO->setUserCount($userCount);
            $contractDTO->setPriceType($mPlan->getPriceType());
            $contractDTO->setPrice($mPlan->getPrice());
        }

        return $contractDTO;
    }
}
