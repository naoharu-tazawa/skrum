<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\AuthenticationException;

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
     * @return boolean 登録結果
     */
    public function login($emailAddress, $password)
    {
        // 対象ユーザをユーザテーブルから取得
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
        if (count($result) === 0) {
            throw new AuthenticationException('対象ユーザは存在しません');
        }

        // パスワード検証
        if (!password_verify($password, $result[0]->getPassword())) {
            throw new AuthenticationException('パスワードが一致しません');
        }
    }
}
