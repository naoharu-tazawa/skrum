<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;

/**
 * OKR設定サービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrSettingService extends BaseService
{
    /**
     * OKRクローズ
     *
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function closeOkr($tOkr)
    {
        // クローズ対象OKRが既にクローズド状態の場合、更新処理を行わない
        if ($tOkr->getStatus() == DBConstant::OKR_STATUS_CLOSED) {
            return;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR更新
            $tOkr->setStatus(DBConstant::OKR_STATUS_CLOSED);

            // OKRアクティビティ登録（クローズ）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_CLOSE);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOkrActivity);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKRオープン
     *
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function openOkr($tOkr)
    {
        // オープン対象OKRが既にオープン状態の場合、更新処理を行わない
        if ($tOkr->getStatus() == DBConstant::OKR_STATUS_OPEN) {
            return;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR更新
            $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);

            // OKRアクティビティ登録（オープン）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_OPEN);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOkrActivity);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR公開設定変更
     *
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @param string $disclosureType 公開種別
     * @return void
     */
    public function changeDisclosure($tOkr, $disclosureType)
    {
        // 更新対象OKRの公開種別とリクエストJSONで指定された公開種別が一致する場合、更新処理を行わない
        if ($tOkr->getDisclosureType() == $disclosureType) {
            return;
        }

        // OKR更新
        $tOkr->setDisclosureType($disclosureType);

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR所有者変更
     *
     * @param \AppBundle\Entity\TOkr $tOkr OKRエンティティ
     * @param string $ownerType オーナー種別
     * @param \AppBundle\Entity\MUser $mUser ユーザエンティティ
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @param integer $companyId 会社ID
     * @return void
     */
    public function changeOwner($tOkr, $ownerType, $mUser, $mGroup, $companyId)
    {
        // OKRアクティビティ登録（オーナー変更）
        $tOkrActivity = new TOkrActivity();
        $tOkrActivity->setOkr($tOkr);
        $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_OWNER_CHANGE);
        $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());

        // 変更前のオーナー種別によって処理を分岐
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            // 「変更前オーナー種別＝1:ユーザ」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝1:ユーザ」の場合

                // オーナー変更がない場合は更新処理を行わない
                if ($tOkr->getOwnerUser()->getUserId() == $mUser->getUserId()) {
                    return;
                }

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerUserId($tOkr->getOwnerUser()->getUserId());
                $tOkrActivity->setNewOwnerUserId($mUser->getUserId());

                // オーナー変更
                $tOkr->setOwnerUser($mUser);

            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝2:グループ」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerUserId($tOkr->getOwnerUser()->getUserId());
                $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                $tOkr->setOwnerUser(null);
                $tOkr->setOwnerGroup($mGroup);

            } else {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝3:会社」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerUserId($tOkr->getOwnerUser()->getUserId());
                $tOkrActivity->setNewOwnerCompanyId($companyId);

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                $tOkr->setOwnerUser(null);
                $tOkr->setOwnerCompanyId($companyId);

            }

        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            // 「変更前オーナー種別＝2:グループ」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝1:ユーザ」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $tOkrActivity->setNewOwnerUserId($mUser->getUserId());

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                $tOkr->setOwnerGroup(null);
                $tOkr->setOwnerUser($mUser);

            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝2:グループ」の場合

                // オーナー変更がない場合は更新処理を行わない
                if ($tOkr->getOwnerGroup()->getGroupId() == $mGroup->getGroupId()) {
                    return;
                }

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());

                // オーナー変更
                $tOkr->setOwnerGroup($mGroup);

            } else {
                // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝3:会社」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $tOkrActivity->setNewOwnerCompanyId($companyId);

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                $tOkr->setOwnerGroup(null);
                $tOkr->setOwnerCompanyId($companyId);

            }

        } else {
            // 「変更前オーナー種別＝3:会社」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝1:ユーザ」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerCompanyId($tOkr->getOwnerCompanyId());
                $tOkrActivity->setNewOwnerUserId($mUser->getUserId());

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                $tOkr->setOwnerCompanyId(null);
                $tOkr->setOwnerUser($mUser);

            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝2:グループ」の場合

                // 変更前/変更後オーナー登録
                $tOkrActivity->setPreviousOwnerGroupId($tOkr->getOwnerGroup()->getGroupId());
                $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                $tOkr->setOwnerCompanyId(null);
                $tOkr->setOwnerGroup($mGroup);

            } else {
                // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝3:会社」の場合

                // オーナー変更がないので更新処理を行わない
                return;
            }

        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * KR加重平均割合更新
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param \AppBundle\Entity\TOkr $okrEntity OKRエンティティ
     * @return void
     */
    public function updateRatio($auth, $data, $okrEntity)
    {
        // 親OKRIDを取得
        $parentOkrId = $okrEntity->getOkrId();

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 持分比率ロックフラグをリセットする
            $tOkrRepos = $this->getTOkrRepository();
            $tOkrRepos->resetRatioLockedFlg($okrEntity->getOkrId(), $okrEntity->getTimeframe()->getTimeframeId(), $auth->getCompanyId());

            // KR加重平均割合更新
            foreach ($data as $item) {
                $tOkr = $tOkrRepos->getOkr($item['keyResultId'], $auth->getCompanyId());
                if (count($tOkr) != 1) {
                    throw new ApplicationException('指定されたキーリザルトは存在しません');
                }

                // 親OKRIDが一致しない場合、エラー
                if ($tOkr[0]->getParentOkr()->getOkrId() != $parentOkrId) {
                    throw new ApplicationException('指定されたキーリザルトが不正です');
                }

                $tOkr[0]->setWeightedAverageRatio($item['weightedAverageRatio']);
                $tOkr[0]->setRatioLockedFlg(DBConstant::FLG_TRUE);
                $this->flush();
            }

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculateFromParent($okrEntity, $auth->getCompanyId(), true);

            $this->flush();
            $this->commit();
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
