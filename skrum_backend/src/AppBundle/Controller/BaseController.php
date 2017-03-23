<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Utils\LoggerManager;
use Symfony\Component\HttpFoundation\Request;
use JsonSchema\Validator;
use Symfony\Component\Form\FormInterface;
use AppBundle\Exception\JsonSchemaException;

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
     * JsonSchemaバリデーション
     *
     * @param Request $request リクエストデータ
     * @param string $schemaFilePath JsonSchemaファイルパス（例："AppBundle/Api/JsonSchema/SamplePdu"）
     * @return array
     */
    protected function validateSchema(Request $request, $schemaFilePath)
    {
        $requestJson = $request->getContent();
        if (!$requestJson) {
            throw new JsonSchemaException("リクエストデータが存在しません");
        }

        $schema = file_get_contents(dirname(__FILE__) . '/../../' . $schemaFilePath . '.json');
        $validator = new Validator();
        $validator->validate(json_decode($requestJson), json_decode($schema));

        return $this->makeErrorResponse($validator->getErrors());
    }

    /**
     * JsonSchemaバリデーションエラーのレスポンスを整形
     *
     * @param array $errors JsonScemaエラー配列
     * @return array レスポンス用エラー配列
     */
    private function makeErrorResponse($errors)
    {
        if (!$errors) return $errors;

        foreach ($errors as $error)
        {
            $requiredErrorItem['field'] = $error['property'];
            $requiredErrorItem['message'] = $error['message'];
            $errorResponse[] = $requiredErrorItem;
        }

        return $errorResponse;
    }

    /**
     * リクエストデータをフォームにバインド
     *
     * @param Request $request リクエストデータ
     * @param FormInterface $form フォームインターフェース
     * @return void
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new JsonSchemaException("リクエストデータが存在しません");
        }
        $form->submit($data);
    }

    /**
     * バリデーションエラー時のレスポンス生成（フォーム用）
     *
     * @param FormInterface $form フォームインターフェース
     * @return array バリデーションエラー情報
     */
    protected function getValidationErrors(FormInterface $form)
    {
        foreach ($form->all() as $childForm)
        {
            if ($childForm instanceof FormInterface)
            {
                foreach ($childForm->getErrors() as $childError)
                {
                    $error['field'] = $childForm->getName();
                    $error['message'] = $childError->getMessage();
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * バリデーション（数値型チェック）
     *
     * @param $item チェック対象
     * @param $digit 桁数
     * @return boolean バリデーションチェック結果
     */
    protected function checkNumber ($item, $digit)
    {
        if (is_null($item)) return false;
        if (!is_numeric($item)) return false;
        if (mb_strlen($item) > $digit) return false;

        return true;
    }

    /**
     * バリデーション（文字列型チェック）
     *
     * @param $item チェック対象
     * @param $digit 桁数
     * @return boolean バリデーションチェック結果
     */
    protected function checkString ($item, $digit)
    {
        if (is_null($item)) return false;
        if (!is_string($item)) return false;
        if (mb_strlen($item) > $digit) return false;

        return true;
    }

    /**
     * バリデーション（11桁のint型ID）
     *
     * @param $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkIntID ($item)
    {
        return $this->checkNumber($item, 11);
    }

    /**
     * バリデーション（20桁のBigint型ID）
     *
     * @param $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkBigintID ($item)
    {
        return $this->checkNumber($item, 20);
    }

    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getSampleService()
    {
        return $this->get('api.sample_service');
    }
}
