<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Form\FormInterface;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Utils\LoggerManager;
use Monolog\Logger;

/**
 * ベースコントローラ（被継承クラス）
 *
 * @author naoharu.tazawa
 */
class BaseController extends Controller
{
    /**
     * ロガー取得
     *
     * @return Logger monologロガーインスタンス
     */
    private function getLogger(): Logger
    {
        return LoggerManager::getInstance()->getLogger();
    }

    /**
     * DEBUGログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logDebug(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addDebug($message, $context);
    }

    /**
     * INFOログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logInfo(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addInfo($message, $context);
    }

    /**
     * WARNログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logWarning(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addWarning($message, $context);
    }

    /**
     * ERRORログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logError(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addError($message, $context);
    }

    /**
     * CRITICALログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logCritical(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addCritical($message, $context);
    }

    /**
     * ALERTログ出力
     *
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return boolean Whether the record has been processed
     */
    protected function logAlert(string $message, array $context = array()): bool
    {
        return $this->getLogger()->addAlert($message, $context);
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
            throw new JsonSchemaException('リクエストデータが存在しません');
        }

        $form->submit($data);
    }

    /**
     * バリデーションエラー時のレスポンス生成（フォーム用）
     *
     * @param FormInterface $form フォームインターフェース
     * @return array バリデーションエラー情報
     */
    protected function getFormErrors(FormInterface $form): array
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
     * @param string $item チェック対象
     * @param integer $digit 桁数
     * @return array バリデーションエラー情報
     */
    protected function validateNumeric(string $item = null, int $digit): array
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
     * @param string $item チェック対象
     * @param integer $digit 桁数
     * @return array バリデーションエラー情報
     */
    protected function validateString(string $item = null, int $digit): array
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
    private function getUrlParameterErrors(ConstraintViolationListInterface $errors): array
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
     * @param string $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkIntID(string $item = null): array
    {
        return $this->validateNumeric($item, 11);
    }

    /**
     * バリデーション（20桁のBigint型ID）
     *
     * @param string $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkBigintID(string $item = null): array
    {
        return $this->validateNumeric($item, 20);
    }

    /**
     * バリデーション（11桁のstring型の数字）
     *
     * @param string $item チェック対象ID
     * @return boolean バリデーションチェック結果
     */
    protected function checkNumeric(string $item = null): array
    {
        return $this->validateNumeric($item, 11);
    }

    /**
     * バリデーション（検索値文字列）
     *
     * @param string $item チェック対象検索ワード
     * @return boolean バリデーションチェック結果
     */
    protected function checkSearchKeyword(string $item = null): array
    {
        return $this->validateString($item, 255);
    }

    /**
     * RFC3339形式の日付文字列を生成
     *
     * @param string $datetimeString 日付文字列(例："2017-03-26 22:09:15")
     * @return string RFC3339形式の日付文字列（例："2017-03-26T13:09:15+09:00"）
     */
    protected function getRfc3339Date(string $datetimeString = null): string
    {
        if ($datetimeString) {
            return date(DATE_RFC3339, strtotime($datetimeString));
        } else {
            return date(DATE_RFC3339);
        }
    }

    //----------------------------------------------
    //ここからロジッククラスの取得メソッド
    //----------------------------------------------

    protected function getDBExistanceLogic()
    {
        return $this->get('api.db_existance_logic');
    }

    protected function getDisclosureLogic()
    {
        return $this->get('api.disclosure_logic');
    }

    protected function getOkrAchievementRateLogic()
    {
        return $this->get('api.okr_achievement_rate_logic');
    }

    protected function getOkrNestedIntervalsLogic()
    {
        return $this->get('api.okr_nested_intervals_logic');
    }

    protected function getOkrOperationLogic()
    {
        return $this->get('api.okr_operation_logic');
    }

    protected function getPermissionLogic()
    {
        return $this->get('api.permission_logic');
    }

    //----------------------------------------------
    //ここからサービスクラスの取得メソッド
    //----------------------------------------------

    protected function getContractService()
    {
        return $this->get('bo.contract_service');
    }

    protected function getAuthorizationService()
    {
        return $this->get('bo.authorization_service');
    }
}
