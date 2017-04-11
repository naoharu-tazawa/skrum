<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Controller\BaseController;
use AppBundle\Api\ResponseDTO\TopDTO;
use AppBundle\Utils\DBConstant;
use AppBundle\Api\ResponseDTO\UserBasicsDTO;

/**
 * 基本情報コントローラ
 *
 * @author naoharu.tazawa
 */
class BasicInfoController extends BaseController
{
    /**
     * ログイン後初期表示情報取得
     *
     * @Rest\Get("/users/{userId}/top.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function getUserTopAction(Request $request, $userId)
    {
        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザIDの一致をチェック
        if ($userId != $auth->getUserId()) {
            throw new ApplicationException('ユーザIDが存在しません');
        }

        // タイムフレーム一覧取得
        $timeframeService = $this->getTimeframeService();
        $timeframeDTOArray = $timeframeService->getTimeframes($auth->getCompanyId());

        // デフォルトタイムフレームのタイムフレームIDを取得
        foreach ($timeframeDTOArray as $timeframeDTO) {
            if ($timeframeDTO->getDefaultFlg() == DBConstant::FLG_TRUE) {
                $timeframeId = $timeframeDTO->getTimeframeId();
            }
        }

        // 所属グループリスト取得
        $groupMemberService = $this->getGroupMemberService();
        $groups = $groupMemberService->getTeamsAndDepartments($userId);

        // ユーザ基本情報取得
        $userService = $this->getUserService();
        $basicUserInfoDTO = $userService->getBasicUserInfo($userId, $auth->getCompanyId());

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults($auth, $userId, $timeframeId, $auth->getCompanyId());

        // 紐付け先情報取得
        $alignmentsInfoDTOArray = $okrService->getAlignmentsInfo($userId, $timeframeId, $auth->getCompanyId());

        // 返却DTOをセット
        $topDTO = new TopDTO();
        $topDTO->setTimeframes($timeframeDTOArray);
        $topDTO->setTeams($groups['teams']);
        $topDTO->setDepartments($groups['departments']);
        $topDTO->setUser($basicUserInfoDTO);
        $topDTO->setOkrs($okrsArray);
        $topDTO->setAlignmentsInfo($alignmentsInfoDTOArray);

        return $topDTO;
    }

    /**
     * ユーザ目標管理情報取得
     *
     * @Rest\Get("/users/{userId}/basics.{_format}")
     * @param $request リクエストオブジェクト
     * @param $userId ユーザID
     * @return array
     */
    public function getUserBasicsAction(Request $request, $userId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $mUser = $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // ユーザ基本情報取得
        $userService = $this->getUserService();
        $basicUserInfoDTO = $userService->getBasicUserInfo($userId, $auth->getCompanyId());

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults($auth, $userId, $timeframeId, $auth->getCompanyId());

        // 紐付け先情報取得
        $alignmentsInfoDTOArray = $okrService->getAlignmentsInfo($userId, $timeframeId, $auth->getCompanyId());

        // 返却DTOをセット
        $userBasicsDTO = new UserBasicsDTO();
        $userBasicsDTO->setUser($basicUserInfoDTO);
        $userBasicsDTO->setOkrs($okrsArray);
        $userBasicsDTO->setAlignmentsInfo($alignmentsInfoDTOArray);

        return $userBasicsDTO;
    }
}
