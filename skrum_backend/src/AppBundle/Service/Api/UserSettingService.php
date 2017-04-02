<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Entity\TPreUser;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MUser;
use AppBundle\Entity\MCompany;
use AppBundle\Entity\MGroup;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\TGroupTree;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\AuthenticationException;
use AppBundle\Exception\InvalidParameterException;

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
     * @param $emailAddress Eメールアドレス
     * @param $urltoken URLトークン
     * @param $companyId 会社ID（ユーザ招待の場合のみ）
     * @param $roleId ロールID（ユーザ招待の場合のみ）
     * @return boolean 登録結果
     */
    public function preregisterUser($emailAddress, $urltoken, $companyId = null, $roleId = null)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
        if (count($result) > 0) {
            throw new DoubleOperationException('Eメールアドレスはすでに登録済みです');
        }

        // 仮登録ユーザテーブルに登録
        $tPreUser = new TPreUser();
        $tPreUser->setEmailAddress($emailAddress);
        $tPreUser->setUrltoken($urltoken);
        // 会社IDが存在する場合（ユーザ招待の場合）のみ、会社IDとロールIDをセット
        if ($companyId) {
            $tPreUser->setInitialUserFlg(0);
            $tPreUser->setCompanyId($companyId);
            $tPreUser->setRoleId($roleId);
        } else {
            $tPreUser->setInitialUserFlg(1);
        }

        try {
            $this->persist($tPreUser);
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        $url = "https://skrum.jp/signup" . "?tkn=" . $urltoken;

        $message = \Swift_Message::newInstance()
            ->setFrom('skrum@skrum.jp')
            ->setTo($emailAddress)
            ->setSubject('新規ユーザ登録メール送信APIテスト件名')
            ->setBody('メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。' . $url);

        $mailer = $this->getContainer()->get('mailer');
        $result = $mailer->send($message);

        $transport = $mailer->getTransport();
        $spool = $transport->getSpool();
        $spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));

        // メールを送信
        if ($result) {
            return true;
        } else {
            $this->logError("メールの送信に失敗しました");

            return false;
        }
    }

    /**
     * 新規ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @return void
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
        $result = $mUserRepos->findBy(array('emailAddress' => $tPreUser->getEmailAddress()));
        if (count($result) > 0) {
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
            $mUser->setRoleId('A0001');
            $this->persist($mUser);

            // 仮登録ユーザテーブルのURLトークンを無効にする
            $tPreUser->setInvalidFlg(1);

            $this->flush();
            $this->commit();

        } catch(\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 追加ユーザ登録
     *
     * @param $password パスワード
     * @param $urltoken URLトークン
     * @return void
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
        $result = $mUserRepos->findBy(array('emailAddress' => $tPreUser->getEmailAddress()));
        if (count($result) > 0) {
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
    }
}
