<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\TOkr;
use AppBundle\Utils\DBConstant;

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

        // OKR更新
        $tOkr->setStatus(DBConstant::OKR_STATUS_CLOSED);

        try {
            $this->flush();
        } catch(\Exception $e) {
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

        // OKR更新
        $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);

        try {
            $this->flush();
        } catch(\Exception $e) {
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
        // 変更前のオーナー種別によって処理を分岐
        if ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_USER) {
            // 「変更前オーナー種別＝1:ユーザ」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝1:ユーザ」の場合

                // オーナー変更がない場合は更新処理を行わない
                if ($tOkr->getOwnerUser()->getUserId() == $mUser->getUserId()) {
                    return;
                }

                // オーナー変更
                $tOkr->setOwnerUser($mUser);

            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝2:グループ」の場合

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                $tOkr->setOwnerUser(null);
                $tOkr->setOwnerGroup($mGroup);

            } else {
                // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝3:会社」の場合

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                $tOkr->setOwnerUser(null);
                $tOkr->setOwnerCompanyId($companyId);

            }

        } elseif ($tOkr->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
            // 「変更前オーナー種別＝2:グループ」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝1:ユーザ」の場合

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

                // オーナー変更
                $tOkr->setOwnerGroup($mGroup);

            } else {
                // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝3:会社」の場合

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                $tOkr->setOwnerGroup(null);
                $tOkr->setOwnerCompanyId($companyId);

            }

        } else {
            // 「変更前オーナー種別＝3:会社」の場合

            if ($ownerType == DBConstant::OKR_OWNER_TYPE_USER) {
                // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝1:ユーザ」の場合

                // オーナー変更
                $tOkr->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                $tOkr->setOwnerCompanyId(null);
                $tOkr->setOwnerUser($mUser);

            } elseif ($ownerType == DBConstant::OKR_OWNER_TYPE_GROUP) {
                // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝2:グループ」の場合

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

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}