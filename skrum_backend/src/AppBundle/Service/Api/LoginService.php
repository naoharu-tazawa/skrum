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
     * ログイン
     *
     * @param $emailAddress Eメールアドレス
     * @param $password パスワード
     * @return string JWT
     */
    public function login($emailAddress, $password)
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

        // JWT発行
        $jwt = $this->issueJwt($mUserArray[0]->getUserId(), $mUserArray[0]->getCompany()->getCompanyId(), $mUserArray[0]->getRoleId());

        return $jwt;
    }

    /**
     * 新規ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @return string JWT
     */
    public function signup($password, $urltoken)
    {
        // URLトークン
        $tPreUserRepos = $this->getTPreUserRepository();

        // URLトークンが仮登録ユーザテーブルに登録済みかチェック
        $tPreUser = $tPreUserRepos->getSignupPreUserToken($urltoken);
        if (empty($tPreUser)) {
            throw new AuthenticationException('URLトークンが無効です');
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
            // 会社マスタにレコード追加
            $mCompany = new MCompany();
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
        $jwt = $this->issueJwt($mUser->getUserId(), $mCompany->getCompanyId(), $mUser->getRoleId());

        return $jwt;
    }

    /**
     * 追加ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @return string JWT
     */
    public function join($password, $urltoken)
    {
        // URLトークン
        $tPreUserRepos = $this->getTPreUserRepository();

        // URLトークンが仮登録ユーザテーブルに登録済みかチェック
        $tPreUser = $tPreUserRepos->getAdditionalPreUserToken($urltoken);
        if (empty($tPreUser)) {
            throw new AuthenticationException('URLトークンが無効です');
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
        $jwt = $this->issueJwt($mUser->getUserId(), $mCompany->getCompanyId(), $mUser->getRoleId());

        return $jwt;
    }

    /**
     * JWTを発行
     *
     * @param $userId ユーザID
     * @param $companyId 会社ID
     * @param $companyId ロールID
     * @return string JWT
     */
    private function issueJwt($userId, $companyId, $roleId)
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
                "uid" => $userId,                  // ユーザID
                "cid" => $companyId,               // 会社ID
                "rid" => $roleId,                  // ロールID
                "rlv" => $mRole->getLevel(),       // ロールレベル
                "permissions" => $permissionArray  // 権限情報
        );

        return JWT::encode($token, $key);
    }
}
