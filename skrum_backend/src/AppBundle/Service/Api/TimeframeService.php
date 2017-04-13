<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Api\ResponseDTO\NestedObject\TimeframeDTO;
use AppBundle\Utils\DBConstant;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\TTimeframe;
use AppBundle\Exception\ApplicationException;
use AppBundle\Utils\DateUtility;

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
     * @param string $companyId 会社ID
     * @return array
     */
    public function getTimeframes($companyId)
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
            if ($tTimeframe->getDefaultFlg() === null) {
                $timeframeDTO->setDefaultFlg(DBConstant::FLG_FALSE);
            } else {
                $timeframeDTO->setDefaultFlg($tTimeframe->getDefaultFlg());
            }
            $timeframeDTOArray[] = $timeframeDTO;
        }

        return $timeframeDTOArray;
    }

    /**
     * デフォルトタイムフレーム変更
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param \AppBundle\Entity\TTimeframe $tTimeframe タイムフレームエンティティ
     * @return void
     */
    public function changeDefault($auth, $tTimeframe)
    {
        // 現在デフォルトに設定されているタイムフレームエンティティを取得
        $tTimeframeRepos = $this->getTTimeframeRepository();
        $defaultTimeframe = $tTimeframeRepos->findOneBy(array('company' => $auth->getCompanyId(), 'defaultFlg' => DBConstant::FLG_TRUE));

        // 選択されたタイムフレームが既にデフォルトに設定されている場合、更新処理をしない
        if ($tTimeframe->getTimeframeId() == $defaultTimeframe->getTimeframeId()) {
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
        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * タイムフレーム登録
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function registerTimeframe($auth, $data)
    {
        // 開始日妥当性チェック
        if (!checkdate($data['start']['month'], $data['start']['date'], $data['start']['year'])) {
            throw new ApplicationException('開始日が不正です');
        }

        // 終了日妥当性チェック
        if (!checkdate($data['end']['month'], $data['end']['date'], $data['end']['year'])) {
            throw new ApplicationException('終了日が不正です');
        }

        // 月日をゼロ埋め
        $data['start']['month'] = sprintf("%02d", $data['start']['month']);
        $data['start']['date'] = sprintf("%02d", $data['start']['date']);
        $data['end']['month'] = sprintf("%02d", $data['end']['month']);
        $data['end']['date'] = sprintf("%02d", $data['end']['date']);

        // 開始日と終了日を大小比較
        $startDate = DateUtility::transIntoDatetime($data['start']['year'] . $data['start']['month'] . $data['start']['date']);
        $endDate = DateUtility::transIntoDatetime($data['end']['year'] . $data['end']['month'] . $data['end']['date']);
        if ($startDate > $endDate) {
            throw new ApplicationException('終了日は開始日以降に設定してください');
        }

        // 会社エンティティ取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());

        // タイムフレーム新規登録
        $tTimeframe = new TTimeframe();
        $tTimeframe->setCompany($mCompany);
        $tTimeframe->setTimeframeName($data['timeframeName']);
        $tTimeframe->setStartDate($startDate);
        $tTimeframe->setEndDate($endDate);

        try {
            $this->persist($tTimeframe);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
