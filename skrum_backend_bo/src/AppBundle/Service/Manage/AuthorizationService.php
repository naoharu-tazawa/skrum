<?php

namespace AppBundle\Service\Manage;

use AppBundle\Service\BaseService;
use AppBundle\Entity\TAuthorization;

/**
 * 認可サービスクラス
 *
 * @author naoharu.tazawa
 */
class AuthorizationService extends BaseService
{
    /**
     * 認可停止/認可停止解除
     *
     * @param integer $companyId 会社ID
     * @param integer $authorizationStopFlg 認可停止フラグ
     * @return boolean
     */
    public function changeAuthzStopFlg(int $companyId, int $authorizationStopFlg): bool
    {
        // 認可情報を取得
        $tAuthorizationRepos = $this->getTAuthorizationRepository();
        $tAuthorization = $tAuthorizationRepos->getValidAuthorizationIncludingStoped($companyId);

        // 認可停止フラグに変更がない場合、更新しない
        if ($authorizationStopFlg == $tAuthorization->getAuthorizationStopFlg()) {
            return false;
        }

        try {
            // 認可停止フラグを更新
            $tAuthorization->setAuthorizationStopFlg($authorizationStopFlg);
            $this->flush();

            return true;
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }
}
