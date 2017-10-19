<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Exception\JsonSchemaException;
use AppBundle\Exception\PermissionException;
use AppBundle\Utils\DBConstant;
use AppBundle\Api\ResponseDTO\OneOnOneDialogDTO;

/**
 * 1on1コントローラ
 *
 * @author naoharu.tazawa
 */
class OneOnOneController extends BaseController
{
    /**
     * 日報/進捗報告送信
     *
     * @Rest\Post("/v1/users/{userId}/reports.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function postUserReportsAction(Request $request, int $userId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostUserReportsPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 1on1種別に応じて必要なJsonSchemaのプロパティをチェック
        if ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_DAILY_REPORT) {
            if (empty($data['to'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } elseif ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_PROGRESS_MEMO) {
            if (empty($data['okrId'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        if (!empty($data['okrId'])) {
            $this->getDBExistanceLogic()->checkOkrExistance($data['okrId'], $auth->getCompanyId());
        }

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 日報/進捗報告送信処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneService->submitReport($auth, $data);

        return array('result' => 'OK');
    }

    /**
     * フィードバック/ヒアリング送信
     *
     * @Rest\Post("/v1/users/{userId}/feedbacks.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function postUserFeedbacksAction(Request $request, int $userId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostUserFeedbacksPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 1on1種別に応じて必要なJsonSchemaのプロパティをチェック
        if ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_FEEDBACK) {
            if (empty($data['feedbackType'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        } elseif ($data['oneOnOneType'] === DBConstant::ONE_ON_ONE_TYPE_HEARING) {
            if (empty($data['dueDate'])) {
                throw new JsonSchemaException("リクエストJSONスキーマが不正です");
            }
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        if (!empty($data['okrId'])) {
            $this->getDBExistanceLogic()->checkOkrExistance($data['okrId'], $auth->getCompanyId());
        }

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 日報/進捗報告送信処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneService->submitFeedback($auth, $data);

        return array('result' => 'OK');
    }

    /**
     * 面談メモ送信
     *
     * @Rest\Post("/v1/users/{userId}/interviewnotes.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function postUserInterviewnotesAction(Request $request, int $userId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostUserInterviewnotesPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 面談相手ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistance($data['intervieweeUserId'], $auth->getCompanyId());

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 日報/進捗報告送信処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneService->submitInterviewnote($auth, $data);

        return array('result' => 'OK');
    }

    /**
     * 1on1返信コメント
     *
     * @Rest\Post("/v1/oneonones/{oneOnOneId}/replies.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $oneOnOneId 1on1ID
     * @return array
     */
    public function postOneononeRepliesAction(Request $request, int $oneOnOneId): array
    {
        // JsonSchemaバリデーション
        $errors = $this->validateSchema($request, 'AppBundle/Api/JsonSchema/PostOneononeRepliesPdu');
        if ($errors) throw new JsonSchemaException("リクエストJSONスキーマが不正です", $errors);

        // リクエストJSONを取得
        $data = $this->getRequestJsonAsArray($request);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 1on1存在チェック
        $tOneOnOne = $this->getDBExistanceLogic()->checkOneOnOneExistance($oneOnOneId, $auth->getCompanyId());

        // 1on1返信コメント処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneService->submitOneOnOneReply($auth, $data, $tOneOnOne);

        return array('result' => 'OK');
    }

    /**
     * 1on1新着履歴取得
     *
     * @Rest\Get("/v1/users/{userId}/newoneonones.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function getUserNewoneononesAction(Request $request, int $userId): array
    {
        // リクエストパラメータを取得
        $keyword = $request->get('q');
        $startDate = $request->get('sdate');
        $endDate = $request->get('edate');
        $before = $request->get('before');

        // リクエストパラメータのバリデーション
        if ($keyword !== null) {
            $keywordErrors = $this->checkSearchKeyword($keyword);
            if($keywordErrors) throw new InvalidParameterException("検索キーワードが不正です", $keywordErrors);
        }
        if ($startDate !== null) {
            $startDateErrors = $this->checkDatetimeString($startDate);
            if($startDateErrors) throw new InvalidParameterException("開始日が不正です", $startDateErrors);
        }
        if ($endDate !== null) {
            $endDateErrors = $this->checkDatetimeString($endDate);
            if($endDateErrors) throw new InvalidParameterException("終了日が不正です", $endDateErrors);
        }
        if ($before !== null) {
            $beforeErrors = $this->checkDatetimeString($before);
            if($beforeErrors) throw new InvalidParameterException("取得基準日時が不正です", $beforeErrors);
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 1on1送受信履歴取得処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneDTOArray = $oneOnOneService->getNewOneOnOnes($auth, $keyword, $startDate, $endDate, $before);

        return $oneOnOneDTOArray;
    }

    /**
     * 1on1送受信履歴取得
     *
     * @Rest\Get("/v1/users/{userId}/oneonones.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function getUserOneononesAction(Request $request, int $userId): array
    {
        // リクエストパラメータを取得
        $oneOnOneType = $request->get('oootype');
        $before = $request->get('before');

        // リクエストパラメータのバリデーション
        $oneOnOneTypeErrors = $this->checkNumeric($oneOnOneType);
        if($oneOnOneTypeErrors) throw new InvalidParameterException("1on1種別が不正です", $oneOnOneTypeErrors);
        if ($before !== null) {
            $beforeErrors = $this->checkDatetimeString($before);
            if($beforeErrors) throw new InvalidParameterException("取得基準日時が不正です", $beforeErrors);
        }

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 1on1送受信履歴取得処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneDTOArray = $oneOnOneService->getOneOnOneHistory($auth, $oneOnOneType, $before);

        return $oneOnOneDTOArray;
    }

    /**
     * 1on1ダイアログ取得
     *
     * @Rest\Get("/v1/users/{userId}/oneonones/{oneOnOneId}.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @param integer $oneOnOneId 1on1ID
     * @return OneOnOneDialogDTO
     */
    public function getUserOneononeAction(Request $request, int $userId, int $oneOnOneId): OneOnOneDialogDTO
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 1on1ダイアログ取得処理
        $oneOnOneService = $this->getOneOnOneService();
        $oneOnOneDialogDTO = $oneOnOneService->getOneOnOneDialog($auth, $oneOnOneId);

        return $oneOnOneDialogDTO;
    }

    /**
     * 前回送信先ユーザリスト取得
     *
     * @Rest\Get("/v1/users/{userId}/defaultdestinations.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param integer $userId ユーザID
     * @return array
     */
    public function getUserDefaultdestinationsAction(Request $request, int $userId): array
    {
        // リクエストパラメータを取得
        $oneOnOneType = $request->get('oootype');

        // リクエストパラメータのバリデーション
        $oneOnOneTypeErrors = $this->checkNumeric($oneOnOneType);
        if($oneOnOneTypeErrors) throw new InvalidParameterException("1on1種別が不正です", $oneOnOneTypeErrors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 権限チェック（ユーザIDの一致をチェック）
        if ($userId !== $auth->getUserId()) {
            throw new PermissionException('ユーザIDが存在しません');
        }

        // 1on1ダイアログ取得処理
        $oneOnOneService = $this->getOneOnOneService();
        $basicUserInfoDTOArray = $oneOnOneService->getDefaultDestinations($userId, $oneOnOneType);

        return $basicUserInfoDTOArray;
    }
}
