<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;

/**
 * サンプル用のサービスクラス
 *
 * @author naoharu.tazawa
 */
class SampleService extends BaseService
{
    /**
     * DEBUGログ出力
     *
     * @return array ユーザ情報
     */
    public function getUsers()
    {
        // ログを出力
        // Controllerの場合と同じく、BaseServiceに定義してあるものを呼んでください
        $this->logDebug('サンプルdebugログ in Service');
        $this->logInfo('サンプルinfoログ in Service');
        $this->logWarning('サンプルwarningログ in Service');
        $this->logError('サンプルerrorログ in Service');
        $this->logCritical('サンプルcriticalログ in Service');
        $this->logAlert('サンプルalertログ in Service', array('cause' => $this->getParameter('sample_parmeter_service')));

        $userRepos = $this->getUserRepository();
        //$user = $this->getUserRepository()->findOneByUserId(2);
        $users = $userRepos->getUsers();
        return $users;

        //return $user;
    }
}
