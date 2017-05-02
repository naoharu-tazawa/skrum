<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\InvalidParameterException;
use AppBundle\Utils\Constant;
use AppBundle\Api\ResponseDTO\CompanyBasicsDTO;
use AppBundle\Api\ResponseDTO\GroupBasicsDTO;
use AppBundle\Api\ResponseDTO\TopDTO;
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
     * @Rest\Get("/v1/users/{userId}/top.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return TopDTO
     */
    public function getUserTopAction(Request $request, string $userId): TopDTO
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
            if ($timeframeDTO->getDefaultFlg()) {
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
     * @Rest\Get("/v1/users/{userId}/basics.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $userId ユーザID
     * @return UserBasicsDTO
     */
    public function getUserBasicsAction(Request $request, string $userId): UserBasicsDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // ユーザ存在チェック
        $this->getDBExistanceLogic()->checkUserExistanceIncludingArchivedUsers($userId, $auth->getCompanyId());

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
     * @Rest\Get("/v1/groups/{groupId}/basics.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $groupId グループID
     * @return GroupBasicsDTO
     */
    public function getGroupBasicsAction(Request $request, string $groupId): GroupBasicsDTO
    {
        // リクエストパラメータを取得
        $timeframeId = $request->get('tfid');

        // リクエストパラメータのバリデーション
        $errors = $this->checkIntID($timeframeId);
        if($errors) throw new InvalidParameterException("タイムフレームIDが不正です", $errors);

        // 認証情報を取得
        $auth = $request->get('auth_token');

        // グループ存在チェック
        $this->getDBExistanceLogic()->checkGroupExistanceIncludingArchivedGroups($groupId, $auth->getCompanyId());

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
     * @Rest\Get("/v1/companies/{companyId}/basics.{_format}")
     * @param Request $request リクエストオブジェクト
     * @param string $companyId 会社ID
     * @return CompanyBasicsDTO
     */
    public function getCompanyBasicsAction(Request $request, string $companyId): CompanyBasicsDTO
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
