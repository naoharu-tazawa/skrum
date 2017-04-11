<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Controller\BaseController;

/**
 * OKRマップコントローラ
 *
 * @author naoharu.tazawa
 */
class OkrMapController extends BaseController
{
    /**
     * ユーザ目標取得
     *
     * @Rest\Get("/users/{userId}/objectives.{_format}")
     * @param $request リクエストオブジェクト
     * @return array
     */
    public function getUserObjectivesAction(Request $request, $userId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザIDの一致をチェック
        if ($userId != $auth->getUserId()) {
            throw new ApplicationException('ユーザIDが存在しません');
        }

        // ユーザ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $basicOkrDTOArray = $okrMapService->getUserObjectives($auth, $userId, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }
}
