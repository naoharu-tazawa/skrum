<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Utils\Constant;
use AppBundle\Api\ResponseDTO\OkrMapDTO;
use AppBundle\Api\ResponseDTO\ThreeGensOkrMapDTO;

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
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return array
     */
    public function getUserObjectivesAction(Request $request, string $userId): array
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $basicOkrDTOArray = $okrMapService->getObjectives(Constant::SUBJECT_TYPE_USER, $auth, $userId, null, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }

    /**
     * ユーザ目標取得（D3.js用）
     *
     * @Rest\Get("/v1/users/{userId}/okrs.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return OkrMapDTO
     */
    public function getUserOkrsAction(Request $request, string $userId): OkrMapDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $okrMapDTO = $okrMapService->getDescendantsOfMultipleParents(Constant::SUBJECT_TYPE_USER, $auth, $userId, null, $timeframeId, $auth->getCompanyId());

        return $okrMapDTO;
    }

    /**
     * グループ目標取得
     *
     * @Rest\Get("/v1/groups/{groupId}/objectives.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return array
     */
    public function getGroupObjectivesAction(Request $request, string $groupId): array
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $basicOkrDTOArray = $okrMapService->getObjectives(Constant::SUBJECT_TYPE_GROUP, $auth, null, $groupId, $timeframeId, $auth->getCompanyId());

        return $basicOkrDTOArray;
    }

    /**
     * グループ目標取得（D3.js用）
     *
     * @Rest\Get("/v1/groups/{groupId}/okrs.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return OkrMapDTO
     */
    public function getGroupOkrsAction(Request $request, string $groupId): OkrMapDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ目標取得処理
        $okrMapService = $this->getOkrMapService();
        $okrMapDTO = $okrMapService->getDescendantsOfMultipleParents(Constant::SUBJECT_TYPE_GROUP, $auth, null, $groupId, $timeframeId, $auth->getCompanyId());

        return $okrMapDTO;
    }

    /**
     * 会社目標取得
     *
     * @Rest\Get("/v1/companies/{companyId}/objectives.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return array
     */
    public function getCompanyObjectivesAction(Request $request, string $companyId): array
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
     * 会社目標取得（D3.js用）
     *
     * @Rest\Get("/v1/companies/{companyId}/okrs.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return OkrMapDTO
     */
    public function getCompanyOkrsAction(Request $request, string $companyId): OkrMapDTO
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
        $okrMapDTO = $okrMapService->getDescendantsOfMultipleParents(Constant::SUBJECT_TYPE_COMPANY, $auth, null, null, $timeframeId, $auth->getCompanyId());

        return $okrMapDTO;
    }

    /**
     * 3世代OKR取得
     *
     * @Rest\Get("/v1/okrs/{okrId}/familyokrs.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function getOkrFamilyokrsAction(Request $request, string $okrId): ThreeGensOkrMapDTO
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

    /**
     * 単一OKRマップ取得（D3.js用）
     *
     * @Rest\Get("/v1/okrs/{okrId}/descendants.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $okrId OKRID
     * @return array
     */
    public function getOkrDescendantsAction(Request $request, string $okrId): OkrMapDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // OKR存在チェック
        $tOkr = $this->getDBExistanceLogic()->checkOkrExistance($okrId, $auth->getCompanyId());

        // 単一OKRマップ取得
        $okrMapService = $this->getOkrMapService();
        $okrMapDTO = $okrMapService->getDescendants($auth, $tOkr, $timeframeId);

        return $okrMapDTO;
    }
}
