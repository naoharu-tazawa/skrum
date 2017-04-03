<?php

namespace AppBundle\Service\Api;

use \Firebase\JWT\JWT;
use AppBundle\Service\BaseService;
use AppBundle\Entity\MUser;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Entity\TPreUser;
use AppBundle\Exception\AuthenticationException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\PermissionException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\DBConstant;

/**
 * ログインサービスクラス
 *
 * @author naoharu.tazawa
 */
class LoginService extends BaseService
{
    /**
     * サブドメインチェック
     *
     * @param $subdomain サブドメイン
     * @return void
     */
    public function checkSubdomain($subdomain)
    {
        // 会社マスタに対象サブドメインの登録が既にあるかチェック
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->findBy(array('subdomain' => $subdomain));
        if (count($mCompany) !== 0) {
            throw new DoubleOperationException('サブドメインは既に使用されています');
        }
    }

    /**
     * ログイン
     *
     * @param $emailAddress Eメールアドレス
     * @param $password パスワード
     * @param $subdomain サブドメイン
     * @return string JWT
     */
    public function login($emailAddress, $password, $subdomain)
    {
        // 対象ユーザをユーザテーブルから取得
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
        if (count($mUserArray) === 0) {
            throw new AuthenticationException('対象ユーザは存在しません');
        }

        // パスワード検証
        if (!password_verify($password, $mUserArray[0]->getPassword())) {
            throw new AuthenticationException('パスワードが一致しません');
        }

        // サブドメインチェック
        $mCompany = $mUserArray[0]->getCompany();
        if ($subdomain !== $mCompany->getSubdomain()) {
            throw new PermissionException('サブドメインが不正です');
        }

        // JWT発行
        $jwt = $this->issueJwt($mCompany->getSubdomain(), $mUserArray[0]->getUserId(), $mCompany->getCompanyId(), $mUserArray[0]->getRoleId());

        return $jwt;
    }

