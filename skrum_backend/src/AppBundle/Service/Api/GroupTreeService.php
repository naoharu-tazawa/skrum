<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\DoubleOperationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathElementDTO;

/**
 * グループツリーサービスクラス
 *
 * @author naoharu.tazawa
 */
class GroupTreeService extends BaseService
{
    /**
     * グループツリー新規登録
     *
     * @param Auth $auth 認証情報
     * @param MGroup $mGroup グループエンティティ
     * @param string $groupTreePath グループツリーパス
     * @param string $groupTreePathName グループツリーパス名
     * @return void
     */
    public function createGroupPath(Auth $auth, MGroup $mGroup, string $groupTreePath, string $groupTreePathName): GroupPathDTO
    {
        // 登録グループツリーパス
        $newGroupTreePath = $groupTreePath . $mGroup->getGroupId() . '/';
        $newGroupTreePathName = $groupTreePathName . $mGroup->getGroupName() . '/';

        // 登録するグループツリーパスが既に登録されているかチェック
        $tGroupTreeRepos = $this->getTGroupTreeRepository();
        $tGroupTree = $tGroupTreeRepos->getByGroupTreePath($newGroupTreePath);
        if ($tGroupTree != null) {
            throw new DoubleOperationException('グループパスは既に登録されています');
        }

        // グループツリー登録
        $tGroupTree = new TGroupTree();
        $tGroupTree->setGroup($mGroup);
        $tGroupTree->setGroupTreePath($newGroupTreePath);
        $tGroupTree->setGroupTreePathName($newGroupTreePathName);

        try {
            $this->persist($tGroupTree);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }

        // レスポンスDTOを作成
        $groupTreePathArray = explode('/', $tGroupTree->getGroupTreePath(), -1);
        $groupTreePathNameArray = explode('/', $tGroupTree->getGroupTreePathName(), -1);
        $count = count($groupTreePathArray);
        $groupIdAndNameArray = array();
        for ($i = 0; $i < $count; ++$i) {
            $groupPathElementDTO = new GroupPathElementDTO();
            if ($i === 0) {
                $groupPathElementDTO->setId($auth->getCompanyId());
            } else {
                $groupPathElementDTO->setId($groupTreePathArray[$i]);
            }
            $groupPathElementDTO->setName($groupTreePathNameArray[$i]);
            $groupIdAndNameArray[] = $groupPathElementDTO;
        }

        $groupPathDTO = new GroupPathDTO();
        $groupPathDTO->setGroupTreeId($tGroupTree->getId());
        $groupPathDTO->setGroupPath($groupIdAndNameArray);

        return $groupPathDTO;
    }

    /**
     * グループツリー削除
     *
     * @param TGroupTree $tGroupTree グループツリーエンティティ
     * @param integer $groupId グループID
     * @return void
     */
    public function deleteGroupPath(TGroupTree $tGroupTree, int $groupId)
    {
        // 削除するグループツリーとグループの整合性をチェック
        if ($tGroupTree->getGroup()->getGroupId() != $groupId) {
            throw new ApplicationException('グループパスが不正です');
        }

        // グループパス削除
        try {
            $this->remove($tGroupTree);
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
