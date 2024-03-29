<?php

namespace AppBundle\Utils;

/**
 * 認証情報保持クラス
 *
 * @author naoharu.tazawa
 */
class Auth
{
    /**
     * サブドメイン
     *
     * @var string
     */
    private $subdomain;

    /**
     * ユーザID
     *
     * @var integer
     */
    private $userId;

    /**
     * 会社ID
     *
     * @var integer
     */
    private $companyId;

    /**
     * ロールID
     *
     * @var string
     */
    private $roleId;

    /**
     * ロールレベル
     *
     * @var integer
     */
    private $roleLevel;

    /**
     * 権限情報
     *
     * @var array
     */
    private $permissions;

    /**
     * コンストラクタ
     *
     * @param string $subdomain サブドメイン
     * @param integer $userId ユーザID
     * @param integer $companyId 会社ID
     * @param string $roleId ロールID
     * @param integer $roleLevel ロールレベル
     * @param array $permissions  権限情報
     */
    public function __construct(string $subdomain, int $userId, int $companyId, string $roleId, int $roleLevel, array $permissions)
    {
        $this->subdomain = $subdomain;
        $this->userId = $userId;
        $this->companyId = $companyId;
        $this->roleId = $roleId;
        $this->roleLevel = $roleLevel;
        $this->permissions = $permissions;
    }

    /**
     * Get subdomain
     *
     * @return string
     */
    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Get roleId
     *
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->roleId;
    }

    /**
     * Get roleLevel
     *
     * @return integer
     */
    public function getRoleLevel(): int
    {
        return $this->roleLevel;
    }

    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}