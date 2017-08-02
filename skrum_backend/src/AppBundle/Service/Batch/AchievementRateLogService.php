<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TAchievementRateLog;

/**
 * 目標進捗率ログ作成サービスクラス
 *
 * @author naoharu.tazawa
 */
class AchievementRateLogService extends BaseService
{
    /**
     * 目標進捗率ログ作成（会社）
     *
     * @param integer $bulkSize バルクサイズ
     * @return void
     */
    private function createCompaniesLog(int $bulkSize)
    {
        // 対象となる会社を全て抽出
        $mCompanyRepos = $this->getMCompanyRepository();
        $companyInfoArray = $mCompanyRepos->getAllCompaniesWithAchievementRate();

        $companyInfoArrayCount = count($companyInfoArray);
        foreach ($companyInfoArray as $key => $companyInfo) {

            if ($key === 0) {
                // 初回ループ

                $tAchievementRateLog = new TAchievementRateLog();
                $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                $tAchievementRateLog->setOwnerCompanyId($companyInfo['companyId']);
                $tAchievementRateLog->setTimeframeId($companyInfo['timeframeId']);

                $achievementRateArray = array();
                $achievementRateArray[] = $companyInfo['achievementRate'];
            } else {
                // 初回と最終以外のループ

                if ($companyInfo['companyId'] == $companyId) {
                    // 会社IDが前回ループ時と同じ場合

                    $achievementRateArray[] = $companyInfo['achievementRate'];
                } else {
                    // 会社IDが前回ループ時と異なる場合

                    // 集計した達成率の平均値を設定
                    $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                    // 永続化
                    $this->persist($tAchievementRateLog);


                    $tAchievementRateLog = new TAchievementRateLog();
                    $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                    $tAchievementRateLog->setOwnerCompanyId($companyInfo['companyId']);
                    $tAchievementRateLog->setTimeframeId($companyInfo['timeframeId']);

                    $achievementRateArray = array();
                    $achievementRateArray[] = $companyInfo['achievementRate'];
                }
            }

            // 会社ID保持
            $companyId = $companyInfo['companyId'];

            // 最終ループ
            if ($key === ($companyInfoArrayCount - 1)) {
                // 集計した達成率の平均値を設定
                $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                // 永続化
                $this->persist($tAchievementRateLog);
            }

            // バルクインサート
            if ($key % $bulkSize === 0) {
                $this->flush();
                $this->clear();
            }
        }

        $this->flush();
        $this->clear();
    }

    /**
     * 目標進捗率ログ作成（グループ）
     *
     * @param integer $bulkSize バルクサイズ
     * @return void
     */
    private function createGroupsLog(int $bulkSize)
    {
        // 対象となる会社を全て抽出
        $mGroupRepos = $this->getMGroupRepository();
        $groupInfoArray = $mGroupRepos->getAllGroupsWithAchievementRate();

        $groupInfoArrayCount = count($groupInfoArray);
        foreach ($groupInfoArray as $key => $groupInfo) {

            if ($key === 0) {
                // 初回ループ

                $tAchievementRateLog = new TAchievementRateLog();
                $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                $tAchievementRateLog->setOwnerGroupId($groupInfo['groupId']);
                $tAchievementRateLog->setTimeframeId($groupInfo['timeframeId']);

                $achievementRateArray = array();
                $achievementRateArray[] = $groupInfo['achievementRate'];
            } else {
                // 初回と最終以外のループ

                if ($groupInfo['groupId'] == $groupId) {
                    // グループIDが前回ループ時と同じ場合

                    $achievementRateArray[] = $groupInfo['achievementRate'];
                } else {
                    // グループIDが前回ループ時と異なる場合

                    // 集計した達成率の平均値を設定
                    $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                    // 永続化
                    $this->persist($tAchievementRateLog);


                    $tAchievementRateLog = new TAchievementRateLog();
                    $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                    $tAchievementRateLog->setOwnerGroupId($groupInfo['groupId']);
                    $tAchievementRateLog->setTimeframeId($groupInfo['timeframeId']);

                    $achievementRateArray = array();
                    $achievementRateArray[] = $groupInfo['achievementRate'];
                }
            }

            // グループID保持
            $groupId = $groupInfo['groupId'];

            // 最終ループ
            if ($key === ($groupInfoArrayCount - 1)) {
                // 集計した達成率の平均値を設定
                $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                // 永続化
                $this->persist($tAchievementRateLog);
            }

            // バルクインサート
            if ($key % $bulkSize === 0) {
                $this->flush();
                $this->clear();
            }
        }

        $this->flush();
        $this->clear();
    }

