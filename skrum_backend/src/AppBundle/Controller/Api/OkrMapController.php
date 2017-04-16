<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Controller\BaseController;
use AppBundle\Utils\Constant;

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
     * @Rest\Get("/v1/users/{userId}/objectives.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
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
        $basicOkrDTOArray = $okrMapService->getObjectives(Constant::SUBJECT_TYPE_USER, $auth, $userId, null, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }

    /**
     * グループ目標取得
     *
     * @Rest\Get("/v1/groups/{groupId}/objectives.{_format}")
     * @param $request リクエストオブジェクト
     * @param $groupId グループID
     * @return array
     */
    public function getGroupObjectivesAction(Request $request, $groupId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // グループ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $basicOkrDTOArray = $okrMapService->getObjectives(Constant::SUBJECT_TYPE_GROUP, $auth, null, $groupId, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }

    /**
     * 会社目標取得
     *
     * @Rest\Get("/v1/companies/{companyId}/objectives.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyObjectivesAction(Request $request, $companyId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 会社IDの一致をチェック
        if ($companyId != $auth->getCompanyId()) {
            throw new ApplicationException('会社IDが存在しません');
        }

        // 会社目標取得処理
        $okrMapService = $this->getOkrMapService();
        $basicOkrDTOArray = $okrMapService->getObjectives(Constant::SUBJECT_TYPE_COMPANY, $auth, null, null, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }

    /**
     * 3世代OKR取得
     *
     * @Rest\Get("/v1/okrs/{okrId}/familyokrs.{_format}")
     * @param $request リクエストオブジェクト
     * @param $okrId OKRID
     * @return array
     */
    public function getOkrFamilyokrsAction(Request $request, $okrId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // 3世代OKR取得処理
        $okrMapService = $this->getOkrMapService();
        $threeGensOkrMapDTO = $okrMapService->getThreeGensOkrMap($auth, $okrId, $timeframeId);

        return $threeGensOkrMapDTO;
    }
}
