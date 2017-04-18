<?php

namespace AppBundle\Logic;

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
     * @param \AppBundle\Entity\TOkr $tOkr 挿入対象OKRエンティティ
     * @parame integer $companyId 会社ID
     * @return void
     */
    public function recalculate($tOkr, $companyId)
    {
        // タイムフレームIDを取得
        $timeframeId = $tOkr->getTimeframe()->getTimeframeId();

        // 親OKR存在チェック
        if ($tOkr->getParentOkr() == null) {
            return;
        }

        // 指定OKRとそれに紐づくOKRを全て取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getOkrAndAllAlignmentOkrs($tOkr->getTreeLeft(), $tOkr->getTreeRight(), $timeframeId, $companyId);

        // OKRの左値・右値を再設定
        for ($i = 0; $i < count($tOkrArray); $i++) {
            // 入れ子区間モデルの左値と右値を更新
            $treeValues = $this->getLeftRightValues($tOkrArray[$i]->getParentOkr()->getOkrId(), $timeframeId);
            $tOkrArray[$i]->setTreeLeft($treeValues['tree_left']);
            $tOkrArray[$i]->setTreeRight($treeValues['tree_right']);

            $this->flush();
        }
    }

    /**
     * ノードの左値・右値を取得する際に最左ノードまたは最右ノードをランダムに取得
     *
     * @param integer $parentOkrId 親OKRID
     * @return void
     */
    private function getLeftRightValues($parentOkrId, $timeframeId)
    {
        // 1または2をランダムに取得
        $rand = mt_rand(1, 2);
        $tOkrRepos = $this->getTOkrRepository();

        if ($rand == 1) {
            return $tOkrRepos->getLeftRightOfLeftestInsertionNode($parentOkrId, $timeframeId);
        } else {
            return $tOkrRepos->getLeftRightOfRightestInsertionNode($parentOkrId, $timeframeId);
        }
    }
}
