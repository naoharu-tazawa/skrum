<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Constant;

/**
 * OKRアクティビティサービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrActivityService extends BaseService
{
    /**
     * OKR最終更新日時を取得
     *
     * @param string $subjectType 主体種別
     * @param integer $userId 取得対象ユーザID
     * @param integer $groupId 取得対象グループID
     * @param integer $companyId 会社ID
     * @param integer $timeframeId タイムフレームID
     * @return mixed
     */
    public function getLastUpdate(string $subjectType, int $userId = null, int $groupId = null, int $companyId = null, int $timeframeId)
    {
        // OKRアクティビティリポジトリを取得
        $tOkrActivityRepos = $this->getTOkrActivityRepository();

        if ($subjectType == Constant::SUBJECT_TYPE_USER) {
            $lastUpdate = $tOkrActivityRepos->getLatestActivityDatetime($userId, null, null, $timeframeId);
        } elseif ($subjectType == Constant::SUBJECT_TYPE_GROUP) {
            $lastUpdate = $tOkrActivityRepos->getLatestActivityDatetime(null, $groupId, null, $timeframeId);
        } else {
            $lastUpdate = $tOkrActivityRepos->getLatestActivityDatetime(null, null, $companyId, $timeframeId);
        }

        if (count($lastUpdate) === 0) {
            return null;
        }

        return $lastUpdate[0]['activityDatetime'];
    }
}
