<?php

namespace AppBundle\Repository;

/**
 * TUploadリポジトリクラス
 *
 * @author naoharu.tazawa
 */
class TUploadRepository extends BaseRepository
{
    /**
     * アップロードテーブルの仮パスワードを削除し、レコードも削除
     *
     * @param integer $uploadControlId アップロード管理ID
     * @return void
     */
    public function deleteTemporaryPasswordAndItsRecord(int $uploadControlId)
    {
        $sql = <<<SQL
        UPDATE t_upload AS t0_
        SET t0_.temporary_password = NULL, t0_.deleted_at = NOW()
        WHERE (t0_.upload_control_id = :uploadControlId) AND (t0_.deleted_at IS NULL);
SQL;

        $params['uploadControlId'] = $uploadControlId;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute($params);
    }
}
