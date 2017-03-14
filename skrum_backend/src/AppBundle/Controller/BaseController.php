<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Utils\LoggerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

/**
 * ベースコントローラ（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseController extends FOSRestController
{
    /**
     * ロガー取得
     *
     * @return \Monolog\Logger monologロガーインスタンス
     */
    private function getLogger()
    {
        return LoggerManager::getInstance()->getLogger();
    }

    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logDebug($message, array $context = array())
    {
        return $this->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logInfo($message, array $context = array())
    {
        return $this->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logWarning($message, array $context = array())
    {
        return $this->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logError($message, array $context = array())
    {
        return $this->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logCritical($message, array $context = array())
    {
        return $this->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    protected function logAlert($message, array $context = array())
    {
        return $this->getLogger()->addAlert($message, $context);
    }

    /**
     * リクエストデータをバインド
     *
     * @param Request $request リクエストデータ
     * @param FormInterface $form フォームインターフェース
     * @return void
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
//         if ($data === null) {
//             $apiProblem = new ApiProblem(
//                     400,
//                     ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
//                     );
//             throw new ApiProbException($apiProblem);
//         }
        $form->submit($data);
    }

    /**
     * バリデーションエラー時のレスポンス生成
     *
     * @param FormInterface $form フォームインターフェース
     * @return array バリデーションエラー情報
     */
    protected function getErrors(FormInterface $form)
    {
        foreach ($form->all() as $childForm)
        {
            if ($childForm instanceof FormInterface)
            {
                foreach ($childForm->getErrors() as $childError)
                {
                    $error['field'] = $childForm->getName();
                    $error['message'] = $childError;
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }

    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getSampleService()
    {
        return $this->get('api.sample_service');
    }
}
