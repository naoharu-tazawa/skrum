<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SampleController extends BaseController
{
    /**
     * @Route("/sample", name="sample")
     */
    public function indexAction(Request $request)
    {
        // ログを出力
        $this->logDebug('サンプルdebugログ in Controller', array('cause' => 'in_hurry'));
        $this->logInfo('サンプルinfoログ in Controller', array('cause' => 'in_hurry'));
        $this->logWarning('サンプルwarningログ in Controller', array('cause' => 'in_hurry'));
        $this->logError('サンプルerrorログ in Controller', array('cause' => 'in_hurry'));
        $this->logCritical('サンプルcriticalログ in Controller', array('cause' => 'in_hurry'));
        $this->logAlert('サンプルalertログ in Controller', array('cause' => $this->getParameter('sample_parmeter_controller')));

        // Sampleサービスの生成
        $sampleService = $this->getSampleService();
        $string = $sampleService->getString();
        return new Response(
                var_dump($string)
                );
    }
}
