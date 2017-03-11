<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Entity\MUser;
use AppBundle\Entity\MCompany;

/**
 * サンプル用のサービスクラス
 *
 * @author naoharu.tazawa
 */
class SampleService extends BaseService
{
    /**
     * ユーザ取得
     *
     * @return array ユーザ情報
     */
    public function getUser($userId)
    {
        // ログを出力
        // Controllerの場合と同じく、BaseServiceに定義してあるものをんでください
        $this->logDebug('サンプルdebugログ in Service');
        $this->logInfo('サンプルinfoログ in Service');
        $this->logWarning('サンプルwarningログ in Service');
        $this->logError('サンプルerrorログ in Service');
        $this->logCritical('サンプルcriticalログ in Service');
        $this->logAlert('サンプルalertログ in Service', ['cause' => $this->getParameter('sample_parmeter_service')]);

        $userRepos = $this->getMUserRepository();
        //$user = $this->getUserRepository()->findOneByUserId(2);
        $user = $userRepos->getUser($userId);
        return $user;
    }

    /**
     * ユーザ取得
     *
     * @return array ユーザ情報
     */
    public function getAllUsers()
    {
        $userRepos = $this->getMUserRepository();
        $users = $userRepos->findAll();
        return $users;
    }


    /**
     * ユーザ登録
     *
     * @return array ユーザ情報
     */
    public function insertUser()
    {
        $mUser = new MUser();
        $mUser->setEmailAddress('nohr.est@gmail.com');
        $mUser->setFirstName('尚治');
        $mUser->setLastName('田澤');
        $mUser->setPhoneNumber('090-2301-8847');
        $mUser->setPosition('部長');
        $mUser->setPassword('863895480');
        $mUser->setImagePath('/images/company/');
        $mUser->setRoleId('1');
        $company = new MCompany();
        $company->setCompanyName('skm company');
        $mUser->setCompany($company);

        $em = $this->getEntityManager();
        try {
            $em->persist($company);
            $em->persist($mUser);
            $em->flush();
            return array('result' => 'OK');
        } catch (\Exception $e) {
            return array('result' => 'NG');
        }
    }

    /**
     * ユーザ削除
     *
     * @return array ユーザ情報
     */
    public function deleteUser($userId)
    {
        $userRepos = $this->getMUserRepository();
        $user = $userRepos->findOneBy(['userId' => $userId], ['userId' => 'ASC']);
        try {
            $userRepos->remove($user);
            $userRepos->flush();
            return array('result' => 'OK');
        } catch (\Exception $e) {
            return array('result' => 'NG');
        }
    }
}
