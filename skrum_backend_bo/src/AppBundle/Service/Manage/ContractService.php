<?php

namespace AppBundle\Service\Manage;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\DateUtility;
use AppBundle\Entity\TContract;
use AppBundle\Entity\TAuthorization;

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
     * @return array
     */
    public function getContracts(): array
    {
        // 契約プラン情報を取得
        $tAuthorizationRepos = $this->getTAuthorizationRepository();
        $contractPlanInfo = $tAuthorizationRepos->getAuthorizations();

        $count = count($contractPlanInfo);
        $data = array();
        $mUserRepos = $this->getMUserRepository();
        for ($i = 0; $i < $count; ++$i) {
            // ユーザ数を取得
            $userCount = $mUserRepos->getUserCount($contractPlanInfo[$i]['companyId']);

            $item = array();

            if ($contractPlanInfo[$i]['tContract'] !== null) {
                $item['contractId'] = $contractPlanInfo[$i]['tContract']->getContractId();
                $item['priceType'] = $contractPlanInfo[$i]['tContract']->getPriceType();
                $item['price'] = $contractPlanInfo[$i]['tContract']->getPrice();
                $item['planStartDate'] = DateUtility::transIntoDateString($contractPlanInfo[$i]['tContract']->getPlanStartDate());
                $item['planEndDate'] = DateUtility::transIntoDateString($contractPlanInfo[$i]['tContract']->getPlanEndDate());
            }
            $item['companyId'] = $contractPlanInfo[$i]['companyId'];
            $item['companyName'] = $contractPlanInfo[$i]['companyName'];
            $item['planName'] = $contractPlanInfo[$i]['planName'];
            $item['userCount'] = $userCount;
            $item['authorizationStopFlg'] = $contractPlanInfo[$i]['authorizationStopFlg'];

            $data[] = $item;
        }

        return $data;
    }

    /**
     * 契約プラン情報取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getContract(int $companyId): array
    {
        // 契約プラン情報を取得
        $tAuthorizationRepos = $this->getTAuthorizationRepository();
        $contractPlanInfo = $tAuthorizationRepos->getAuthorization($companyId);

        $count = count($contractPlanInfo);
        $data = array();
        $mUserRepos = $this->getMUserRepository();
        for ($i = 0; $i < $count; ++$i) {
            // ユーザ数を取得
            $userCount = $mUserRepos->getUserCount($contractPlanInfo[$i]['companyId']);

            $item = array();

            if ($contractPlanInfo[$i]['tContract'] !== null) {
                $item['contractId'] = $contractPlanInfo[$i]['tContract']->getContractId();
                $item['priceType'] = $contractPlanInfo[$i]['tContract']->getPriceType();
                $item['price'] = $contractPlanInfo[$i]['tContract']->getPrice();
                $item['planStartDate'] = DateUtility::transIntoDateString($contractPlanInfo[$i]['tContract']->getPlanStartDate());
                $item['planEndDate'] = DateUtility::transIntoDateString($contractPlanInfo[$i]['tContract']->getPlanEndDate());
            }
            $item['companyId'] = $contractPlanInfo[$i]['companyId'];
            $item['companyName'] = $contractPlanInfo[$i]['companyName'];
            $item['planName'] = $contractPlanInfo[$i]['planName'];
            $item['userCount'] = $userCount;
            $item['authorizationStopFlg'] = $contractPlanInfo[$i]['authorizationStopFlg'];

            $data[] = $item;
        }

        return $data;
    }

    /**
     * 契約プラン情報取得
     *
     * @param integer $companyId 会社ID
     * @param integer $planId プランID
     * @return boolean
     */
    public function changePlan(int $companyId, int $planId): bool
    {
        // 認可情報を取得
        $tAuthorizationRepos = $this->getTAuthorizationRepository();
        $tAuthorizationArray = $tAuthorizationRepos->getValidAuthorization($companyId);

        // 認可停止フラグがTRUEの場合、変更不可
        if (count($tAuthorizationArray) === 0) {
            return false;
        }

        $tAuthorization = $tAuthorizationArray[0];

        // お試しプランへの変更は不可
        if ($planId === DBConstant::PLAN_ID_TRIAL_PLAN) {
            return false;
        }

        // 同一プランでないことを確認
        if ($planId === $tAuthorization->getPlanId()) {
            return false;
        }

        // プランIDがいづれかのプランIDに一致することを確認（現状、スタンダードプランのみ）
        if ($planId !== DBConstant::PLAN_ID_STANDARD_PLAN) {
            return false;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 契約テーブルにレコードが存在する場合（現在プランがお試しプランでない場合）、プラン終了日を本日に変更
            if ($tAuthorization->getPlanId() !== DBConstant::PLAN_ID_TRIAL_PLAN) {
                $tContractRepos = $this->getTContractRepository();
                $tContract = $tContractRepos->getValidContract($companyId);

                $tContract->setPlanEndDate(DateUtility::getCurrentDate());
            }

            // 変更後プラン情報を取得
            $mPlanRepos = $this->getMPlanRepository();
            $mPlan = $mPlanRepos->find($planId);

            // 契約テーブルにレコード追加
            $newTContract = new TContract();
            $newTContract->setCompanyId($companyId);
            $newTContract->setPlanId($planId);
            $newTContract->setPrice($mPlan->getPrice());
            $newTContract->setPriceType($mPlan->getPriceType());
            $newTContract->setPlanStartDate(DateUtility::getCurrentDate());
            $newTContract->setPlanEndDate(DateUtility::getMaxDate());
            $this->persist($newTContract);
            $this->flush();

            // 現在の認可終了日時を現在時刻に変更
            $now = DateUtility::getCurrentDatetime();
            $tAuthorization->setAuthorizationEndDatetime($now);

            // 新しい認可を登録
            $newTAuthorization = new TAuthorization();
            $newTAuthorization->setCompanyId($companyId);
            $newTAuthorization->setPlanId($planId);
            $newTAuthorization->setContractId($newTContract->getContractId());
            $newTAuthorization->setAuthorizationStartDatetime($now);
            $newTAuthorization->setAuthorizationEndDatetime(DateUtility::getMaxDatetime());
            $this->persist($newTAuthorization);
            $this->flush();

            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->logError($e->getMessage());
            return false;
        }
    }
}
