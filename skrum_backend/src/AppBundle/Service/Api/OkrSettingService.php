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
}
