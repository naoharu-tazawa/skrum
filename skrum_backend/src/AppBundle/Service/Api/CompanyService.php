<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\BaseService;
use AppBundle\Exception\ApplicationException;
use AppBundle\Exception\SystemException;
use AppBundle\Entity\MGroup;
use AppBundle\Entity\TGroupTree;
use AppBundle\Api\ResponseDTO\NestedObject\BasicGroupInfoDTO;
use AppBundle\Api\ResponseDTO\NestedObject\GroupPathDTO;
use AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO;

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
     * @return \AppBundle\Api\ResponseDTO\NestedObject\BasicCompanyInfoDTO
     */
    public function getBasicCompanyInfo($companyId)
    {
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($companyId);
        if ($mCompany == null) {
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
     * 会社情報更新
     *
     * @param array $data リクエストJSON連想配列
     * @param integer $companyId 会社ID
     * @return void
     */
    public function updateCompany($data, $companyId)
    {
        // 会社エンティティ取得
        $mCompanyRepos = $this->getMCompanyRepository();
        $mCompany = $mCompanyRepos->find($companyId);

        // 会社情報更新
        $mCompany->setCompanyName($data['companyName']);
        $mCompany->setVision($data['vision']);
        $mCompany->setMission($data['mission']);

        try {
            $this->flush();
        } catch(\Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }
}
