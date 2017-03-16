<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;
use AppBundle\Form\Data\UserData;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\MaintenanceException;
use AppBundle\Exception\AuthException;

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
     * HTTPメソッド：POST
     * http://localhost:8000/app_dev.php/api/samples.json
     *
     *　Formクラス及びバリデーションのサンプルです。以下のjsonをpostデータに含めてapi投げてみてください
     * {"userId":"2","userName":"naoharu"}
     */
    public function postSamplesAction(Request $request)
    {
        $form = $this->createForm(UserType::class, new UserData());
        $this->processForm($request, $form);

        // 例外のサンプル
        // throw new ApplicationException("ApplicationExceptionのサンプル");
        // throw new InvalidParameterException("InvalidParameterExceptionのサンプル");
        // throw new MaintenanceException("MaintenanceExceptionのサンプル");
        // throw new AuthException("AuthExceptionのサンプル");

        if (!$form->isValid()) {
            throw new InvalidParameterException("InvalidParameterExceptionのサンプル", $this->getValidationErrors($form));
            // return array('result' => 'NG', 'errors' => $this->getValidationErrors($form));
        }

        return array('result'=>'OK');
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
