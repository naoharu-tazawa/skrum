<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Entity\TUpload;
use AppBundle\Entity\TUploadControl;

/**
 * OKR一括登録サービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrsBulkRegistrationService extends BaseService
{
    /**
     * EXITコード
     */
    private $exitCode = null;

    /**
     * DB登録用エラーメッセージ
     */
    private $message = null;

    /**
     * DB登録結果
     */
    private $result = null;

    /**
     * 親OKRエンティティ
     */
    private $parentOkrEntity = null;

    /**
     * OKR登録
     *
     * @param TUpload $tUpload アップロードエンティティ
     * @param MCompany $mCompany 会社エンティティ
     * @return boolean 登録結果
     */
    private function registerOkrs(TUpload $tUpload, MCompany $mCompany): bool
    {
        // CSV1行分のデータを取得
        $line = $tUpload->getLineData();

        // カンマ区切りでCSV1行を分割し、配列に格納
        $items = explode(",", $line);

        try {
            // 新規OKR登録
            $this->createOkr($items, $mCompany);
        } catch (\Exception $e) {
            $this->message = $this->message . 'DB登録エラーが発生しました。';
            $this->result = DBConstant::FLG_TRUE;
            $this->exitCode = DBConstant::EXIT_CODE_RETRY;

            return false;
        }

        return true;
    }

    /**
     * OKR新規登録
     *
     * @param array $items CSV1行をカンマ区切りで分割した配列
     * @param MCompany $mCompany 会社エンティティ
     * @return void
     */
    private function createOkr(array $items, MCompany $mCompany)
    {
        // OKRオーナー種別
        $ownerType = DBConstant::OKR_OWNER_TYPE_USER;

        // タイムフレームエンティティを取得
        $tTimeframe = $this->getDBExistanceLogic()->checkTimeframeExistance($items[4], $mCompany->getCompanyId());

        // ユーザエンティティを取得
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($items[1], $mCompany->getCompanyId());

        $auth = new Auth($mCompany->getSubdomain(), $mUser->getUserId(), $mCompany->getCompanyId(), $mUser->getRoleAssignment()->getRoleId(), $mUser->getRoleAssignment()->getRoleLevel(), array());

        // 親OKRエンティティを更新
        $alignmentFlg = false;
        if ($this->parentOkrEntity !== null) {
            if ($items[5] == DBConstant::OKR_TYPE_OBJECTIVE) {
                $this->parentOkrEntity = null;
            } elseif ($items[5] == DBConstant::OKR_TYPE_KEY_RESULT) {
                $parentOkrEntity = $this->parentOkrEntity;
                $alignmentFlg = true;
            }
        }

        if ($alignmentFlg) {
            // 紐付け先チェック
            $userId = $mUser->getUserId();
            $okrOperationLogic = $this->getOkrOperationLogic();
            $okrOperationLogic->checkAlignment($items[5], $ownerType, $userId, null, $parentOkrEntity);
        }

        // OKR登録
        $tOkr = new TOkr();
        $tOkr->setTimeframe($tTimeframe);
        if ($alignmentFlg) {
            $tOkr->setParentOkr($parentOkrEntity);
        }
        $tOkr->setType($items[5]);
        $tOkr->setName($items[6]);
        $tOkr->setDetail('');
        if ($items[7] !== '') $tOkr->setTargetValue($items[7]);
        $tOkr->setAchievedValue(0);
        $tOkr->setAchievementRate(0);
        if ($items[8] !== '') $tOkr->setUnit($items[8]);
        $tOkr->setOwnerType($ownerType);
        $tOkr->setOwnerUser($mUser);
        $tOkr->setStartDate($tTimeframe->getStartDate());
        $tOkr->setEndDate($tTimeframe->getEndDate());
        $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);
        $tOkr->setDisclosureType($items[9]);
        if ($items[5] === DBConstant::OKR_TYPE_KEY_RESULT) $tOkr->setRatioLockedFlg(DBConstant::FLG_FALSE);
        $this->persist($tOkr);

        // OKRアクティビティ登録（作成）
        $tOkrActivity = new TOkrActivity();
        $tOkrActivity->setOkr($tOkr);
        $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_GENERATE);
        $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
        if ($items[7] !== '') {
            $tOkrActivity->setTargetValue($items[7]);
        } else {
            $tOkrActivity->setTargetValue(100);
        }
        $tOkrActivity->setAchievedValue(0);
        $tOkrActivity->setAchievementRate(0);
        $tOkrActivity->setChangedPercentage(0);
        $this->persist($tOkrActivity);

        // 自動投稿登録（作成）
        if ($tOkr->getType() === DBConstant::OKR_TYPE_OBJECTIVE) {
            $autoPost = $this->getParameter('auto_post_type_generate_o');
        } elseif ($tOkr->getType() === DBConstant::OKR_TYPE_KEY_RESULT) {
            $autoPost = $this->getParameter('auto_post_type_generate_kr');
        }
        $postLogic = $this->getPostLogic();
        $postLogic->autoPostPosterIsUser($auth, $autoPost, $tOkr, $tOkrActivity);

        // OKRアクティビティ登録（紐付け）
        if ($alignmentFlg) {
            if (!($ownerType === DBConstant::OKR_OWNER_TYPE_COMPANY && $items[5] === DBConstant::OKR_TYPE_OBJECTIVE)) {
                $tOkrActivityAlign = new TOkrActivity();
                $tOkrActivityAlign->setOkr($tOkr);
                $tOkrActivityAlign->setType(DBConstant::OKR_OPERATION_TYPE_ALIGN);
                $tOkrActivityAlign->setActivityDatetime(DateUtility::getCurrentDatetime());
                $tOkrActivityAlign->setNewParentOkrId($parentOkrEntity->getOkrId());
                $this->persist($tOkrActivityAlign);
            }
        }

        $this->flush();

        // 達成率を再計算
        $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
        $okrAchievementRateLogic->recalculate($auth, $tOkr, true);

        $this->flush();

        if ($items[5] == DBConstant::OKR_TYPE_OBJECTIVE) {
            $this->parentOkrEntity = $tOkr;
        }
    }

    /**
     * アップロードテーブルを更新
     *
     * @param TUpload $tUpload アップロードエンティティ
     * @return void
     */
    private function updateTUpload(TUpload $tUpload)
    {
        // アップロードテーブルに登録
        $tUpload->setBatchExecutionStatus(DBConstant::BATCH_EXECUTION_STATUS_EXECUTED);
        $tUpload->setResult($this->result);
        if ($this->message !== null) {
            $tUpload->setMessage($this->message);
        }

        $this->flush();
    }

    /**
     * エラーメール作成・登録
     *
     * @param TUploadControl $tUploadControl アップロード管理エンティティ
     * @return void
     */
    private function createErrorEmail(TUploadControl $tUploadControl)
    {
        // アップロード登録者エンティティ取得
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($tUploadControl->getUploadUserId());

        // エラーとなったアップロードエンティティ配列を取得
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadArray = $tUploadControlRepos->getUploadErrorData($tUploadControl->getId(), $tUploadControl->getCompanyId(), DBConstant::UPLOAD_TYPE_OKRS);

        // エラーがない場合、処理終了
        if (count($tUploadArray) === 0) {
            return;
        }

        $data = array();
        $data['userName'] = $mUser->getLastName() . ' ' . $mUser->getFirstName();
        foreach ($tUploadArray as $key => $tUpload) {
            $data['errors'][$key]['line'] = $tUpload->getLineData();
            $data['errors'][$key]['message'] = $tUpload->getMessage();
        }

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('file_registration_error'));
        $tEmailReservation->setBody($this->renderView('mail/file_registration_error.txt.twig', ['data' => $data]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
        $this->persist($tEmailReservation);

        $this->flush();
    }

    /**
     * アップロードテーブルの仮パスワードを削除し、レコードも削除
     *
     * @param TUploadControl $tUploadControl アップロード管理エンティティ
     * @return void
     */
    private function deleteTemporaryPassword(TUploadControl $tUploadControl)
    {
        $tUploadRepos = $this->getTUploadRepository();
        $tUploadRepos->deleteTemporaryPasswordAndItsRecord($tUploadControl->getId());

        $this->flush();
    }

    /**
     * OKR一括追加登録
     *
     * @return int EXITコード
     */
    public function run(): int
    {
        $this->exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // 一括登録情報を取得
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadControlArray = $tUploadControlRepos->findBy(array('uploadType' => DBConstant::UPLOAD_TYPE_OKRS));

        try {
            foreach ($tUploadControlArray as $tUploadControl) {
                // アップロード詳細を取得
                $tUploadRepos = $this->getTUploadRepository();
                $tUploadArray = $tUploadRepos->findBy(array('uploadControl' => $tUploadControl->getId()));

                // 会社エンティティを取得
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($tUploadControl->getCompanyId());

                foreach ($tUploadArray as $key => $tUpload) {
                    // 変数を初期化
                    $this->message = null;
                    $this->result = DBConstant::FLG_FALSE;

                    // トランザクション開始
                    $this->beginTransaction();

                    try {
                        // CSV1行分の情報（登録OKR情報）登録
                        $registrationResult = $this->registerOkrs($tUpload, $mCompany);

                        if (!$registrationResult) {
                            $this->rollback();

                            // トランザクション再開始
                            $this->beginTransaction();
                        }

                        // アップロードテーブルを更新
                        $this->updateTUpload($tUpload);

                        $this->commit();
                    } catch (\Exception $e) {
                        $this->rollback();
                        $this->logAlert('DB登録エラーが発生したためロールバックします');
                        $this->exitCode = DBConstant::EXIT_CODE_RETRY;
                    }
                }

                // トランザクション開始
                $this->beginTransaction();
                try {
                    // メール作成・登録
                    $this->createErrorEmail($tUploadControl);

                    // アップロードテーブルの仮パスワードを削除する
                    $this->deleteTemporaryPassword($tUploadControl);

                    // アップロード管理データを削除
                    $this->remove($tUploadControl);
                    $this->flush();

                    $this->commit();
                } catch (\Exception $e) {
                    $this->rollback();
                    $this->logAlert('DB登録エラーが発生したためロールバックします');
                    $this->exitCode = DBConstant::EXIT_CODE_RETRY;
                }
            }
        } catch (\Exception $e) {
            $this->logAlert('DB登録エラーが発生したためロールバックします');
            $this->exitCode = DBConstant::EXIT_CODE_ERROR;
        }

        return $this->exitCode;
    }
}
