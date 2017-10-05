<?php

namespace AppBundle\Logic;

use AppBundle\Exception\NoDataException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MRoleAssignment;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TGroupTree;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOneOnOne;
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
     * ユーザ存在チェック（アーカイブ済ユーザ含まず）
     *
     * @param string $targetUserId チェック対象ユーザID
     * @param integer $companyId 会社ID
     * @return MUser ユーザエンティティ
     */
    public function checkUserExistance(string $targetUserId, int $companyId): MUser
    {
        // 数字チェック
        if (!is_numeric($targetUserId)) {
            throw new NoDataException('ユーザが存在しません');
        }

        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUser($targetUserId, $companyId);
        if (count($mUserArray) === 0) {
            throw new NoDataException('ユーザが存在しません');
        }

        return $mUserArray[0];
    }

    /**
     * ユーザ存在チェック（アーカイブ済ユーザ含む）
     *
     * @param string $targetUserId チェック対象ユーザID
     * @param integer $companyId 会社ID
     * @return MUser ユーザエンティティ
     */
    public function checkUserExistanceIncludingArchivedUsers(string $targetUserId, int $companyId): MUser
    {
        // 数字チェック
        if (!is_numeric($targetUserId)) {
            throw new NoDataException('ユーザが存在しません');
        }

        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->getUser($targetUserId, $companyId, true);
        if (count($mUserArray) === 0) {
            throw new NoDataException('ユーザが存在しません');
        }

        return $mUserArray[0];
    }

    /**
     * グループ存在チェック（アーカイブ済ユーザ含まず）
     *
     * @param string $targetGroupId チェック対象グループID
     * @param integer $companyId 会社ID
     * @return MGroup グループエンティティ
     */
    public function checkGroupExistance(string $targetGroupId, int $companyId): MGroup
    {
        // 数字チェック
        if (!is_numeric($targetGroupId)) {
            throw new NoDataException('グループが存在しません');
        }

        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroup($targetGroupId, $companyId);
        if (count($mGroupArray) === 0) {
            throw new NoDataException('グループが存在しません');
        }

        return $mGroupArray[0];
    }

    /**
     * グループ存在チェック（アーカイブ済ユーザ含む）
     *
     * @param string $targetGroupId チェック対象グループID
     * @param integer $companyId 会社ID
     * @return MGroup グループエンティティ
     */
    public function checkGroupExistanceIncludingArchivedGroups(string $targetGroupId, int $companyId): MGroup
    {
        // 数字チェック
        if (!is_numeric($targetGroupId)) {
            throw new NoDataException('グループが存在しません');
        }

        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroup($targetGroupId, $companyId, true);
        if (count($mGroupArray) === 0) {
            throw new NoDataException('グループが存在しません');
        }

        return $mGroupArray[0];
    }

    /**
     * グループパス存在チェック
     *
     * @param string $targetGroupPathId チェック対象グループパスID
     * @param integer $companyId 会社ID
     * @return TGroupTree グループツリーエンティティ
     */
    public function checkGroupPathExistance(string $targetGroupPathId, int $companyId): TGroupTree
    {
        // 数字チェック
        if (!is_numeric($targetGroupPathId)) {
            throw new NoDataException('グループパスが存在しません');
        }

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
     * @param string $targetOkrId チェック対象OKRID
     * @param integer $companyId 会社ID
     * @return TOkr OKRエンティティ
     */
    public function checkOkrExistance(string $targetOkrId, int $companyId): TOkr
    {
        // 数字チェック
        if (!is_numeric($targetOkrId)) {
            throw new NoDataException('OKRが存在しません');
        }

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
     * @param string $targetTimeframeId チェック対象タイムフレームID
     * @param integer $companyId 会社ID
     * @return TTimeframe タイムフレームエンティティ
     */
    public function checkTimeframeExistance(string $targetTimeframeId, int $companyId): TTimeframe
    {
        // 数字チェック
        if (!is_numeric($targetTimeframeId)) {
            throw new NoDataException('タイムフレームが存在しません');
        }

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
     * @param string $targetPostId チェック対象投稿ID
     * @param integer $companyId 会社ID
     * @return TPost 投稿エンティティ
     */
    public function checkPostExistance(string $targetPostId, int $companyId): TPost
    {
        // 数字チェック
        if (!is_numeric($targetPostId)) {
            throw new NoDataException('投稿が存在しません');
        }

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
     * @param string $targetRoleAssignmentId チェック対象ロール割当ID
     * @param integer $companyId 会社ID
     * @return MRoleAssignment ロール割当エンティティ
     */
    public function checkRoleAssignmentExistance(string $targetRoleAssignmentId, int $companyId): MRoleAssignment
    {
        // 数字チェック
        if (!is_numeric($targetRoleAssignmentId)) {
            throw new NoDataException('ロール割当が存在しません');
        }

        $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
        $mRoleAssignmentArray = $mRoleAssignmentRepos->getRoleAssignment($targetRoleAssignmentId, $companyId);
        if (count($mRoleAssignmentArray) === 0) {
            throw new NoDataException('ロール割当が存在しません');
        }

        return $mRoleAssignmentArray[0];
    }

    /**
     * 1on1存在チェック
     *
     * @param string $targetOneOnOneId チェック対象1on1ID
     * @param integer $companyId 会社ID
     * @return TOneOnOne 1on1エンティティ
     */
    public function checkOneOnOneExistance(string $targetOneOnOneId, int $companyId): TOneOnOne
    {
        // 数字チェック
        if (!is_numeric($targetOneOnOneId)) {
            throw new NoDataException('1on1が存在しません');
        }

        $tOneOnOneRepos = $this->getTOneOnOneRepository();
        $tOneOnOneArray = $tOneOnOneRepos->getOneOnOne($targetOneOnOneId, $companyId);
        if (count($tOneOnOneArray) === 0) {
            throw new NoDataException('1on1が存在しません');
        }

        return $tOneOnOneArray[0];
    }
}
