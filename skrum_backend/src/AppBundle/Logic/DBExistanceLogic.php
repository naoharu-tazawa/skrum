<?php

namespace AppBundle\Logic;

use AppBundle\Exception\NoDataException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MRoleAssignment;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TGroupTree;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TPost;
use AppBundle\Entity\TTimeframe;

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
     * @return MUser ユーザエンティティ
     */
    public function checkUserExistance(int $targetUserId, int $companyId): MUser
    {
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUser($targetUserId, $companyId);
        if (count($mUserArray) === 0) {
            throw new NoDataException('ユーザが存在しません');
        }

        return $mUserArray[0];
    }

    /**
     * グループ存在チェック
     *
     * @param integer $targetGroupId チェック対象グループID
     * @param integer $companyId 会社ID
     * @return MGroup グループエンティティ
     */
    public function checkGroupExistance(int $targetGroupId, int $companyId): MGroup
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroup($targetGroupId, $companyId);
        if (count($mGroupArray) === 0) {
            throw new NoDataException('グループが存在しません');
        }

        return $mGroupArray[0];
    }

    /**
     * グループパス存在チェック
     *
     * @param integer $targetGroupPathId チェック対象グループパスID
     * @param integer $companyId 会社ID
     * @return TGroupTree グループツリーエンティティ
     */
    public function checkGroupPathExistance(int $targetGroupPathId, int $companyId): TGroupTree
    {
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTreeArray = $tGroupTreeRepos->getGroupPath($targetGroupPathId, $companyId);
        if (count($tGroupTreeArray) === 0) {
            throw new NoDataException('グループパスが存在しません');
        }

        return $tGroupTreeArray[0];
    }

    /**
     * OKR存在チェック
     *
     * @param integer $targetOkrId チェック対象OKRID
     * @param integer $companyId 会社ID
     * @return TOkr OKRエンティティ
     */
    public function checkOkrExistance(int $targetOkrId, int $companyId): TOkr
    {
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getOkr($targetOkrId, $companyId);
        if (count($tOkrArray) === 0) {
            throw new NoDataException('OKRが存在しません');
        }

        return $tOkrArray[0];
    }

    /**
     * タイムフレーム存在チェック
     *
     * @param integer $targetTimeframeId チェック対象タイムフレームID
     * @param integer $companyId 会社ID
     * @return TTimeframe タイムフレームエンティティ
     */
    public function checkTimeframeExistance(int $targetTimeframeId, int $companyId): TTimeframe
    {
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframeArray = $tTimeframeRepos->getTimeframe($targetTimeframeId, $companyId);
        if (count($tTimeframeArray) === 0) {
            throw new NoDataException('タイムフレームが存在しません');
        }

        return $tTimeframeArray[0];
    }

    /**
     * 投稿存在チェック
     *
     * @param integer $targetPostId チェック対象投稿ID
     * @param integer $companyId 会社ID
     * @return TPost 投稿エンティティ
     */
    public function checkPostExistance(int $targetPostId, int $companyId): TPost
    {
        $tPostRepos = $this->getTPostRepository();
        $tPostArray = $tPostRepos->getPost($targetPostId, $companyId);
        if (count($tPostArray) === 0) {
            throw new NoDataException('投稿が存在しません');
        }

        return $tPostArray[0];
    }

    /**
     * ロール割当存在チェック
     *
     * @param integer $targetRoleAssignmentId チェック対象ロール割当ID
     * @param integer $companyId 会社ID
     * @return MRoleAssignment ロール割当エンティティ
     */
    public function checkRoleAssignmentExistance(int $targetRoleAssignmentId, int $companyId): MRoleAssignment
    {
        $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
        $mRoleAssignmentArray = $mRoleAssignmentRepos->getRoleAssignment($targetRoleAssignmentId, $companyId);
        if (count($mRoleAssignmentArray) === 0) {
            throw new NoDataException('ロール割当が存在しません');
        }

        return $mRoleAssignmentArray[0];
    }
}
