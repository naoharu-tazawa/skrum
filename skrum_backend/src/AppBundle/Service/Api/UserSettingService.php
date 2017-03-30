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
     * @return boolean 登録結果
     */
    public function preregisterUser($emailAddress, $urltoken)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
        if (count($result) > 0){
            $this->logDebug('Eメールアドレスはすでに登録済みです');

            return false;
        }

        // 仮登録ユーザテーブルに登録
        $tPreUser = new TPreUser();
        $tPreUser->setEmailAddress($emailAddress);
        $tPreUser->setUrltoken($urltoken);
        $tPreUser->setInitialUserFlg(1);
        try {
            $this->getEntityManager()->persist($tPreUser);
            $this->getEntityManager()->flush();
        } catch(\Exception $e) {
            throw new SystemException("ユーザ仮登録でDBエラーが発生しました");
        }

        $url = "https://skrum.jp/signup" . "?tkn=" . $urltoken;

        $message = \Swift_Message::newInstance()
            ->setFrom('skrum@skrum.jp')
            ->setTo($emailAddress)
            ->setSubject('新規ユーザ登録メール送信APIテスト件名')
            ->setBody('メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。' . $url);

        $mailer = $this->getContainer()->get('mailer');
        $result = $mailer->send($message);

//         $transport = $mailer->getTransport();
//         if (!$transport instanceof \Swift_Transport_SpoolTransport) {
//             return;
//         }

//         $spool = $transport->getSpool();
//         if (!$spool instanceof \Swift_MemorySpool) {
//             return;
//         }

//         $spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));

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
     * @return boolean 登録結果
     */
    public function signup($password, $urltoken)
    {
        // URLトークン
        $tPreUserRepos = $this->getTPreUserRepository();

        // URLトークンが仮登録ユーザテーブルに登録済みかチェック
        $tPreUser = $tPreUserRepos->checkUrltoken($urltoken);
        if (empty($tPreUser)) {
            $this->logDebug('URLトークンが無効です');

            return false;
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => 10));

        // トランザクション開始
        $this->beginTransaction();

        try{
            // 会社マスタにレコード追加
            $mCompany = new MCompany();
            $this->getEntityManager()->persist($mCompany);

            // グループマスタにレコード追加
            $mGroup = new MGroup();
            $mGroup->setCompany($mCompany);
            $mGroup->setGroupType(DBConstant::GROUP_TYPE_COMPANY);
            $mGroup->setCompanyFlg(1);
            $this->getEntityManager()->persist($mGroup);$this->getEntityManager()->flush();

            // グループツリーテーブルにレコード追加
            $tGroupTree = new TGroupTree();
            $tGroupTree->setGroup($mGroup);
            $tGroupTree->setGroupTreePath($mGroup->getGroupId() . '/');
            $this->getEntityManager()->persist($tGroupTree);

            // ユーザマスタにレコード追加
            $mUser = new MUser();
            $mUser->setCompany($mCompany);
            $mUser->setEmailAddress($tPreUser->getEmailAddress());
            $mUser->setPassword($hashedPassword);
            $mUser->setRoleId('A0001');
            $this->getEntityManager()->persist($mUser);

            // 仮登録ユーザテーブルのURLトークンを無効にする
            $tPreUser->setInvalidFlg(1);

            $this->getEntityManager()->flush();
            $this->commit();

        } catch(\Doctrine\DBAL\DBALException $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        return true;
    }
}