    /**
     * 目標進捗率ログ作成（ユーザ）
     *
     * @param integer $bulkSize バルクサイズ
     * @return void
     */
    private function createUsersLog(int $bulkSize)
    {
        // 対象となる会社を全て抽出
        $mUserRepos = $this->getMUserRepository();
        $userInfoArray = $mUserRepos->getAllUsersWithAchievementRate();

        $userInfoArrayCount = count($userInfoArray);
        foreach ($userInfoArray as $key => $userInfo) {

            if ($key === 0) {
                // 初回ループ

                $tAchievementRateLog = new TAchievementRateLog();
                $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                $tAchievementRateLog->setOwnerUserId($userInfo['userId']);
                $tAchievementRateLog->setTimeframeId($userInfo['timeframeId']);

                $achievementRateArray = array();
                $achievementRateArray[] = $userInfo['achievementRate'];
            } else {
                // 初回と最終以外のループ

                if ($userInfo['userId'] == $userId) {
                    // ユーザIDが前回ループ時と同じ場合

                    $achievementRateArray[] = $userInfo['achievementRate'];
                } else {
                    // ユーザIDが前回ループ時と異なる場合

                    // 集計した達成率の平均値を設定
                    $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                    // 永続化
                    $this->persist($tAchievementRateLog);


                    $tAchievementRateLog = new TAchievementRateLog();
                    $tAchievementRateLog->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                    $tAchievementRateLog->setOwnerUserId($userInfo['userId']);
                    $tAchievementRateLog->setTimeframeId($userInfo['timeframeId']);

                    $achievementRateArray = array();
                    $achievementRateArray[] = $userInfo['achievementRate'];
                }
            }

            // ユーザID保持
            $userId = $userInfo['userId'];

            // 最終ループ
            if ($key === ($userInfoArrayCount - 1)) {
                // 集計した達成率の平均値を設定
                $tAchievementRateLog->setAchievementRate(floor((array_sum($achievementRateArray) / count($achievementRateArray)) * 10) / 10);

                // 永続化
                $this->persist($tAchievementRateLog);
            }

            // バルクインサート
            if ($key % $bulkSize === 0) {
                $this->flush();
                $this->clear();
            }
        }

        $this->flush();
        $this->clear();
        $this->close();
    }

    /**
     * 目標進捗率ログ作成
     *
     * @param integer $bulkSize バルクサイズ
     * @return int EXITコード
     */
    public function run(int $bulkSize): int
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            // 目標進捗率ログ作成（会社）
            $this->createCompaniesLog($bulkSize);
            $this->logDebug('会社目標進捗率ログ作成完了');

            // 目標進捗率ログ作成（グループ）
            $this->createGroupsLog($bulkSize);
            $this->logDebug('グループ目標進捗率ログ作成完了');

            // 目標進捗率ログ作成（ユーザ）
            $this->createUsersLog($bulkSize);
            $this->logDebug('ユーザ目標進捗率ログ作成完了');

            $this->commit();

        } catch (\Exception $e) {
            $this->rollback();
            $this->logAlert('DB登録エラーが発生したためロールバックします');
            return DBConstant::EXIT_CODE_ERROR;
        }

        return DBConstant::EXIT_CODE_SUCCESS;
    }
}
