<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;
use AppBundle\Exception\DoubleOperationException;

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
     * @param \AppBundle\Entity\MGroup $mGroup グループエンティティ
     * @param string $groupTreePath グループツリーパス
     * @param string $groupTreePathName グループツリーパス名
     * @return void
     */
    public function createGroupPath($mGroup, $groupTreePath, $groupTreePathName)
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
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
