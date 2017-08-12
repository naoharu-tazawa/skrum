<?php

namespace AppBundle\Repository;

use AppBundle\Utils\DBConstant;

/**
 * TUploadControlリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TUploadControlRepository extends BaseRepository
{
    /**
     * アップロードエラーデータ取得
     *
     * @param integer $uploadControlId アップロードコントロールID
     * @param string $companyId 会社ID
     * @param string $uploadType アップロード種別
     * @return array
     */
    public function getUploadErrorData(int $uploadControlId, string $companyId, string $uploadType): array
    {
        $qb = $this->createQueryBuilder('tuc');
        $qb->select('tu')
            ->innerJoin('AppBundle:TUpload', 'tu', 'WITH', 'tuc.id = tu.uploadControl')
            ->where('tuc.id = :uploadControlId')
            ->andWhere('tuc.companyId = :companyId')
            ->andWhere('tuc.uploadType = :uploadType')
            ->andWhere('tu.result = :result')
            ->setParameter('uploadControlId', $uploadControlId)
            ->setParameter('companyId', $companyId)
            ->setParameter('uploadType', $uploadType)
            ->setParameter('result', DBConstant::FLG_TRUE);

        return $qb->getQuery()->getResult();
    }

    /**
     * アップロード成功データ取得
     *
     * @param integer $uploadControlId アップロードコントロールID
     * @param string $companyId 会社ID
     * @param string $uploadType アップロード種別
     * @return array
     */
    public function getUploadSuccessData(int $uploadControlId, string $companyId, string $uploadType): array
    {
        $qb = $this->createQueryBuilder('tuc');
        $qb->select('tu')
            ->innerJoin('AppBundle:TUpload', 'tu', 'WITH', 'tuc.id = tu.uploadControl')
            ->where('tuc.id = :uploadControlId')
            ->andWhere('tuc.companyId = :companyId')
            ->andWhere('tuc.uploadType = :uploadType')
            ->andWhere('tu.result = :result')
            ->setParameter('uploadControlId', $uploadControlId)
            ->setParameter('companyId', $companyId)
            ->setParameter('uploadType', $uploadType)
            ->setParameter('result', DBConstant::FLG_FALSE);

        return $qb->getQuery()->getResult();
    }
}
