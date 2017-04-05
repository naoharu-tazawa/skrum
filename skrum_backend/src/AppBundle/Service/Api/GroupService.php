<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;

/**
 * グループサービスクラス
 *
 * @author naoharu.tazawa
 */
class GroupService extends BaseService
{
    /**
     * グループ新規登録
     *
     * @param \AppBundle\Utils\Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @return void
     */
    public function createGroup($auth, $data)
    {
        // トランザクション開始
        $this->beginTransaction();

        try {
            $mCompanyRepos = $this->getMCompanyRepository();
            $mCompany = $mCompanyRepos->find($auth->getCompanyId());

            // グループ登録
            $mGroup = new MGroup();
            $mGroup->setCompany($mCompany);
            $mGroup->setGroupName($data['groupName']);
            $mGroup->setGroupType($data['groupType']);
            $mGroup->setLeaderUserId($auth->getUserId());
            $this->persist($mGroup);
            $this->flush();

            if (array_key_exists('groupTreeId', $data)) {
                // 所属先グループツリーパスを取得
                $tGroupTreeRepos = $this->getTGroupTreeRepository();
                $result = $tGroupTreeRepos->getGroupTreePath($data['groupTreeId'], $auth->getCompanyId());

                // グループツリー登録
                $tGroupTree = new TGroupTree();
                $tGroupTree->setGroup($mGroup);
                $tGroupTree->setGroupTreePath($result['groupTreePath'] . $mGroup->getGroupId() . '/');
                $tGroupTree->setGroupTreePathName($result['groupTreePathName'] . $mGroup->getGroupName() . '/');
                $this->persist($tGroupTree);
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * グループ基本情報取得
     *
     * @param integer $groupId グループID
     * @param integer $companyId 会社ID
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO
     */
    public function getBasicGroupInfo($groupId, $companyId)
    {
        $mGroupRepos = $this->getMGroupRepository();
        $mGroupArray = $mGroupRepos->getGroupWithLeaderUser($groupId, $companyId);
        if (count($mGroupArray) === 0) {
            throw new ApplicationException('グループが存在しません');
        }

        // グループパスを取得
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTreeArray = $tGroupTreeRepos->findBy(array('group' => $groupId));
        $groupPathArray = array();
        foreach ($tGroupTreeArray as $tGroupTree) {
            $groupPathDTO = new GroupPathDTO();
            $groupPathDTO->setGroupTreePath($tGroupTree->getGroupTreePath());
            $groupPathDTO->setGroupTreePathName($tGroupTree->getGroupTreePathName());
            $groupPathArray[] = $groupPathDTO;
        }

        $basicGroupInfoDTO = new BasicGroupInfoDTO();
        $basicGroupInfoDTO->setGroupId($mGroupArray['mGroup']->getGroupId());
        $basicGroupInfoDTO->setName($mGroupArray['mGroup']->getGroupName());
        $basicGroupInfoDTO->setGroupPaths($groupPathArray);
        $basicGroupInfoDTO->setMission($mGroupArray['mGroup']->getMission());
        $basicGroupInfoDTO->setLeaderUserId($mGroupArray['mGroup']->getLeaderUserId());
        $basicGroupInfoDTO->setLeaderName($mGroupArray['mUser']->getLastName() . ' ' . $mGroupArray['mUser']->getFirstName());

        return $basicGroupInfoDTO;
    }
}
