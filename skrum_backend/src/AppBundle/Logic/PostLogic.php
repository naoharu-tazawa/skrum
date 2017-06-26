<?php

namespace AppBundle\Logic;

use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TOkr;

/**
 * 投稿ロジッククラス
 *
 * @author naoharu.tazawa
 */
class PostLogic extends BaseLogic
{
    /**
     * 投稿先グループのグループID配列を取得（OKR所有者がグループの場合）
     *
     * @param Auth $auth 認証情報
     * @param string $post 投稿
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @return array グループID配列
     */
    public function getGroupIdArrayForGroup(Auth $auth, string $post, TOkr $tOkr): array
    {
        $groupIdArray = array();
        $tOkrRepos = $this->getTOkrRepository();

        if (!empty($post)) {
            // 進捗登録対象OKRのオーナーがグループの場合、投稿先グループに入れる
            if ($tOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                $groupIdArray[] = $tOkr->getOwnerGroup()->getGroupId();
            }

            // 親OKRまたは祖父母OKRのオーナーがグループの場合、そのグループを投稿先グループに入れる
            if ($tOkr->getParentOkr() !== null) {
                $parentAndGrandParentOkr = $tOkrRepos->getParentOkr($tOkr->getParentOkr()->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $auth->getCompanyId());
                if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
                    if (!empty($parentAndGrandParentOkr[0]['childOkr'])) {
                        if ($parentAndGrandParentOkr[0]['childOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $groupIdArray[] = $parentAndGrandParentOkr[0]['childOkr']->getOwnerGroup()->getGroupId();
                        }
                    }
                } else {
                    if (!empty($parentAndGrandParentOkr[1]['parentOkr'])) {
                        if ($parentAndGrandParentOkr[1]['parentOkr']->getOwnerType() == DBConstant::OKR_OWNER_TYPE_GROUP) {
                            $groupIdArray[] = $parentAndGrandParentOkr[1]['parentOkr']->getOwnerGroup()->getGroupId();
                        }
                    }
                }
            }

            // 二重投稿を防ぐ
            if (count($groupIdArray) === 2) {
                if ($groupIdArray[0] == $groupIdArray[1]) {
                    unset($groupIdArray[1]);
                }
            }
        }

        return $groupIdArray;
    }

    /**
     * 投稿先グループのグループID配列を取得（OKR所有者がユーザの場合）
     *
     * @param Auth $auth 認証情報
     * @param string $post 投稿
     * @param TOkr $tOkr 操作対象OKRエンティティ
     * @return array グループID配列
     */
    public function getGroupIdArrayForUser(Auth $auth, string $post, TOkr $tOkr): array
    {

    }
}
