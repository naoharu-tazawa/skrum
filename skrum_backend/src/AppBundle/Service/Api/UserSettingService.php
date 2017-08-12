<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\AuthenticationException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\NoDataException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\Constant;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MRoleAssignment;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TPreUser;
use AppBundle\Entity\TTimeframe;
use AppBundle\Api\ResponseDTO\RoleDTO;

/**
 * ユーザ設定サービスクラス
 *
 * @author naoharu.tazawa
 */
class UserSettingService extends BaseService
{
    /**
     * ユーザ仮登録
     *
     * @param string $emailAddress Eメールアドレス
     * @param string $subdomain サブドメイン
     * @return void
     */
    public function preregisterUser(string $emailAddress, string $subdomain)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress, 'archivedFlg' => DBConstant::FLG_FALSE));
        if (count($result) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // URLトークンを生成
        $urltoken = $this->getToken();

        // 仮登録ユーザテーブルに登録
        $tPreUser = new TPreUser();
        $tPreUser->setEmailAddress($emailAddress);
        $tPreUser->setSubdomain($subdomain);
        $tPreUser->setUrltoken($urltoken);
        $tPreUser->setInitialUserFlg(DBConstant::FLG_TRUE);

        try {
            $this->persist($tPreUser);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // Eメール本文テンプレート埋め込み変数配列の設定
        $data = array();
        $data['subdomain'] = $subdomain;
        $data['urltoken'] = $urltoken;
        $data['supportAddress'] = $this->getParameter('support_address');

        // Eメール非同期送信
        $this->sendEmail(
                $this->getParameter('from_address'),
                $emailAddress,
                $this->getParameter('email_subject_new_user_registration'),
                'mail/new_user_registration.txt.twig',
                $data
            );
    }

    /**
     * ユーザ招待
     *
     * @param Auth $auth 認証情報
     * @param string $emailAddress Eメールアドレス
     * @param MRoleAssignment $mRoleAssignment ロール割当エンティティ
     * @return void
     */
    public function inviteUser(Auth $auth, string $emailAddress, MRoleAssignment $mRoleAssignment)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress, 'archivedFlg' => DBConstant::FLG_FALSE));
        if (count($result) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // ロールがスーパー管理者ユーザの場合、スーパー管理者ユーザ数をチェック
        $this->checkSuperAdminUserCount($mRoleAssignment->getRoleLevel(), $auth->getCompanyId());

        // URLトークンを生成
        $urltoken = $this->getToken();

        // 仮登録ユーザテーブルに登録
        $tPreUser = new TPreUser();
        $tPreUser->setEmailAddress($emailAddress);
        $tPreUser->setSubdomain($auth->getSubdomain());
        $tPreUser->setUrltoken($urltoken);
        $tPreUser->setCompanyId($auth->getCompanyId());
        $tPreUser->setRoleAssignmentId($mRoleAssignment->getRoleAssignmentId());

        try {
            $this->persist($tPreUser);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // Eメール本文テンプレート埋め込み変数配列の設定
        $data = array();
        $data['subdomain'] = $auth->getSubdomain();
        $data['urltoken'] = $urltoken;
        $data['supportAddress'] = $this->getParameter('support_address');

        // Eメール非同期送信
        $this->sendEmail(
                $this->getParameter('from_address'),
                $emailAddress,
                $this->getParameter('email_subject_additional_user_registration'),
                'mail/additional_user_registration.txt.twig',
                $data
            );
    }

    /**
     * Eメール非同期送信
     *
     * @param string $fromAddress Fromアドレス
     * @param string $toAddress Toアドレス
     * @param string $subject 件名
     * @param string $bodyTemplatePath 本文テンプレートパス
     * @param array $data テンプレート埋め込み変数配列
     * @return void
     */
    private function sendEmail(string $fromAddress, string $toAddress, string $subject, string $bodyTemplatePath, array $data)
    {
        // Eメールスプール
        $message = \Swift_Message::newInstance()
            ->setFrom($fromAddress)
            ->setTo($toAddress)
            ->setSubject($subject)
            ->setBody(
                $this->renderView(
                        $bodyTemplatePath,
                        ['data' => $data]
                        )
                );

        $result = $this->getContainer()->get('mailer')->send($message);

        // メールスプール結果判定
        if (!$result) {
            throw new SystemException('メールの送信に失敗しました');
        }

        // スプールしたEメールを送信するコマンドを非同期実行
        exec('php ../app/console swiftmailer:spool:send > /dev/null &');
    }

    /**
     * トークン取得
     *
     * @return string トークン
     */
    private function getToken(): string
    {
        return hash('sha256',uniqid(rand(),1));
    }

    /**
     * 初期設定登録
     *
     * @param Auth $auth 認証情報
     * @param array $userInfo ユーザ情報連想配列
     * @param array $companyInfo 会社情報連想配列
     * @param array $timeframeInfo タイムフレーム情報連想配列
     * @return void
     */
    public function establishCompany(Auth $auth, array $userInfo, array $companyInfo, array $timeframeInfo)
    {
        // 二重登録回避
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($auth->getCompanyId());
        if ($mCompany->getCompanyName() !== null) {
            throw new DoubleOperationException('既に初期設定登録されています');
        }

        // 会社名に'/'(スラッシュ)が入っている場合除外する
        $companyInfo['name'] = str_replace('/', '', $companyInfo['name']);

        // トランザクション開始
        $this->beginTransaction();

        try {
            // ユーザ情報を登録
            $mUserRepos = $this->getMUserRepository();
            $mUser = $mUserRepos->findOneBy(array('userId' => $auth->getUserId()));
            $mUser->setLastName($userInfo['lastName']);
            $mUser->setFirstName($userInfo['firstName']);
            $mUser->setPosition($userInfo['position']);
            if (array_key_exists('phoneNumber', $userInfo)) $mUser->setPhoneNumber($userInfo['phoneNumber']);
            $this->persist($mUser);

            // 会社情報を登録
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->findOneBy(array('companyId' => $auth->getCompanyId()));
            $mCompany->setCompanyName($companyInfo['name']);
            if (array_key_exists('vision', $companyInfo)) $mCompany->setVision($companyInfo['vision']);
            if (array_key_exists('mission', $companyInfo)) $mCompany->setMission($companyInfo['mission']);
            $mCompany->setDefaultDisclosureType($companyInfo['defaultDisclosureType']);
            $this->persist($mCompany);

            // グループマスタに登録されている会社レコードの会社名を変更
            $mGroupRepos = $this->getMGroupRepository();
            $mGroup = $mGroupRepos->findOneBy(array('company' => $auth->getCompanyId(), 'companyFlg' => DBConstant::FLG_TRUE));
            $mGroup->setGroupName($companyInfo['name']);

            // グループツリーテーブルに登録されている会社レコードのグループパス名を変更
            $tGroupTreeRepos = $this->getTGroupTreeRepository();
            $tGroupTreeArray = $tGroupTreeRepos->findBy(array('group' => $mGroup->getGroupId()), array('id' => 'ASC'), 1);
            $tGroupTreeArray[0]->setGroupTreePathName($companyInfo['name'] . '/');

            // タイムフレームを登録
            if ($timeframeInfo['customFlg']) {
                /* カスタム設定の場合 */

                // 開始年月日を生成
                $startYearMonthDate = $timeframeInfo['startDate'];
                // 終了年月日を生成
                $endYearMonthDate = $timeframeInfo['endDate'];

                // 「開始日 <= 終了日」であるかチェック
                if ($startYearMonthDate > $endYearMonthDate) {
                    throw new ApplicationException('終了日は開始日以降に設定してください');
                }

                $tTimeframe = new TTimeframe();
                $tTimeframe->setCompany($mCompany);
                $tTimeframe->setTimeframeName($timeframeInfo['timeframeName']);
                $tTimeframe->setStartDate(DateUtility::transIntoDatetime($startYearMonthDate));
                $tTimeframe->setEndDate(DateUtility::transIntoDatetime($endYearMonthDate));
                $tTimeframe->setDefaultFlg(DBConstant::FLG_TRUE);
                $this->persist($tTimeframe);
            } else {
                /* 標準タイムフレームを使用する場合 */

                // サイクル種別から作成レコード数と間隔月数を取得
                switch ($timeframeInfo['cycleType']) {
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_MONTH:
                        $cycleType = array('i' => 12, 'months' => 1);
                        break;
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_QUARTER:
                        $cycleType = array('i' => 4, 'months' => 3);
                        break;
                    case DBConstant::TIMEFRAME_CYCLE_TYPE_HALF:
                        $cycleType = array('i' => 2, 'months' => 6);
                        break;
                    default:
                        $cycleType = array('i' => 1, 'months' => 12);
                }

                // サイクル種別に応じた回数ループ
                for ($i = 0; $i < $cycleType['i']; ++$i) {
                    // 開始日
                    if ($i === 0) {
                        $startYearMonthDate = $timeframeInfo['startDate'];
                    } else {
                        $startYearMonthDate = DateUtility::get1stOfXMonthDateString($startDateArray[0], $startDateArray[1], $cycleType['months']);
                    }

                    // 開始日を分解
                    $startDateArray = DateUtility::analyzeDate($startYearMonthDate);

                    // 終了日
                    $endYearMonthDate = DateUtility::getEndOfXMonthDateString($startDateArray[0], $startDateArray[1], $cycleType['months'] - 1);

                    // 終了日を分解
                    $endDateArray = DateUtility::analyzeDate($endYearMonthDate);

                    // タイムフレーム名を作成
                    $timeframeName = sprintf(
                                        Constant::NORMAL_TIMEFRAME_NAME_FORMAT,
                                        $startDateArray[0],
                                        $startDateArray[1],
                                        $endDateArray[0],
                                        $endDateArray[1]
                                    );

                    $tTimeframe = new TTimeframe();
                    $tTimeframe->setCompany($mCompany);
                    $tTimeframe->setTimeframeName($timeframeName);
                    $tTimeframe->setStartDate(DateUtility::transIntoDatetime($startYearMonthDate));
                    $tTimeframe->setEndDate(DateUtility::transIntoDatetime($endYearMonthDate));
                    if ($i === 0) {
                        $tTimeframe->setDefaultFlg(DBConstant::FLG_TRUE);
                    }
                    $this->persist($tTimeframe);
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
            $this->rollback();
        }
    }

    /**
     * 追加ユーザ初期設定登録
     *
     * @param Auth $auth 認証情報
     * @param array $userInfo ユーザ情報連想配列
     * @return void
     */
    public function establishUser(Auth $auth, array $userInfo)
    {
        // 二重登録回避
        $mUserRepos = $this->getMUserRepository();
        $mUser = $mUserRepos->findOneBy(array('userId' => $auth->getUserId()));
        if ($mUser->getLastName() !== null) {
            throw new DoubleOperationException('既に初期設定登録されています');
        }

        // ユーザ情報を登録
        $mUser->setLastName($userInfo['lastName']);
        $mUser->setFirstName($userInfo['firstName']);
        $mUser->setPosition($userInfo['position']);
        $mUser->setPhoneNumber($userInfo['phoneNumber']);

        try {
            $this->persist($mUser);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * パスワードリセット
     *
     * @param Auth $auth 認証情報
     * @param MUser $mUser ユーザエンティティ
     * @return void
     */
    public function resetPassword(Auth $auth, MUser $mUser)
    {
        // 自ユーザのパスワードリセットは不可
        if ($mUser->getUserId() === $auth->getUserId()) {
            throw new ApplicationException('自ユーザのパスワードリセットはできません');
        }

        // ランダムパスワード生成
        $randomPassword =  null;
        while (!preg_match('/^(?=.*?[a-zA-Z])(?=.*?[0-9])[a-zA-Z0-9]{8,20}$/', $randomPassword)) {
            $randomPassword = $this->generateRamdomPassword();
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT, array('cost' => 12));

        // トランザクション開始
        $this->beginTransaction();

        try {
            // パスワード更新
            $mUser->setPassword($hashedPassword);

            // Eメール本文テンプレート埋め込み変数配列の設定
            $data = array();
            $data['password'] = $randomPassword;
            $data['subdomain'] = $auth->getSubdomain();
            $data['supportAddress'] = $this->getParameter('support_address');

            // Eメール送信
            $this->sendEmail(
                    $this->getParameter('from_address'),
                    $mUser->getEmailAddress(),
                    $this->getParameter('password_reset'),
                    'mail/password_reset.txt.twig',
                    $data
                    );

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ランダムパスワード生成
     *
     * @return string ランダムパスワード
     */
    private function generateRamdomPassword(): string
    {
        $data = 'abcdefghkmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ234567823456782345678234567823456782345678';
        $length = strlen($data);
        $ret = '';
        for ($i = 0; $i < 15; ++$i) {
            $ret .= $data[mt_rand(0, $length - 1)];
        }

        return $ret;
    }

    /**
     * パスワード変更
     *
     * @param Auth $auth 認証情報
     * @param string $currentPassword 現在パスワード
     * @param string $newPassword 新パスワード
     * @return void
     */
    public function changePassword(Auth $auth, string $currentPassword, string $newPassword)
    {
        // 現在パスワードと新パスワードが同一の場合、更新処理を行わない
        if ($currentPassword === $newPassword) {
            return;
        }
        // 対象ユーザをユーザテーブルから取得
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->findBy(array('userId' => $auth->getUserId()));
        if (count($mUserArray) === 0) {
            throw new NoDataException('対象ユーザは存在しません');
        }

        // パスワード検証
        if (!password_verify($currentPassword, $mUserArray[0]->getPassword())) {
            throw new AuthenticationException('パスワードが一致しません');
        }

        // 新パスワードをハッシュ化
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, array('cost' => 12));

        // パスワード更新
        $mUserArray[0]->setPassword($hashedPassword);

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * ロール一覧取得
     *
     * @param integer $companyId 会社ID
     * @return array
     */
    public function getRoles(int $companyId): array
    {
        // スーパー管理者ユーザ数を取得
        $mUserRepos = $this->getMUserRepository();
        $superAdminUserCount = $mUserRepos->getSuperAdminUserCount($companyId);

        // ロール一覧取得
        $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
        if ($superAdminUserCount < 2) {
            // スーパー管理者ユーザ登録数が1人の場合
            $mRoleAssignmentArray = $mRoleAssignmentRepos->getRoles($companyId, true);
        } else {
            // スーパー管理者ユーザが既に2人登録済みの場合
            $mRoleAssignmentArray = $mRoleAssignmentRepos->getRoles($companyId, false);
        }

        // DTOに詰め替える
        $roleDTOArray = array();
        foreach ($mRoleAssignmentArray as $mRoleAssignment) {
            $roleDTO = new RoleDTO();
            $roleDTO->setRoleAssignmentId($mRoleAssignment->getRoleAssignmentId());
            if ($mRoleAssignment->getRoleLevel() == DBConstant::ROLE_LEVEL_NORMAL) {
                $roleDTO->setRoleName(DBConstant::ROLE_DISPLAY_NAME_NORMAL);
            } elseif ($mRoleAssignment->getRoleLevel() == DBConstant::ROLE_LEVEL_ADMIN) {
                $roleDTO->setRoleName(DBConstant::ROLE_DISPLAY_NAME_ADMIN);
            } else {
                $roleDTO->setRoleName(DBConstant::ROLE_DISPLAY_NAME_SUPERADMIN);
            }

            $roleDTOArray[] = $roleDTO;
        }

        return $roleDTOArray;
    }

    /**
     * ユーザ権限更新
     *
     * @param Auth $auth 認証情報
     * @param MUser $mUser ユーザエンティティ
     * @param MRoleAssignment $mRoleAssignment ロール割当エンティティ
     * @return void
     */
    public function changeRole(Auth $auth, MUser $mUser, MRoleAssignment $mRoleAssignment)
    {
        // 現在のロール割当IDと変更後のロール割当IDが同一の場合、更新処理を行わない
        if ($mUser->getRoleAssignment()->getRoleAssignmentId() === $mRoleAssignment->getRoleAssignmentId()) {
            return;
        }

        // 変更後ロールがスーパー管理者ユーザの場合、スーパー管理者ユーザ数をチェック
        $this->checkSuperAdminUserCount($mRoleAssignment->getRoleLevel(), $auth->getCompanyId());

        $this->beginTransaction();

        try {
            // ユーザ権限更新
            $mUser->setRoleAssignment($mRoleAssignment);

            // メール通知設定を変更
            $tEmailSettingsRepos = $this->getTEmailSettingsRepository();
            $tEmailSettings = $tEmailSettingsRepos->findOneBy(array('userId' => $mUser->getUserId()));
            if ($mRoleAssignment->getRoleLevel() >= DBConstant::ROLE_LEVEL_ADMIN) {
                $tEmailSettings->setReportMemberAchievement(DBConstant::EMAIL_REPORT_MEMBER_ACHIEVEMENT);
                $tEmailSettings->setReportGroupAchievement(DBConstant::EMAIL_OFF);
                $tEmailSettings->setReportFeedbackTarget(DBConstant::EMAIL_REPORT_FEEDBACK_TARGET);
            } else {
                $tEmailSettings->setReportMemberAchievement(DBConstant::EMAIL_OFF);
                $tEmailSettings->setReportGroupAchievement(DBConstant::EMAIL_REPORT_GROUP_ACHIEVEMENT);
                $tEmailSettings->setReportFeedbackTarget(DBConstant::EMAIL_OFF);
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * スーパー管理者ユーザ数チェック
     *
     * @param integer $roleLevel ロールレベル
     * @param string $companyId 会社ID
     * @return void
     */
    private function checkSuperAdminUserCount(int $roleLevel, string $companyId)
    {
        // 変更後ロールがスーパー管理者ユーザの場合、スーパー管理者ユーザ数を取得
        if ($roleLevel >= DBConstant::ROLE_LEVEL_SUPERADMIN) {
            $mUserRepos = $this->getMUserRepository();
            $superAdminUserCount = $mUserRepos->getSuperAdminUserCount($companyId);

            // スーパー管理者ユーザが既に2人登録済みの場合、更新不可
            if ($superAdminUserCount >= 2) {
                throw new ApplicationException('スーパー管理者ユーザは2人までしか登録できません');
            }
        }
    }
}