    /**
     * 新規ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @param $subdomain サブドメイン
     * @return string JWT
     */
    public function signup($password, $urltoken, $subdomain)
    {
        // URLトークン
        $tPreUserRepos = $this->getTPreUserRepository();

        // URLトークンが仮登録ユーザテーブルに登録済みかチェック
        $tPreUser = $tPreUserRepos->getSignupPreUserToken($urltoken);
        if (empty($tPreUser)) {
            throw new AuthenticationException('URLトークンが無効です');
        }

        // サブドメインチェック
        if ($subdomain !== $tPreUser->getSubdomain()) {
            throw new PermissionException('サブドメインが不正です');
        }

        // 会社マスタに同一サブドメインの登録がないか確認
        $this->checkSubdomain($tPreUser->getSubdomain());

        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->findBy(array('emailAddress' => $tPreUser->getEmailAddress()));
        if (count($mUserArray) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        // トランザクション開始
        $this->beginTransaction();

        try{
            // 会社マスタにレコード追加
            $mCompany = new MCompany();
            $mCompany->setSubdomain($tPreUser->getSubdomain());
            $this->persist($mCompany);

            // グループマスタにレコード追加
            $mGroup = new MGroup();
            $mGroup->setCompany($mCompany);
            $mGroup->setGroupType(DBConstant::GROUP_TYPE_COMPANY);
            $mGroup->setCompanyFlg(1);
            $this->persist($mGroup);
            $this->flush();

            // グループツリーテーブルにレコード追加
            $tGroupTree = new TGroupTree();
            $tGroupTree->setGroup($mGroup);
            $tGroupTree->setGroupTreePath($mGroup->getGroupId() . '/');
            $this->persist($tGroupTree);

            // ユーザマスタにレコード追加
            $mUser = new MUser();
            $mUser->setCompany($mCompany);
            $mUser->setEmailAddress($tPreUser->getEmailAddress());
            $mUser->setPassword($hashedPassword);
            $mUser->setRoleId('A003');
            $this->persist($mUser);

            // 仮登録ユーザテーブルのURLトークンを無効にする
            $tPreUser->setInvalidFlg(1);

            $this->flush();
            $this->commit();

        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // JWT発行
        $jwt = $this->issueJwt($mCompany->getSubdomain(), $mUser->getUserId(), $mCompany->getCompanyId(), $mUser->getRoleId());

        return $jwt;
    }

    /**
     * 追加ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @param $subdomain サブドメイン
     * @return string JWT
     */
    public function join($password, $urltoken, $subdomain)
    {
        // URLトークン
        $tPreUserRepos = $this->getTPreUserRepository();

        // URLトークンが仮登録ユーザテーブルに登録済みかチェック
        $tPreUser = $tPreUserRepos->getAdditionalPreUserToken($urltoken);
        if (empty($tPreUser)) {
            throw new AuthenticationException('URLトークンが無効です');
        }

        // サブドメインチェック
        if ($subdomain !== $tPreUser->getSubdomain()) {
            throw new PermissionException('サブドメインが不正です');
        }

        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $mUserArray = $mUserRepos->findBy(array('emailAddress' => $tPreUser->getEmailAddress()));
        if (count($mUserArray) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        // トランザクション開始
        $this->beginTransaction();

        try{
            // 会社IDに紐づく会社エンティティを取得
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->findOneBy(array('companyId' => $tPreUser->getCompanyId()));
            if (empty($mCompany)) {
                throw new ApplicationException('所属会社が存在しません');
            }

            // ユーザマスタにレコード追加
            $mUser = new MUser();
            $mUser->setCompany($mCompany);
            $mUser->setEmailAddress($tPreUser->getEmailAddress());
            $mUser->setPassword($hashedPassword);
            $mUser->setRoleId($tPreUser->getRoleId());
            $this->persist($mUser);

            // 仮登録ユーザテーブルのURLトークンを無効にする
            $tPreUser->setInvalidFlg(1);

            $this->flush();
            $this->commit();

        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // JWT発行
        $jwt = $this->issueJwt($mCompany->getSubdomain(), $mUser->getUserId(), $mCompany->getCompanyId(), $mUser->getRoleId());

        return $jwt;
    }

    /**
     * JWTを発行
     *
     * @param string $subdomain サブドメイン
     * @param integer $userId ユーザID
     * @param integer $companyId 会社ID
     * @param string $companyId ロールID
     * @return string JWT
     */
    private function issueJwt($subdomain, $userId, $companyId, $roleId)
    {
        // 権限取得
        $mRolePermissionRepos = $this->getMRolePermissionRepository();
        $permissions = $mRolePermissionRepos->getPermissions($roleId);

        // 取得した権限情報を配列に変換
        $permissionArray = array();
        foreach ($permissions as $permission) {
            $permissionArray[] = $permission['name'];
        }

        // ロールを取得
        $mRoleRepos = $this->getMRoleRepository();
        $mRole = $mRoleRepos->findOneBy(array('roleId' => $roleId));

        // JWT発行
        $expMinutes = $this->getParameter('jwt_exp_minutes');
        $now = $_SERVER['REQUEST_TIME'];
        $expireUnixTime = $now + ($expMinutes * 60);
        $key = $this->getContainer()->getParameter('secret');
        $token = array(
                "iss" => "accounts.skrum.jp",      // 発行者の識別子
                "iat" => $now,                     // 発行日時
                "exp" => $expireUnixTime,          // 有効期限
                "sdm" => $subdomain,               // サブドメイン
                "uid" => $userId,                  // ユーザID
                "cid" => $companyId,               // 会社ID
                "rid" => $roleId,                  // ロールID
                "rlv" => $mRole->getLevel(),       // ロールレベル
                "permissions" => $permissionArray  // 権限情報
        );

        return JWT::encode($token, $key);
    }
}
