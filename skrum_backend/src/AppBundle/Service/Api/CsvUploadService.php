<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TUpload;
use AppBundle\Entity\TUploadControl;
use AppBundle\Repository\MUserRepository;

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
     * 前回ユーザID
     */
    private $previousUserId = null;

    /**
     * 追加ユーザCSV登録
     *
     * @param Auth $auth 認証情報
     * @param string $fileContent CSVファイル内容
     * @return void
     */
    public function registerAdditionalusersCsv(Auth $auth, string $fileContent): array
    {
        // 二重登録チェック
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadControlArray = $tUploadControlRepos->findBy(array('companyId' => $auth->getCompanyId(), 'uploadType' => DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS));
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
            $checkResult = $this->checkAdditionalusersCsv($items, ($key + 1));

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
     * 追加ユーザCSVチェック
     *
     * @param array $items 1行分CSV要素配列
     * @param integer $number ループ回数
     * @return boolean チェック結果
     */
    private function checkAdditionalusersCsv(array $items, int $number): bool
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

    /**
     * OKR登録CSV登録
     *
     * @param Auth $auth 認証情報
     * @param string $fileContent CSVファイル内容
     * @return void
     */
    public function registerOkrsCsv(Auth $auth, string $fileContent): array
    {
        // 二重登録チェック
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadControlArray = $tUploadControlRepos->findBy(array('companyId' => $auth->getCompanyId(), 'uploadType' => DBConstant::UPLOAD_TYPE_OKRS));
        if (count($tUploadControlArray) !== 0) {
            $this->message = 'ファイルは既に登録済みです';
            $result['error'] = $this->message;

            return $result;
        }

        // CSVファイルを改行コードで分割し配列に格納
        $replacedFileContent = str_replace(array("\r\n","\r"), "\n", $fileContent);
        $lines = explode("\n", $replacedFileContent);

        // 1行ごとにCSVチェック
        $mUserRepos = $this->getMUserRepository();
        foreach ($lines as $key => $line) {
            // カンマ区切りでCSV1行を分割し、配列に格納
            $items = explode(",", $line);
            $checkResult = $this->checkOkrsCsv($auth, $items, ($key + 1), $mUserRepos);

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
                $tUploadControl->setUploadType(DBConstant::UPLOAD_TYPE_OKRS);
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
     * OKR登録CSVチェック
     *
     * @param Auth $auth 認証情報
     * @param array $items 1行分CSV要素配列
     * @param integer $number ループ回数
     * @param MUserRepository $mUserRepos MUserリポジトリ
     * @return boolean チェック結果
     */
    private function checkOkrsCsv(Auth $auth, array $items, int $number, MUserRepository $mUserRepos): bool
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

        // ユーザ存在チェック
        try {
            $mUser = $this->getDBExistanceLogic()->checkUserExistance($items[1], $auth->getCompanyId());
        } catch (\Exception $e) {
            $this->message = $number . '行目：ユーザが存在しません';

            return false;
        }

        // 名前(姓)一致チェック
        if ($items[2] !== $mUser->getLastName()) {
            $this->message = $number . '行目：名前(姓)が一致しません';

            return false;
        }

        // 名前(名)一致チェック
        if ($items[3] !== $mUser->getFirstName()) {
            $this->message = $number . '行目：名前(名)が一致しません';

            return false;
        }

        // タイムフレーム存在チェック
        try {
            $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($items[4], $auth->getCompanyId());
        } catch (\Exception $e) {
            $this->message = $number . '行目：タイムフレームが存在しません';

            return false;
        }

        // OKR種別が正しいかチェック（目標＝'1'、キーリザルト＝'2'）
        if ($items[5] !== DBConstant::OKR_TYPE_OBJECTIVE && $items[5] !== DBConstant::OKR_TYPE_KEY_RESULT) {
            $this->message = $number . '行目：OKR種別が不正です';

            return false;
        }

        // ユーザが切り替わった時にOKR種別が'1'であるかチェック
        if ($this->previousUserId !== null) {
            if ($items[1] !== $this->previousUserId && $items[5] !== DBConstant::OKR_TYPE_OBJECTIVE) {
                $this->message = $number . '行目：キーリザルトのみを登録することはできません';

                return false;
            }
        }
        $this->previousUserId = $items[1];

        // OKR名が120文字以内かチェック
        if (mb_strlen($items[6]) > 120) {
            $this->message = $number . '行目：OKR名は120文字までしか指定できません';

            return false;
        }

        // 開始日の妥当性チェック
        if ($items[9] !== '') {
            $startDate = str_replace('/', '-', $items[9]);
            $startDateArray = DateUtility::analyzeDate($startDate);
            if (!checkdate($startDateArray[1], $startDateArray[2], $startDateArray[0])) {
                $this->message = $number . '行目：開始日が不正です';

                return false;
            }
        }

        // 期限日の妥当性チェック
        if ($items[10] !== '') {
            $endDate = str_replace('/', '-', $items[10]);
            $endDateArray = DateUtility::analyzeDate($endDate);
            if (!checkdate($endDateArray[1], $endDateArray[2], $endDateArray[0])) {
                $this->message = $number . '行目：期限日が不正です';

                return false;
            }
        }

        // 開始日と期限日の妥当性チェック
        if ($items[9] !== '' || $items[10] !== '') {
            if ($items[9] === '') {
                $startDate = DateUtility::transIntoDateString($tTimeframe->getStartDate());
            }
            if ($items[10] === '') {
                $endDate = DateUtility::transIntoDateString($tTimeframe->getEndDate());
            }

            if (strtotime($startDate) > strtotime($endDate)) {
                $this->message = $number . '行目：期限日は開始日以降に設定してください';

                return false;
            }
        }

        // OKR公開種別が正しいかチェック（全体公開＝'1'、グループ公開＝'2'、管理者公開＝'3'、グループ管理者公開＝'4'）
        if ($items[11] !== DBConstant::OKR_DISCLOSURE_TYPE_OVERALL && $items[11] !== DBConstant::OKR_DISCLOSURE_TYPE_GROUP
                && $items[11] !== DBConstant::OKR_DISCLOSURE_TYPE_ADMIN && $items[11] !== DBConstant::OKR_DISCLOSURE_TYPE_GROUP_ADMIN) {
            $this->message = $number . '行目：OKR公開種別が不正です';

            return false;
        }

        return true;
    }
}
