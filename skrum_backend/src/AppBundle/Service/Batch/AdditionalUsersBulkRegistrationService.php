<?php

namespace AppBundle\Service\Batch;

use AppBundle\Service\BaseService;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TEmailReservation;
use AppBundle\Entity\TEmailSettings;
use AppBundle\Entity\TGroupMember;
use AppBundle\Entity\TGroupTree;
use AppBundle\Entity\TUpload;
use AppBundle\Entity\TUploadControl;

/**
 * ユーザ一括追加登録サービスクラス
 *
 * @author naoharu.tazawa
 */
class AdditionalUsersBulkRegistrationService extends BaseService
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
     * ユーザ/グループ登録
     *
     * @param TUpload $tUpload アップロードエンティティ
     * @param MCompany $mCompany 会社エンティティ
     * @param MGroup $mGroupOfCompany 会社のグループエンティティ
     * @param array $roleAssignmentEntityArray ロール割当エンティティ配列
     * @return boolean 登録結果
     */
    private function registerUserAndGroups(TUpload $tUpload, MCompany $mCompany, MGroup $mGroupOfCompany, array $roleAssignmentEntityArray): bool
    {
        // CSV1行分のデータを取得
        $line = $tUpload->getLineData();

        // カンマ区切りでCSV1行を分割し、配列に格納
        $items = explode(",", $line);

        try {
            // ユーザテーブルに登録
            $userRegistrationResult = $this->registerUser($items, $mCompany, $roleAssignmentEntityArray, $tUpload);
            if (!$userRegistrationResult) {
                return false;
            }

            // 先ほど登録したユーザエンティティを取得
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->findOneBy(array('emailAddress' => $items[5], 'archivedFlg' => DBConstant::FLG_FALSE));

            // グループテーブルに登録
            $this->registerGroups($items, $mUser, $mCompany, $mGroupOfCompany);

            // メール通知設定テーブルにレコード追加
            $this->registerEmailSettings($mUser);
        } catch (\Exception $e) {
            $this->message = $this->message . 'DB登録エラーが発生しました。';
            $this->result = DBConstant::FLG_TRUE;
            $this->exitCode = DBConstant::EXIT_CODE_RETRY;

            return false;
        }

        return true;
    }

    /**
     * ユーザ登録
     *
     * @param array $items CSV1行分の要素配列
     * @param MCompany $mCompany 会社エンティティ
     * @param array $roleAssignmentEntityArray ロール割当エンティティ配列
     * @param TUpload $tUpload アップロードエンティティ
     * @return boolean 登録結果
     */
    private function registerUser(array $items, MCompany $mCompany, array $roleAssignmentEntityArray, TUpload $tUpload): bool
    {
        $mUserRepos = $this->getMUserRepository();

        // ユーザ権限チェック（スーパー管理者ユーザ）
        if ($items[3] === Constant::ROLE_SUPERADMIN) {
            $superAdminUserCount = $mUserRepos->getSuperAdminUserCount($mCompany->getCompanyId());

            // スーパー管理者ユーザが既に2人登録済みの場合、更新不可
            if ($superAdminUserCount >= 2) {
                $this->message = $this->message . 'スーパー管理者ユーザは2人までしか登録できません。';
                $this->result = DBConstant::FLG_TRUE;
                $this->exitCode = DBConstant::EXIT_CODE_RETRY;

                return false;
            }
        }

        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserArray = $mUserRepos->findBy(array('emailAddress' => $items[5], 'archivedFlg' => DBConstant::FLG_FALSE));
        if (count($mUserArray) > 0) {
            $this->message = $this->message . 'Eメールアドレスはすでに登録済みです。';
            $this->result = DBConstant::FLG_TRUE;
            $this->exitCode = DBConstant::EXIT_CODE_RETRY;

            return false;
        }

        // 仮パスワードを生成
        $randomPassword = $this->generateRandomPassword();

        // アップロードテーブルに仮パスワードを登録
        $tUpload->setTemporaryPassword($randomPassword);

        // パスワードをハッシュ化
        $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT, array('cost' => 12));

        // ユーザマスタにレコード追加
        $mUser = new MUser();
        $mUser->setCompany($mCompany);
        $mUser->setLastName($items[1]);
        $mUser->setFirstName($items[2]);
        $mUser->setEmailAddress($items[5]);
        $mUser->setPassword($hashedPassword);
        if ($items[3] === Constant::ROLE_NORMAL) {
            $mUser->setRoleAssignment($roleAssignmentEntityArray['normal']);
        } elseif ($items[3] === Constant::ROLE_ADMIN) {
            $mUser->setRoleAssignment($roleAssignmentEntityArray['admin']);
        } elseif ($items[3] === Constant::ROLE_SUPERADMIN) {
            $mUser->setRoleAssignment($roleAssignmentEntityArray['superadmin']);
        }
        $mUser->setPosition($items[4]);
        $mUser->setPhoneNumber($items[6]);
        $this->persist($mUser);

        $this->flush();

        return true;
    }

    /**
     * ランダムパスワード取得
     *
     * @return void
     */
    private function generateRandomPassword()
    {
        // ランダムパスワード生成
        $randomPassword =  null;
        while (!preg_match('/^(?=.*?[a-zA-Z])(?=.*?[0-9])[a-zA-Z0-9]{8,20}$/', $randomPassword)) {
            $data = 'abcdefghkmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ234567823456782345678234567823456782345678';
            $length = strlen($data);
            $randomPassword = '';
            for ($i = 0; $i < 15; ++$i) {
                $randomPassword .= $data[mt_rand(0, $length - 1)];
            }
        }

        return $randomPassword;
    }

    /**
     * グループ登録
     *
     * @param array $items CSV1行分の要素配列
     * @param MUser $mUser ユーザエンティティ
     * @param MCompany $mCompany 会社エンティティ
     * @param MGroup $mGroupOfCompany 会社のグループエンティティ
     * @return void
     */
    private function registerGroups(array $items, MUser $mUser, MCompany $mCompany, MGroup $mGroupOfCompany)
    {
        // 所属部署名を配列に格納
        $departmentArray = array(
                empty($items[7]) ? null : $items[7],
                empty($items[8]) ? null : $items[8],
                empty($items[9]) ? null : $items[9],
                empty($items[10]) ? null : $items[10],
                empty($items[11]) ? null : $items[11]
        );

        // グループ名でグループ存在検索
        $departmentIdArray = array();
        $mGroupRepos = $this->getMGroupRepository();
        foreach ($departmentArray as $key => $departmentName) {
            if ($departmentName !== null) {
                // グループ名に'/'(スラッシュ)が入っている場合除外する。また、先頭および末尾にある半角スペースを取り除く。
                $departmentName = str_replace('/', '', $departmentName);
                $departmentName = trim($departmentName);
                $departmentArray[$key] = $departmentName;

                $mGroupArray = $mGroupRepos->findBy(array('company' => $mCompany->getCompanyId(), 'groupName' => $departmentName), array('groupId' => 'ASC'), 1);

                // グループが存在しない場合は新規登録
                if (empty($mGroupArray)) {
                    // グループマスタにレコード追加
                    $mGroup = new MGroup();
                    $mGroup->setCompany($mCompany);
                    $mGroup->setGroupName($departmentName);
                    $mGroup->setGroupType(DBConstant::GROUP_TYPE_DEPARTMENT);
                    $mGroup->setCompanyFlg(DBConstant::FLG_FALSE);
                    $this->persist($mGroup);
                    $this->flush();

                    $departmentIdArray[] = $mGroup->getGroupId();
                } else {
                    $mGroup = $mGroupArray[0];

                    $departmentIdArray[] = $mGroupArray[0]->getGroupId();
                }

                // グループメンバーテーブルにレコード追加
                $tGroupMember = new TGroupMember();
                $tGroupMember->setGroup($mGroup);
                $tGroupMember->setUser($mUser);
                $this->persist($tGroupMember);

                $this->flush();

                // グループツリー登録
                $this->registerGroupPath($mGroup, $mGroupOfCompany, $departmentArray, $departmentIdArray);
            }
        }
    }

    /**
     * グループツリー登録
     *
     * @param MGroup $mGroup グループエンティティ
     * @param MGroup $mGroupOfCompany 会社のグループエンティティ
     * @param array $departmentArray 部署名配列
     * @param array $departmentIdArray 部署ID配列
     * @return void
     */
    private function registerGroupPath(MGroup $mGroup, MGroup $mGroupOfCompany, array $departmentArray, array $departmentIdArray)
    {
        // 登録グループツリーパス
        $newGroupTreePath = $mGroupOfCompany->getGroupId() . '/';
        $newGroupTreePathName = $mGroupOfCompany->getGroupName() . '/';
        foreach ($departmentIdArray as $key => $departmentId) {
            $newGroupTreePath .= $departmentId . '/';
            $newGroupTreePathName .= $departmentArray[$key] . '/';
        }

        // 登録するグループツリーパスが既に登録されているかチェック
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTree = $tGroupTreeRepos->getByGroupTreePath($newGroupTreePath);
        if ($tGroupTree != null) {
            return;
        }

        // グループツリー登録
        $tGroupTree = new TGroupTree();
        $tGroupTree->setGroup($mGroup);
        $tGroupTree->setGroupTreePath($newGroupTreePath);
        $tGroupTree->setGroupTreePathName($newGroupTreePathName);
        $this->persist($tGroupTree);

        $this->flush();
    }

    /**
     * メール通知設定テーブルにレコード追加
     *
     * @param MUser $mUser ユーザエンティティ
     * @return void
     */
    private function registerEmailSettings(MUser $mUser)
    {
        $tEmailSettings = new TEmailSettings();
        $tEmailSettings->setUserId($mUser->getUserId());
        if ($mUser->getRoleAssignment()->getRoleLevel() >= DBConstant::ROLE_LEVEL_ADMIN) {
            $tEmailSettings->setReportGroupAchievement(DBConstant::EMAIL_OFF);
        } else {
            $tEmailSettings->setReportMemberAchievement(DBConstant::EMAIL_OFF);
            $tEmailSettings->setReportFeedbackTarget(DBConstant::EMAIL_OFF);
        }
        $this->persist($tEmailSettings);

        $this->flush();
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
        $tUploadArray = $tUploadControlRepos->getUploadErrorData($tUploadControl->getId(), $tUploadControl->getCompanyId(), DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS);

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
     * 成功メール作成・登録
     *
     * @param TUploadControl $tUploadControl アップロード管理エンティティ
     * @return void
     */
    private function createSuccessEmail(TUploadControl $tUploadControl)
    {
        // アップロード登録者エンティティ取得
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->find($tUploadControl->getUploadUserId());

        // 登録成功したアップロードエンティティ配列を取得
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadArray = $tUploadControlRepos->getUploadSuccessData($tUploadControl->getId(), $tUploadControl->getCompanyId(), DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS);

        // 成功データがない場合、処理終了
        if (count($tUploadArray) === 0) {
            return;
        }

        $data = array();
        $data['userName'] = $mUser->getLastName() . ' ' . $mUser->getFirstName();
        foreach ($tUploadArray as $key => $tUpload) {
            $tUpload->getLineData();
            $data['items'][$key]['line'] = $tUpload->getLineData();
            $data['items'][$key]['password'] = $tUpload->getTemporaryPassword();
        }

        // メール送信予約テーブルに登録
        $tEmailReservation = new TEmailReservation();
        $tEmailReservation->setToEmailAddress($mUser->getEmailAddress());
        $tEmailReservation->setTitle($this->getParameter('file_registration_success'));
        $tEmailReservation->setBody($this->renderView('mail/file_registration_success.txt.twig', ['data' => $data]));
        $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
        $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
        $this->persist($tEmailReservation);

        $this->flush();
    }

    /**
     * 社員への通知メール作成・登録
     *
     * @param TUploadControl $tUploadControl アップロード管理エンティティ
     * @param string $subdomain サブドメイン
     * @return void
     */
    private function createEmailToEmployees(TUploadControl $tUploadControl, string $subdomain)
    {
        // 登録成功したアップロードエンティティ配列を取得
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadArray = $tUploadControlRepos->getUploadSuccessData($tUploadControl->getId(), $tUploadControl->getCompanyId(), DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS);

        // 成功データがない場合、処理終了
        if (count($tUploadArray) === 0) {
            return;
        }

        foreach ($tUploadArray as $tUpload) {
            // CSV1行分のデータを取得
            $line = $tUpload->getLineData();

            // カンマ区切りでCSV1行を分割し、配列に格納
            $items = explode(",", $line);

            $data = array();
            $data['userName'] = $items[1] . ' ' . $items[2];
            $data['emailAddress'] = $items[5];
            $data['password'] = $tUpload->getTemporaryPassword();
            $data['subdomain'] = $subdomain;
            $data['supportAddress'] = $this->getParameter('support_address');

            // メール送信予約テーブルに登録
            $tEmailReservation = new TEmailReservation();
            $tEmailReservation->setToEmailAddress($items[5]);
            $tEmailReservation->setTitle($this->getParameter('email_subject_additional_user_registration'));
            $tEmailReservation->setBody($this->renderView('mail/additional_user_bulk_registration.txt.twig', ['data' => $data]));
            $tEmailReservation->setReceptionDatetime(DateUtility::getCurrentDatetime());
            $tEmailReservation->setSendingReservationDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tEmailReservation);
        }

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
     * ユーザ一括追加登録
     *
     * @param integer $mailSending 対社員メール送信フラグ
     * @return int EXITコード
     */
    public function run(int $mailSending): int
    {
        $this->exitCode = DBConstant::EXIT_CODE_SUCCESS;

        // 一括登録情報を取得
        $tUploadControlRepos = $this->getTUploadControlRepository();
        $tUploadControlArray = $tUploadControlRepos->findBy(array('uploadType' => DBConstant::UPLOAD_TYPE_ADDITIONNAL_USERS));

        try {
            foreach ($tUploadControlArray as $tUploadControl) {
                // アップロード詳細を取得
                $tUploadRepos = $this->getTUploadRepository();
                $tUploadArray = $tUploadRepos->findBy(array('uploadControl' => $tUploadControl->getId()));

                // 会社エンティティを取得
                $mCompanyRepos = $this->getMCompanyRepository();
                $mCompany = $mCompanyRepos->find($tUploadControl->getCompanyId());

                // 会社のグループIDを取得
                $mGroupRepos = $this->getMGroupRepository();
                $mGroupOfCompany = $mGroupRepos->findOneBy(array('company' => $mCompany->getCompanyId(), 'companyFlg' => DBConstant::FLG_TRUE));

                // ロール割当IDを取得
                $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
                $mRoleAssignmentArray = $mRoleAssignmentRepos->findBy(array('companyId' => $mCompany->getCompanyId()));
                $roleAssignmentEntityArray = array();
                foreach ($mRoleAssignmentArray as $mRoleAssignment) {
                    if ($mRoleAssignment->getRoleLevel() === DBConstant::ROLE_LEVEL_SUPERADMIN) {
                        $roleAssignmentEntityArray['superadmin'] = $mRoleAssignment;
                    } elseif ($mRoleAssignment->getRoleLevel() === DBConstant::ROLE_LEVEL_ADMIN) {
                        $roleAssignmentEntityArray['admin'] = $mRoleAssignment;
                    } elseif ($mRoleAssignment->getRoleLevel() === DBConstant::ROLE_LEVEL_NORMAL) {
                        $roleAssignmentEntityArray['normal'] = $mRoleAssignment;
                    }
                }

                foreach ($tUploadArray as $key => $tUpload) {
                    // 変数を初期化
                    $this->message = null;
                    $this->result = DBConstant::FLG_FALSE;

                    // トランザクション開始
                    $this->beginTransaction();

                    try {
                        // CSV1行分の情報（新規ユーザ情報・所属グループ情報）登録
                        $registrationResult = $this->registerUserAndGroups($tUpload, $mCompany, $mGroupOfCompany, $roleAssignmentEntityArray);

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

                    if ($mailSending === 0) {
                        /* 社員に1人1人にメール送信無し */
                        $this->createSuccessEmail($tUploadControl);
                    } else {
                        /* 社員1人1人にメール送信あり */
                        $this->createEmailToEmployees($tUploadControl, $mCompany->getSubdomain());
                    }

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
