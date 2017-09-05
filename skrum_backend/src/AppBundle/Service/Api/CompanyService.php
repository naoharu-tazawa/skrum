<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\PolicyDTO;

/**
 * 会社サービスクラス
 *
 * @author naoharu.tazawa
 */
class CompanyService extends BaseService
{
    /**
     * 会社基本情報取得
     *
     * @param integer $companyId 会社ID
     * @return BasicCompanyInfoDTO
     */
    public function getBasicCompanyInfo(int $companyId): BasicCompanyInfoDTO
    {
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($companyId);
        if ($mCompany === null) {
            throw new ApplicationException('会社が存在しません');
        }

        $basicCompanyInfoDTO = new BasicCompanyInfoDTO();
        $basicCompanyInfoDTO->setCompanyId($mCompany->getCompanyId());
        $basicCompanyInfoDTO->setName($mCompany->getCompanyName());
        $basicCompanyInfoDTO->setVision($mCompany->getVision());
        $basicCompanyInfoDTO->setMission($mCompany->getMission());

        return $basicCompanyInfoDTO;
    }

    /**
     * 会社名取得
     *
     * @param integer $companyId 会社ID
     * @return BasicCompanyInfoDTO
     */
    public function getSideBarCompany(int $companyId): BasicCompanyInfoDTO
    {
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($companyId);
        if ($mCompany === null) {
            throw new ApplicationException('会社が存在しません');
        }

        $policyDTO = new PolicyDTO();
        $policyDTO->setDefaultDisclosureType($mCompany->getDefaultDisclosureType());

        $basicCompanyInfoDTO = new BasicCompanyInfoDTO();
        $basicCompanyInfoDTO->setCompanyId($mCompany->getCompanyId());
        $basicCompanyInfoDTO->setName($mCompany->getCompanyName());
        $basicCompanyInfoDTO->setImageVersion($mCompany->getImageVersion());
        $basicCompanyInfoDTO->setPolicy($policyDTO);

        return $basicCompanyInfoDTO;
    }

    /**
     * 会社情報更新
     *
     * @param array $data リクエストJSON連想配列
     * @param integer $companyId 会社ID
     * @return void
     */
    public function updateCompany(array $data, int $companyId)
    {
        // 会社エンティティ取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($companyId);

        // トランザクション開始
        $this->beginTransaction();

        try {
            // 会社情報更新
            if (array_key_exists('name', $data) && !empty($data['name'])) {
                $data['name'] = str_replace('/', '', $data['name']);
                $mCompany->setCompanyName($data['name']);
            }
            if (array_key_exists('vision', $data)) {
                $mCompany->setVision($data['vision']);
            }
            if (array_key_exists('mission', $data)) {
                $mCompany->setMission($data['mission']);
            }

            // グループパス名を更新
            if (array_key_exists('name', $data) && !empty($data['name'])) {
                // 全てのグループツリーエンティティを取得
                $tGroupTreeRepos = $this->getTGroupTreeRepository();
                $tGroupTreeArray = $tGroupTreeRepos->getAllGroupPath($companyId);

                // グループパス名を更新
                foreach ($tGroupTreeArray as $tGroupTree) {
                    // グループパス名を'/'で分割し配列に格納
                    $groupTreePathNameItems = explode('/', $tGroupTree->getGroupTreePathName(), -1);

                    // グループパス名中のグループ名（会社名）を変更
                    $groupTreePathNameItems[0] = $data['name'];

                    // グループパスを再構成
                    $newGroupTreePathName = '';
                    foreach ($groupTreePathNameItems as $groupTreePathName) {
                        $newGroupTreePathName .= $groupTreePathName . '/';
                    }

                    // グループパス名を更新
                    $tGroupTree->setGroupTreePathName($newGroupTreePathName);
                    $this->flush();
                }
            }

            $this->flush();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new SystemException($e->getMessage());
        }
    }
}
