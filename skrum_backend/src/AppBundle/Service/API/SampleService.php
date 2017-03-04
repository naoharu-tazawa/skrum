<?php

namespace AppBundle\Service\API;

use AppBundle\Service\BaseService;

class SampleService extends BaseService
{
    public function getString()
    {
        // ログを出力
        $this->logDebug('サンプルdebugログ in Service');
        $this->logInfo('サンプルinfoログ in Service');
        $this->logWarning('サンプルwarningログ in Service');
        $this->logError('サンプルerrorログ in Service');
        $this->logCritical('サンプルcriticalログ in Service');
        $this->logAlert('サンプルalertログ in Service', array('cause' => $this->getParameter('sample_parmeter_service')));

        //$userRepos = $this->getUserRepository();
        $user = $this->getUserRepository()->findOneByUserId(2);
        //$users = $userRepos->getUsers();
        //return $users;

        return $user;
    }
}
