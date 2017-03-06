<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;
use AppBundle\Form\Data\User;

/**
 * サンプル用のコントローラ
 *
 * @author naoharu.tazawa
 */
class SampleController extends BaseController
{
    /**
     *　ユーザテーブルからユーザを取得しjsonで返す
     *　メソッド名の定義に注意してください。メソッド名から自動的にurlとpost,get等が決定されます
     *　アノテーションはいりません
     */
    public function getSamplesAction(Request $request)
    {
//         $form = $this->createForm(new UserType(), $user = new User(), [
//                 'csrf_protection' => false,
//         ]);

//         $form->handleRequest($request);

//         if (!$form->isValid()) {
//             return $user;
//         }

        // ログを出力
        // ログ出力はBaseControllerに定義しているので継承元のメソッドを呼んでください
        $this->logDebug('サンプルdebugログ in Controller', array('cause' => 'in_hurry'));
        $this->logInfo('サンプルinfoログ in Controller', array('cause' => 'in_hurry'));
        $this->logWarning('サンプルwarningログ in Controller', array('cause' => 'in_hurry'));
        $this->logError('サンプルerrorログ in Controller', array('cause' => 'in_hurry'));
        $this->logCritical('サンプルcriticalログ in Controller', array('cause' => 'in_hurry'));
        $this->logAlert('サンプルalertログ in Controller', array('cause' => $this->getParameter('sample_parmeter_controller'))); // ymlに定義したparameterはこのように呼んでください

        // Sampleサービスの生成
        $sampleService = $this->getSampleService(); // 新しくサービスクラスを作成したらBaseControllerに定義しこのように取得してください
        $userId = 2;
        $userList = $sampleService->getUser($userId);

        // リストを渡せば自動的にJsonで返してくれるようにymlで設定してあります
        return $userList;
    }
}
