<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DBConstant;
use AppBundle\Utils\Constant;
use AppBundle\Entity\TUpload;
use AppBundle\Entity\TUploadControl;

/**
 * CSVアップロードサービスクラス
 *
 * @author naoharu.tazawa
 */
class CsvUploadService extends BaseService
{
    /**
     * 返却メッセージ
     */
    private $message = null;

    /**
     * CSV登録
     *
     * @param Auth $auth 認証情報
     * @param string $fileContent CSVファイル内容
     * @return void
     */
    public function registerCsv(Auth $auth, string $fileContent): array
    {
        // 二重登録チェック
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadControlArray = $tUploadControlRepos->findBy(array('companyId' => $auth->getCompanyId()));
        if (count($tUploadControlArray) !== 0) {
            $this->message = 'ファイルは既に登録済みです';
            $result['error'] = $this->message;

            return $result;
        }

        // CSVファイルを改行コードで分割し配列に格納
        $replacedFileContent = str_replace(array("\r\n","\r"), "\n", $fileContent);
        $lines = explode("\n", $replacedFileContent);

        // 1行ごとにCSVチェック
        foreach ($lines as $key => $line) {
            // カンマ区切りでCSV1行を分割し、配列に格納
            $items = explode(",", $line);
            $checkResult = $this->checkCsv($items, ($key + 1));

            if (!$checkResult) {
                break;
            }
        }

        // DB登録
        if ($this->message === null) {
            // トランザクション開始
            $this->beginTransaction();

            try {
                // アップロード管理テーブルに登録
                $tUploadControl = new TUploadControl();
                $tUploadControl->setCompanyId($auth->getCompanyId());
                $tUploadControl->setUploadType(DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS);
                $tUploadControl->setCount(count($lines));
                $tUploadControl->setUploadUserId($auth->getUserId());
                $this->persist($tUploadControl);
                $this->flush();

                // アップロードテーブルに登録
                foreach ($lines as $key => $line) {
                    $tUpload = new TUpload();
                    $tUpload->setUploadControl($tUploadControl);
                    $tUpload->setLineNumber($key + 1);
                    $tUpload->setLineData($line);
                    $tUpload->setBatchExecutionStatus(DBConstant::BATCH_EXECUTION_STATUS_NOT_EXECUTED);
                    $this->persist($tUpload);
                }

                $this->flush();

                $this->commit();
            } catch (\Exception $e) {
                $this->rollback();
                throw new SystemException($e->getMessage());
            }
        }

        // 返却値を設定
        $result = array();
        if ($this->message === null) {
            $result['result'] = 'OK';
        } else {
            $result['error'] = $this->message;
        }

        return $result;
    }

    /**
     * CSVチェック
     *
     * @param array $items 1行分CSV要素配列
     * @param integer $number ループ回数
     * @return boolean チェック結果
     */
    private function checkCsv(array $items, int $number): bool
    {
        // 1行12項目あるかチェック
        if (count($items) !== 12) {
            $this->message = $number . '行目：1行には12項目必要です';

            return false;
        }

        // ID(連番)と行数が一致するかチェック
        if ($items[0] != $number) {
            $this->message = $number . '行目：ID(連番)が不正です';

            return false;
        }

        // 名前(姓)空チェック
        if ($items[1] === '') {
            $this->message = $number . '行目：名前(姓)は必須です';

            return false;
        }

        // 名前(名)空チェック
        if ($items[2] === '') {
            $this->message = $number . '行目：名前(名)は必須です';

            return false;
        }

        // ユーザ権限が正しいかチェック（通常ユーザ＝'1'、管理者ユーザ＝'2'、スーパー管理者ユーザ＝'3'）
        if ($items[3] !== Constant::ROLE_NORMAL && $items[3] !== Constant::ROLE_ADMIN && $items[3] !== Constant::ROLE_SUPERADMIN) {
            $this->message = $number . '行目：ユーザ権限が不正です';

            return false;
        }

        // メールアドレスの形式が合っているかチェック
        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $items[5])) {
            $this->message = $number . '行目：Eメールアドレスの形式が不正です';

            return false;
        }

        // 1行に同じ所属部署名が2つ以上ないかチェック
        $departmentArray = array(
                empty($items[7]) ? 0 : $items[7],
                empty($items[8]) ? 1 : $items[8],
                empty($items[9]) ? 2 : $items[9],
                empty($items[10]) ? 3 : $items[10],
                empty($items[11]) ? 4 : $items[11]
            );
        if (count($departmentArray) !== count(array_unique($departmentArray))) {
            $this->message = $number . '行目：所属部署名が重複しています';

            return false;
        }

        return true;
    }
}
