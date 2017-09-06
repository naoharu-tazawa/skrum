<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Utils\Auth;
use AppBundle\Utils\DateUtility;
use AppBundle\Utils\DBConstant;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\MUser;
use AppBundle\Entity\TOkr;
use AppBundle\Entity\TOkrActivity;
use AppBundle\Api\ResponseDTO\OkrInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicOkrDTO;

/**
 * OKR設定サービスクラス
 *
 * @author naoharu.tazawa
 */
class OkrSettingService extends BaseService
{
    /**
     * OKRクローズ
     *
     * @param TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function closeOkr(TOkr $tOkr)
    {
        // クローズ対象OKRが既にクローズド状態の場合、更新処理を行わない
        if ($tOkr->getStatus() === DBConstant::OKR_STATUS_CLOSED) {
            return;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR更新
            $tOkr->setStatus(DBConstant::OKR_STATUS_CLOSED);

            // OKRアクティビティ登録（クローズ）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_CLOSE);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOkrActivity);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKRオープン
     *
     * @param TOkr $tOkr OKRエンティティ
     * @return void
     */
    public function openOkr(TOkr $tOkr)
    {
        // オープン対象OKRが既にオープン状態の場合、更新処理を行わない
        if ($tOkr->getStatus() === DBConstant::OKR_STATUS_OPEN) {
            return;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            // OKR更新
            $tOkr->setStatus(DBConstant::OKR_STATUS_OPEN);

            // OKRアクティビティ登録（オープン）
            $tOkrActivity = new TOkrActivity();
            $tOkrActivity->setOkr($tOkr);
            $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_OPEN);
            $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());
            $this->persist($tOkrActivity);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR公開設定変更
     *
     * @param TOkr $tOkr OKRエンティティ
     * @param string $disclosureType 公開種別
     * @return void
     */
    public function changeDisclosure(TOkr $tOkr, string $disclosureType)
    {
        // 更新対象OKRの公開種別とリクエストJSONで指定された公開種別が一致する場合、更新処理を行わない
        if ($tOkr->getDisclosureType() === $disclosureType) {
            return;
        }

        // OKR更新
        $tOkr->setDisclosureType($disclosureType);

        try {
            $this->flush();
        } catch (\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * OKR所有者変更
     *
     * @param TOkr $tOkr OKRエンティティ
     * @param string $ownerType オーナー種別
     * @param MUser $mUser ユーザエンティティ
     * @param MGroup $mGroup グループエンティティ
     * @param integer $companyId 会社ID
     * @return void
     */
    public function changeOwner(TOkr $tOkr, string $ownerType, MUser $mUser = null, MGroup $mGroup = null, int $companyId)
    {
        // 所有者変更対象OKRのキーリザルト（OKR種別＝'2' のみ）を取得
        $tOkrRepos = $this->getTOkrRepository();
        $tOkrArray = $tOkrRepos->getObjectiveAndKeyResults($tOkr->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $companyId, DBConstant::OKR_TYPE_KEY_RESULT);

        // OKR種別＝'1' のキーリザルトしかない場合、オーナー種別変更対象OKRのみ配列に入れる
        if (empty($tOkrArray)) {
            $tOkrArray[] = $tOkr;
        }

        // トランザクション開始
        $this->beginTransaction();

        try {
            foreach ($tOkrArray as $okrEntity) {
                // キーリザルト（OKR種別＝'1', '2'）が1つもない場合、配列の2要素目にnullが入っているので、処理終了
                if ($okrEntity === null) {
                    break;
                }

                // OKRアクティビティ登録（オーナー変更）
                $tOkrActivity = new TOkrActivity();
                $tOkrActivity->setOkr($okrEntity);
                $tOkrActivity->setType(DBConstant::OKR_OPERATION_TYPE_OWNER_CHANGE);
                $tOkrActivity->setActivityDatetime(DateUtility::getCurrentDatetime());

                // 変更前のオーナー種別によって処理を分岐
                if ($okrEntity->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                    // 「変更前オーナー種別＝1:ユーザ」の場合

                    if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                        // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝1:ユーザ」の場合

                        // オーナー変更がない場合は更新処理を行わない
                        if ($okrEntity->getOwnerUser()->getUserId() === $mUser->getUserId()) {
                            return;
                        }

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerUserId($okrEntity->getOwnerUser()->getUserId());
                        $tOkrActivity->setNewOwnerUserId($mUser->getUserId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerUser($mUser);

                    } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                        // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝2:グループ」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerUserId($okrEntity->getOwnerUser()->getUserId());
                        $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                        $okrEntity->setOwnerUser(null);
                        $okrEntity->setOwnerGroup($mGroup);

                    } else {
                        // 「変更前オーナー種別＝1:ユーザ」かつ「変更後オーナー種別＝3:会社」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerUserId($okrEntity->getOwnerUser()->getUserId());
                        $tOkrActivity->setNewOwnerCompanyId($companyId);
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                        $okrEntity->setOwnerUser(null);
                        $okrEntity->setOwnerCompanyId($companyId);
                        $okrEntity->setDisclosureType(DBConstant::OKR_DISCLOSURE_TYPE_OVERALL); // 公開種別を強制的に全体公開にする

                    }

                } elseif ($okrEntity->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                    // 「変更前オーナー種別＝2:グループ」の場合

                    if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                        // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝1:ユーザ」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerGroupId($okrEntity->getOwnerGroup()->getGroupId());
                        $tOkrActivity->setNewOwnerUserId($mUser->getUserId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                        $okrEntity->setOwnerGroup(null);
                        $okrEntity->setOwnerUser($mUser);

                    } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                        // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝2:グループ」の場合

                        // オーナー変更がない場合は更新処理を行わない
                        if ($okrEntity->getOwnerGroup()->getGroupId() === $mGroup->getGroupId()) {
                            return;
                        }

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerGroupId($okrEntity->getOwnerGroup()->getGroupId());
                        $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerGroup($mGroup);

                    } else {
                        // 「変更前オーナー種別＝2:グループ」かつ「変更後オーナー種別＝3:会社」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerGroupId($okrEntity->getOwnerGroup()->getGroupId());
                        $tOkrActivity->setNewOwnerCompanyId($companyId);
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_COMPANY);
                        $okrEntity->setOwnerGroup(null);
                        $okrEntity->setOwnerCompanyId($companyId);
                        $okrEntity->setDisclosureType(DBConstant::OKR_DISCLOSURE_TYPE_OVERALL); // 公開種別を強制的に全体公開にする

                    }

                } else {
                    // 「変更前オーナー種別＝3:会社」の場合

                    if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER) {
                        // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝1:ユーザ」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerCompanyId($okrEntity->getOwnerCompanyId());
                        $tOkrActivity->setNewOwnerUserId($mUser->getUserId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_USER);
                        $okrEntity->setOwnerCompanyId(null);
                        $okrEntity->setOwnerUser($mUser);

                    } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP) {
                        // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝2:グループ」の場合

                        // 変更前/変更後オーナー登録
                        $tOkrActivity->setPreviousOwnerCompanyId($okrEntity->getOwnerCompanyId());
                        $tOkrActivity->setNewOwnerGroupId($mGroup->getGroupId());
                        $this->persist($tOkrActivity);

                        // オーナー変更
                        $okrEntity->setOwnerType(DBConstant::OKR_OWNER_TYPE_GROUP);
                        $okrEntity->setOwnerCompanyId(null);
                        $okrEntity->setOwnerGroup($mGroup);

                    } else {
                        // 「変更前オーナー種別＝3:会社」かつ「変更後オーナー種別＝3:会社」の場合

                        // オーナー変更がないので更新処理を行わない
                        $tOkrActivity = null;
                        continue;
                    }
                }
            }

            // 目標の変更後オーナーと OKR種別＝'1' のキーリザルトのオーナーが同じ場合、当該キーリザルトのOKR種別を'2'にする
            $differentOwnerOkrArray = $tOkrRepos->getObjectiveAndKeyResults($tOkr->getOkrId(), $tOkr->getTimeframe()->getTimeframeId(), $companyId, DBConstant::OKR_TYPE_OBJECTIVE);
            foreach ($differentOwnerOkrArray as $differentOwnerOkr) {
                // キーリザルト（OKR種別＝'1', '2'）が1つもない場合、配列の2要素目にnullが入っているので、処理終了
                if ($differentOwnerOkr === null) {
                    break;
                }

                // オーナー変更対象OKRはスキップ
                if ($differentOwnerOkr->getOkrId() === $tOkr->getOkrId()) {
                    continue;
                }

                if ($ownerType === DBConstant::OKR_OWNER_TYPE_USER && $differentOwnerOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_USER) {
                    // 「変更後オーナー種別＝1:ユーザ」の場合
                    if ($differentOwnerOkr->getOwnerUser()->getUserId() === $mUser->getUserId()) {
                        $differentOwnerOkr->setType(DBConstant::OKR_TYPE_KEY_RESULT);
                    }
                } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP && $differentOwnerOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_GROUP) {
                    // 「変更後オーナー種別＝2:グループ」の場合
                    if ($differentOwnerOkr->getOwnerGroup()->getGroupId() === $mGroup->getGroupId()) {
                        $differentOwnerOkr->setType(DBConstant::OKR_TYPE_KEY_RESULT);
                    }
                } elseif ($ownerType === DBConstant::OKR_OWNER_TYPE_GROUP && $differentOwnerOkr->getOwnerType() === DBConstant::OKR_OWNER_TYPE_COMPANY) {
                    // 「変更後オーナー種別＝3:会社」の場合
                    if ($differentOwnerOkr->getCompanyId() === $companyId()) {
                        $differentOwnerOkr->setType(DBConstant::OKR_TYPE_KEY_RESULT);
                    }
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * KR加重平均割合更新
     *
     * @param Auth $auth 認証情報
     * @param array $data リクエストJSON連想配列
     * @param TOkr $okrEntity OKRエンティティ
     * @return OkrInfoDTO
     */
    public function updateRatio(Auth $auth, array $data, TOkr $okrEntity): OkrInfoDTO
    {
        // 親OKRIDを取得
        $parentOkrId = $okrEntity->getOkrId();

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 持分比率ロックフラグをリセットする
            $tOkrRepos = $this->getTOkrRepository();
            $tOkrRepos->resetRatioLockedFlg($okrEntity->getOkrId(), $okrEntity->getTimeframe()->getTimeframeId(), $auth->getCompanyId());

            // KR加重平均割合更新
            $weightedAverageRatioArray = array();
            foreach ($data as $item) {
                $tOkrArray = $tOkrRepos->getOkr($item['keyResultId'], $auth->getCompanyId());
                if (count($tOkrArray) !== 1) {
                    throw new ApplicationException('指定されたキーリザルトは存在しません');
                }

                // 親OKRIDが一致しない場合、エラー
                if ($tOkrArray[0]->getParentOkr()->getOkrId() !== $parentOkrId) {
                    throw new ApplicationException('指定されたキーリザルトが不正です');
                }

                $tOkrArray[0]->setWeightedAverageRatio($item['weightedAverageRatio']);
                $tOkrArray[0]->setRatioLockedFlg(DBConstant::FLG_TRUE);
                $this->flush();

                // KR加重平均割合を全て配列に格納
                $weightedAverageRatioArray[] = $item['weightedAverageRatio'];
            }

            // 持分比率ロックフラグを立てるKR加重平均割合の合計値が100を超えていないかチェック
            if (array_sum($weightedAverageRatioArray) > 100) {
                throw new ApplicationException('キーリザルト加重平均割合の合計値が100%を超えています');
            }

            // 達成率を再計算
            $okrAchievementRateLogic = $this->getOkrAchievementRateLogic();
            $okrAchievementRateLogic->recalculateFromParent($auth, $okrEntity, true);

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }

        // レスポンス用DTOを作成
        $okrInfoDTO = new OkrInfoDTO();
        $basicOkrDTOParentOkr = new BasicOkrDTO();
        $basicOkrDTOParentOkr->setOkrId($okrEntity->getOkrId());
        $basicOkrDTOParentOkr->setAchievementRate(round($okrEntity->getAchievementRate(), 1));
        $okrInfoDTO->setParentOkr($basicOkrDTOParentOkr);

        return $okrInfoDTO;
    }
}
