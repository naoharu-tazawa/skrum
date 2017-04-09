<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JsonSchema\Validator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use AppBundle\Utils\LoggerManager;
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
     * リクエストURLのサブドメインを取得
     *
     * @param Request $request リクエストデータ
     * @return string サブドメイン
     */
    protected function getSubdomain(Request $request)
    {
        return strstr($request->getHost(), '.', true);
    }

    /**
     * リクエストJSONを配列で取得
     *
     * @param Request $request リクエストデータ
     * @return array
     */
    protected function getRequestJsonAsArray(Request $request)
    {
        return json_decode($request->getContent(), true);
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
        $data = json_decode($request->getContent());
        if (!$data) throw new JsonSchemaException("リクエストデータが存在しません");

        $validator = new Validator();
        $validator->validate($data, (object)['$ref' => 'file://' . realpath(dirname(__FILE__) . '/../../' . $schemaFilePath . '.json')]);

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

        $errorResponse = array();
        foreach ($errors as $error) {
            $requiredErrorItem['field'] = $error['property'];
            $requiredErrorItem['message'] = $error['message'];
            $errorResponse = $requiredErrorItem;
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
    protected function getFormErrors(FormInterface $form)
    {
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                $errors = array();
                foreach ($childForm->getErrors() as $childError) {
                    $error['field'] = $childForm->getName();
                    $error['message'] = $childError->getMessage();
                    $errors = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * URLパラメータバリデーション（数値型チェック）
     *
     * @param $item チェック対象
     * @param $digit 桁数
     * @return array バリデーションエラー情報
     */
    protected function validateNumeric($item, $digit)
    {
        $errors = $this->get('validator')->validate($item, array(
            new Assert\NotNull(),
            new Assert\Type("numeric"),
            new Assert\Length(array('max' => $digit))
        ));

        return $this->getUrlParameterErrors($errors);
    }

    /**
     * URLパラメータバリデーション（文字列型チェック）
     *
     * @param $item チェック対象
     * @param $digit 桁数
     * @return array バリデーションエラー情報
     */
    protected function validateString($item, $digit)
    {
        $errors = $this->get('validator')->validate($item, array(
            new Assert\NotNull(),
            new Assert\Type("string"),
            new Assert\Length(array('max' => $digit))
        ));

        return $this->getUrlParameterErrors($errors);
    }

    /**
     * バリデーションエラー時のレスポンス生成（URLパラメータ用）
     *
     * @param ConstraintViolationListInterface $errors
     * @return array バリデーションエラー情報
     */
    private function getUrlParameterErrors(ConstraintViolationListInterface $errors)
    {
        if (!$errors) return $errors;

        $errorResponse = array();
        foreach ($errors as $error) {
            $requiredErrorItem['field'] = '';
            $requiredErrorItem['message'] = $error->getMessage();
            $errorResponse = $requiredErrorItem;
        }

        return $errorResponse;
    }

    /**
     * バリデーション（11桁のint型ID）
     *
     * @param $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkIntID($item)
    {
        return $this->validateNumeric($item, 11);
    }

    /**
     * バリデーション（20桁のBigint型ID）
     *
     * @param $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkBigintID($item)
    {
        return $this->validateNumeric($item, 20);
    }

    /**
     * RFC3339形式の日付文字列を生成
     *
     * @param $datetimeString 日付文字列(例："2017-03-26 22:09:15")
     * @return string RFC3339形式の日付文字列（例："2017-03-26T13:09:15+09:00"）
     */
    protected function getRfc3339Date($datetimeString = null)
    {
        if ($datetimeString)
        {
            return date(DATE_RFC3339, strtotime($datetimeString));
        } else {
            return date(DATE_RFC3339);
        }
    }

    //----------------------------------------------
    //ここからロジック/サービスクラスの取得メソッド
    //----------------------------------------------

    protected function getDBExistanceLogic()
    {
        return $this->get('api.db_existance_logic');
    }

    protected function getPermissionLogic()
    {
        return $this->get('api.permission_logic');
    }

    protected function getSampleService()
    {
        return $this->get('api.sample_service');
    }

    protected function getUserSettingService()
    {
        return $this->get('api.user_setting_service');
    }

    protected function getLoginService()
    {
        return $this->get('api.login_service');
    }

    protected function getUserService()
    {
        return $this->get('api.user_service');
    }

    protected function getGroupService()
    {
        return $this->get('api.group_service');
    }

    protected function getGroupMemberService()
    {
        return $this->get('api.group_member_service');
    }

    protected function getOkrService()
    {
        return $this->get('api.okr_service');
    }

    protected function getTimeframeService()
    {
        return $this->get('api.timeframe_service');
    }
}
