<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Entity\TPreUser;
use AppBundle\Exception\SystemException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Utils\DBConstant;
use AppBundle\Exception\ApplicationException;

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
     * @param $subdomain サブドメイン
     * @param $companyId 会社ID（ユーザ招待の場合のみ）
     * @param $roleAssignmentId ロール割当ID（ユーザ招待の場合のみ）
     * @return boolean 登録結果
     */
    public function preregisterUser($emailAddress, $subdomain, $companyId = null, $roleAssignmentId = null)
    {
        // ユーザテーブルに同一Eメールアドレスの登録がないか確認
        $mUserRepos = $this->getMUserRepository();
        $result = $mUserRepos->findBy(array('emailAddress' => $emailAddress));
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
        // 会社IDが存在する場合（ユーザ招待の場合）のみ、会社IDとロール割当IDをセット
        if ($companyId) {
            $tPreUser->setCompanyId($companyId);

            // ロール割当ID存在チェック
            $mRoleAssignmentRepos = $this->getMRoleAssignmentRepository();
            $mRoleAssignmentArray = $mRoleAssignmentRepos->findBy(array(
                                        'roleAssignmentId' => $roleAssignmentId,
                                        'companyId' => $companyId
                                    ));
            if (count($mRoleAssignmentArray) === 0) {
                throw new ApplicationException('ロール割当IDが存在しません');
            }

            $tPreUser->setRoleAssignmentId($roleAssignmentId);
        } else {
            $tPreUser->setInitialUserFlg(DBConstant::FLG_TRUE);
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
     * トークン取得
     *
     * @return string トークン
     */
    private function getToken()
    {
        return hash('sha256',uniqid(rand(),1));
    }
}
