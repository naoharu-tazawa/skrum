<?php

namespace AppBundle\Logic;

use AppBundle\Exception\ApplicationException;

/**
 * DB存在チェックロジッククラス
 *
 * @author naoharu.tazawa
 */
class DBExistanceLogic extends BaseLogic
{
    /**
     * ユーザ存在チェック
     *
     * @param integer $targetUserId チェック対象ユーザID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\MUser ユーザエンティティ
     */
    public function checkUserExistance($targetUserId, $companyId)
    {
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUser($targetUserId, $companyId);
        if (count($mUserArray) == 0) {
            throw new ApplicationException('ユーザが存在しません');
        }

        return $mUserArray[0];
    }

    /**
     * グループ存在チェック
     *
     * @param integer $targetGroupId チェック対象グループID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\MGroup グループエンティティ
     */
    public function checkGroupExistance($targetGroupId, $companyId)
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroup($targetGroupId, $companyId);
        if (count($mGroupArray) == 0) {
            throw new ApplicationException('グループが存在しません');
        }

        return $mGroupArray[0];
    }

    /**
     * グループパス存在チェック
     *
     * @param integer $targetGroupPathId チェック対象グループパスID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\TGroupTree グループツリーエンティティ
     */
    public function checkGroupPathExistance($targetGroupPathId, $companyId)
    {
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTreeArray = $tGroupTreeRepos->getGroupPath($targetGroupPathId, $companyId);
        if (count($tGroupTreeArray) == 0) {
            throw new ApplicationException('グループパスが存在しません');
        }

        return $tGroupTreeArray[0];
    }

    /**
     * OKR存在チェック
     *
     * @param integer $targetOkrId チェック対象OKRID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\TOkr OKRエンティティ
     */
    public function checkOkrExistance($targetOkrId, $companyId)
    {
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getOkr($targetOkrId, $companyId);
        if (count($tOkrArray) == 0) {
            throw new ApplicationException('OKRが存在しません');
        }

        return $tOkrArray[0];
    }

    /**
     * タイムフレーム存在チェック
     *
     * @param integer $targetTimeframeId チェック対象タイムフレームID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\TTimeframe タイムフレームエンティティ
     */
    public function checkTimeframeExistance($targetTimeframeId, $companyId)
    {
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframeArray = $tTimeframeRepos->getTimeframe($targetTimeframeId, $companyId);
        if (count($tTimeframeArray) == 0) {
            throw new ApplicationException('タイムフレームが存在しません');
        }

        return $tTimeframeArray[0];
    }

    /**
     * 投稿存在チェック
     *
     * @param integer $targetPostId チェック対象投稿ID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Entity\TPost 投稿エンティティ
     */
    public function checkPostExistance($targetPostId, $companyId)
    {
        $tPostRepos = $this->getTPostRepository();
        $tPostArray = $tPostRepos->getPost($targetPostId, $companyId);
        if (count($tPostArray) == 0) {
            throw new ApplicationException('投稿が存在しません');
        }

        return $tPostArray[0];
    }
}
