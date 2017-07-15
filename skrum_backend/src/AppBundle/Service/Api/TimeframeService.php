<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TTimeframe;
use AppBundle\Api\ResponseDTO\TimeframeDetailDTO;
use AppBundle\Api\ResponseDTO\NestedObject\TimeframeDTO;

/**
 * タイムフレームサービスクラス
 *
 * @author naoharu.tazawa
 */
class TimeframeService extends BaseService
{
    /**
     * タイムフレーム取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getTimeframes(int $companyId): array
    {
        // タイムフレーム取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframeArray = $tTimeframeRepos->findBy(array('company' => $companyId), array('timeframeId' => 'DESC'));

        // DTOに詰め替える
        $timeframeDTOArray = array();
        foreach ($tTimeframeArray as $tTimeframe) {
            $timeframeDTO = new TimeframeDTO();
            $timeframeDTO->setTimeframeId($tTimeframe->getTimeframeId());
            $timeframeDTO->setTimeframeName($tTimeframe->getTimeframeName());
            $timeframeDTO->setDefaultFlg($tTimeframe->getDefaultFlg());
            $timeframeDTOArray[] = $timeframeDTO;
        }

        return $timeframeDTOArray;
    }

    /**
     * タイムフレーム詳細取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getTimeframeDetails(int $companyId): array
    {
        // タイムフレーム取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $tTimeframeArray = $tTimeframeRepos->findBy(array('company' => $companyId), array('timeframeId' => 'DESC'));

        // DTOに詰め替える
        $timeframeDetailDTOArray = array();
        foreach ($tTimeframeArray as $tTimeframe) {
            $timeframeDetailDTO = new TimeframeDetailDTO();
            $timeframeDetailDTO->setTimeframeId($tTimeframe->getTimeframeId());
            $timeframeDetailDTO->setTimeframeName($tTimeframe->getTimeframeName());
            $timeframeDetailDTO->setStartDate($tTimeframe->getStartDate());
            $timeframeDetailDTO->setEndDate($tTimeframe->getEndDate());
            $timeframeDetailDTO->setDefaultFlg($tTimeframe->getDefaultFlg());
            $timeframeDetailDTOArray[] = $timeframeDetailDTO;
        }

        return $timeframeDetailDTOArray;
    }

    /**
     * デフォルトタイムフレーム変更
     *
     * @param Auth $auth 認証情報
     * @param TTimeframe $tTimeframe タイムフレームエンティティ
     * @return void
     */
    public function changeDefault(Auth $auth, TTimeframe $tTimeframe)
    {
        // 現在デフォルトに設定されているタイムフレームエンティティを取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $defaultTimeframe = $tTimeframeRepos->findOneBy(array('company' => $auth->getCompanyId(), 'defaultFlg' => DBConstant::FLG_TRUE));

        // 選択されたタイムフレームが既にデフォルトに設定されている場合、更新処理をしない
        if ($tTimeframe->getTimeframeId() === $defaultTimeframe->getTimeframeId()) {
            return;
        }

        // トランザクション開始
        $this->beginTransaction();

        // 更新処理
        $tTimeframe->setDefaultFlg(DBConstant::FLG_TRUE);
        $defaultTimeframe->setDefaultFlg(null);

        try {
            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * タイムフレーム登録
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return TimeframeDetailDTO
     */
    public function registerTimeframe(Auth $auth, array $data): TimeframeDetailDTO
    {
        // 開始日と終了日を大小比較
        if ($data['startDate'] > $data['endDate']) {
            throw new ApplicationException('終了日は開始日以降に設定してください');
        }

        // 会社エンティティ取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());

        // タイムフレーム新規登録
        $tTimeframe = new TTimeframe();
        $tTimeframe->setCompany($mCompany);
        $tTimeframe->setTimeframeName($data['timeframeName']);
        $tTimeframe->setStartDate(DateUtility::transIntoDatetime($data['startDate']));
        $tTimeframe->setEndDate(DateUtility::transIntoDatetime($data['endDate']));

        try {
            $this->persist($tTimeframe);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // レスポンス用DTO生成
        $timeframeDetailDTO = new TimeframeDetailDTO();
        $timeframeDetailDTO->setTimeframeId($tTimeframe->getTimeframeId());
        $timeframeDetailDTO->setTimeframeName($tTimeframe->getTimeframeName());
        $timeframeDetailDTO->setStartDate($tTimeframe->getStartDate());
        $timeframeDetailDTO->setEndDate($tTimeframe->getEndDate());
        $timeframeDetailDTO->setDefaultFlg($tTimeframe->getDefaultFlg());

        return $timeframeDetailDTO;
    }

    /**
     * タイムフレーム更新
     *
     * @param array $data リクエストJSON連想配列
     * @param TTimeframe $tTimeframe タイムフレームエンティティ
     * @return void
     */
    public function updateTimeframe(array $data, TTimeframe $tTimeframe)
    {
        // // 開始日または終了日に変更がある場合、大小比較
        if (array_key_exists('startDate', $data) || array_key_exists('endDate', $data)) {
            // 開始日
            if (array_key_exists('startDate', $data)) {
                $startDate = DateUtility::transIntoDatetime($data['startDate']);
            } else {
                $startDate = $tTimeframe->getStartDate();
            }

            // 終了日
            if (array_key_exists('endDate', $data)) {
                $endDate = DateUtility::transIntoDatetime($data['endDate']);
            } else {
                $endDate = $tTimeframe->getEndDate();
            }

            // 開始日と終了日を大小比較
            if ($startDate > $endDate) {
                throw new ApplicationException('終了日は開始日以降に設定してください');
            }
        }

        // タイムフレーム更新
        if (array_key_exists('timeframeName', $data) && !empty($data['timeframeName'])) {
            $tTimeframe->setTimeframeName($data['timeframeName']);
        }
        if (array_key_exists('startDate', $data) && !empty($data['startDate'])) {
            $tTimeframe->setStartDate($startDate);
        }
        if (array_key_exists('endDate', $data) && !empty($data['endDate'])) {
            $tTimeframe->setEndDate($endDate);
        }

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * タイムフレーム削除
     *
     * @param TTimeframe $tTimeframe タイムフレームエンティティ
     * @return void
     */
    public function deleteTimeframe(TTimeframe $tTimeframe)
    {
        // デフォルトタイムフレームは削除不可
        if ($tTimeframe->getDefaultFlg()) {
            throw new ApplicationException('デフォルトタイムフレームは削除できません');
        }

        try {
            $this->remove($tTimeframe);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
