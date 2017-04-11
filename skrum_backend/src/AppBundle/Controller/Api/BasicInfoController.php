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
use AppBundle\Api\ResponseDTO\GroupBasicsDTO;
use AppBundle\Utils\Constant;
use AppBundle\Api\ResponseDTO\CompanyBasicsDTO;

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
        $okrsArray = $okrService->getObjectivesAndKeyResults(Constant::SUBJECT_TYPE_USER, $auth, $userId, null, $timeframeId, $auth->getCompanyId());

        // 紐付け先情報取得
        $alignmentsInfoDTOArray = $okrService->getAlignmentsInfo(Constant::SUBJECT_TYPE_USER, $userId, null, $timeframeId, $auth->getCompanyId());

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
        $this->getDBExistanceLogic()->checkUserExistance($userId, $auth->getCompanyId());

        // ユーザ基本情報取得
        $userService = $this->getUserService();
        $basicUserInfoDTO = $userService->getBasicUserInfo($userId, $auth->getCompanyId());

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults(Constant::SUBJECT_TYPE_USER, $auth, $userId, null, $timeframeId, $auth->getCompanyId());

        // 紐付け先情報取得
        $alignmentsInfoDTOArray = $okrService->getAlignmentsInfo(Constant::SUBJECT_TYPE_USER, $userId, null, $timeframeId, $auth->getCompanyId());

        // 返却DTOをセット
        $userBasicsDTO = new UserBasicsDTO();
        $userBasicsDTO->setUser($basicUserInfoDTO);
        $userBasicsDTO->setOkrs($okrsArray);
        $userBasicsDTO->setAlignmentsInfo($alignmentsInfoDTOArray);

        return $userBasicsDTO;
    }

    /**
     * グループ目標管理情報取得
     *
     * @Rest\Get("/groups/{groupId}/basics.{_format}")
     * @param $request リクエストオブジェクト
     * @param $groupId グループID
     * @return array
     */
    public function getGroupBasicsAction(Request $request, $groupId)
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $mGroup = $this->getDBExistanceLogic()->checkGroupExistance($groupId, $auth->getCompanyId());

        // グループ基本情報取得
        $groupService = $this->getGroupService();
        $basicGroupInfoDTO = $groupService->getBasicGroupInfo($groupId, $auth->getCompanyId());

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults(Constant::SUBJECT_TYPE_GROUP, $auth, null, $groupId, $timeframeId, $auth->getCompanyId());

        // 紐付け先情報取得
        $alignmentsInfoDTOArray = $okrService->getAlignmentsInfo(Constant::SUBJECT_TYPE_GROUP, null, $groupId, $timeframeId, $auth->getCompanyId());

        // 返却DTOをセット
        $groupBasicsDTO = new GroupBasicsDTO();
        $groupBasicsDTO->setGroup($basicGroupInfoDTO);
        $groupBasicsDTO->setOkrs($okrsArray);
        $groupBasicsDTO->setAlignmentsInfo($alignmentsInfoDTOArray);

        return $groupBasicsDTO;
    }

    /**
     * 会社目標管理情報取得
     *
     * @Rest\Get("/companies/{companyId}/basics.{_format}")
     * @param $request リクエストオブジェクト
     * @param $companyId 会社ID
     * @return array
     */
    public function getCompanyBasicsAction(Request $request, $companyId)
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

        // 会社基本情報取得
        $companyService = $this->getCompanyService();
        $basicCompanyInfoDTO = $companyService->getBasicCompanyInfo($companyId);

        // OKR一覧取得
        $okrService = $this->getOkrService();
        $okrsArray = $okrService->getObjectivesAndKeyResults(Constant::SUBJECT_TYPE_COMPANY, $auth, null, null, $timeframeId, $auth->getCompanyId());

        // 返却DTOをセット
        $companyBasicsDTO = new CompanyBasicsDTO();
        $companyBasicsDTO->setCompany($basicCompanyInfoDTO);
        $companyBasicsDTO->setOkrs($okrsArray);

        return $companyBasicsDTO;
    }
}
