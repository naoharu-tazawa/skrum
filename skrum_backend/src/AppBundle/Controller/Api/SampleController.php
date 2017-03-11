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
     * HTTPメソッド：GET
     * http://localhost:8000/app_dev.php/api/samples/{slug}.json
     *
     *　ユーザテーブルからユーザID={slug}のユーザを取得しjsonで返す
     *　メソッド名の定義に注意してください。メソッド名から自動的にurlとHTTPメソッドが決定されます
     *　アノテーションはいりません
     */
    public function getSampleAction($slug)
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
        $this->logDebug('サンプルdebugログ in Controller', ['cause' => 'in_hurry']);
        $this->logInfo('サンプルinfoログ in Controller', ['cause' => 'in_hurry']);
        $this->logWarning('サンプルwarningログ in Controller', ['cause' => 'in_hurry']);
        $this->logError('サンプルerrorログ in Controller', ['cause' => 'in_hurry']);
        $this->logCritical('サンプルcriticalログ in Controller', ['cause' => 'in_hurry']);
        $this->logAlert('サンプルalertログ in Controller', ['cause' => $this->getParameter('sample_parmeter_controller')]); // ymlに定義したparameterはこのように呼んでください

        // Sampleサービスの生成
        $sampleService = $this->getSampleService(); // 新しくサービスクラスを作成したらBaseControllerに定義しこのように取得してください
        $userList = $sampleService->getUser($slug);

        // リストを渡せば自動的にJsonで返してくれるようにymlで設定してあります
        return $userList;
    }

    /**
     * HTTPメソッド：GET
     * http://localhost:8000/app_dev.php/api/samples.json
     *
     *　ユーザテーブルから複数の（ここでは全ての）ユーザを取得しjsonで返す
     *　メソッド名の定義に注意してください。メソッド名から自動的にurlとHTTPメソッドが決定されます
     *　アノテーションはいりません
     */
    public function getSamplesAction()
    {
        // Sampleサービスの生成
        $sampleService = $this->getSampleService(); // 新しくサービスクラスを作成したらBaseControllerに定義しこのように取得してください
        $userList = $sampleService->getAllUsers();

        // リストを渡せば自動的にJsonで返してくれるようにymlで設定してあります
        return $userList;
    }

    /**
     * HTTPメソッド：PUT
     * http://localhost:8000/app_dev.php/api/samples/{slug}.json
     *
     *　ユーザ新規登録
     *　メソッド名の定義に注意してください。メソッド名から自動的にurlとHTTPメソッドが決定されます
     *　アノテーションはいりません
     */
    public function putSampleAction($slug)
    {
        // Sampleサービスの生成
        $sampleService = $this->getSampleService(); // 新しくサービスクラスを作成したらBaseControllerに定義しこのように取得してください
        $userList = $sampleService->insertUser();

        // リストを渡せば自動的にJsonで返してくれるようにymlで設定してあります
        return $userList;
    }

    /**
     * HTTPメソッド：DELETE
     * http://localhost:8000/app_dev.php/api/samples/{slug}.json
     *
     *　ユーザテーブルからユーザID={slug}のユーザを削除する
     *　メソッド名の定義に注意してください。メソッド名から自動的にurlとHTTPメソッドが決定されます
     *　アノテーションはいりません
     */
    public function deleteSampleAction($slug)
    {
        // Sampleサービスの生成
        $sampleService = $this->getSampleService(); // 新しくサービスクラスを作成したらBaseControllerに定義しこのように取得してください
        $userList = $sampleService->deleteUser($slug);

        // リストを渡せば自動的にJsonで返してくれるようにymlで設定してあります
        return $userList;
    }
}
