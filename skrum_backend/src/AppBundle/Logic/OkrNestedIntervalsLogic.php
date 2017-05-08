<?php

namespace AppBundle\Logic;

use AppBundle\Entity\TOkr;

/**
 * OKR入れ子区間モデルロジッククラス
 *
 * @author naoharu.tazawa
 */
class OkrNestedIntervalsLogic extends BaseLogic
{
    /**
     * 入れ子集合モデルの左値・右値を再計算
     *
     * @param TOkr $tOkr 挿入対象OKRエンティティ
     * @parame integer $companyId 会社ID
     * @return void
     */
    public function recalculate(TOkr $tOkr, int $companyId)
    {
        // タイムフレームIDを取得
        $timeframeId = $tOkr->getTimeframe()->getTimeframeId();

        // 親OKR存在チェック
        if ($tOkr->getParentOkr() === null) {
            return;
        }

        // 指定OKRとそれに紐づくOKRを全て取得
        $tOkrRepos = $this->getTOkrRepository();
        if ($tOkr->getTreeLeft() !== null) {
            // 入れ子区間モデルの左値・右値が存在する場合
            $tOkrArray = $tOkrRepos->getOkrAndAllAlignmentOkrs($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $timeframeId, $companyId);
        } else {
            // 入れ子区間モデルの左値・右値が存在しない場合
            $tOkrArray = $this->getOkrAndAllAlignmentOkrsByRecursion($tOkr);
        }

        // OKRの左値・右値を再設定
        $tOkrArrayCount = count($tOkrArray);
        for ($i = 0; $i < $tOkrArrayCount; ++$i) {
            // 入れ子区間モデルの左値と右値を更新
            $treeValues = $this->getLeftRightValues($tOkrArray[$i]->getParentOkr()->getOkrId(), $timeframeId);
            $tOkrArray[$i]->setTreeLeft($treeValues['tree_left']);
            $tOkrArray[$i]->setTreeRight($treeValues['tree_right']);

            $this->flush();
        }
    }

    /**
     * 左値・右値が存在しない指定OKRとそれに紐づくOKRを再帰処理により全て取得
     *
     * @param TOkr $tOkr 対象OKRエンティティ
     * @return array
     */
    public function getOkrAndAllAlignmentOkrsByRecursion(TOkr $tOkr): array
    {
        $parentOkrIdArray = array($tOkr->getOkrId());
        $tOkrArray = array($tOkr);
        $tOkrRepos = $this->getTOkrRepository();

        while (!empty($parentOkrIdArray)) {
            // 全ての子OKRを取得
            $childrenOkrArray = $tOkrRepos->getAllChildrenOkrsOfMultipleParentOkrs($parentOkrIdArray);

            // 子OKRエンティティとOKRIDを配列に格納
            $parentOkrIdArray = array();
            foreach ($childrenOkrArray as $childOkr) {
                $parentOkrIdArray[] = $childOkr->getOkrId();
                $tOkrArray[] = $childOkr;
            }
        }

        return $tOkrArray;
    }

    /**
     * ノードの左値・右値を取得する際に最左ノードまたは最右ノードをランダムに取得
     *
     * @param integer $parentOkrId 親OKRID
     * @param integer $timeframeId タイムフレームID
     * @return array
     */
    private function getLeftRightValues(int $parentOkrId, int $timeframeId): array
    {
        // 1または2をランダムに取得
        $rand = mt_rand(1, 2);
        $tOkrRepos = $this->getTOkrRepository();

        if ($rand === 1) {
            return $tOkrRepos->getLeftRightOfLeftestInsertionNode($parentOkrId, $timeframeId);
        } else {
            return $tOkrRepos->getLeftRightOfRightestInsertionNode($parentOkrId, $timeframeId);
        }
    }
}
