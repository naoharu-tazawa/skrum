<?php

namespace AppBundle\Api\ResponseDTO;

use JMS\Serializer\Annotation as JSON;

/**
 * ロールDTO

 * @JSON\ExclusionPolicy("none")
 */
class RoleDTO
{
    /**
     * @var integer
     *
     * @JSON\Type("integer")
     */
    private $roleAssignmentId;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $roleName;

    /**
     * Set roleAssignmentId
     *
     * @param integer $roleAssignmentId
     *
     * @return RoleDTO
     */
    public function setRoleAssignmentId($roleAssignmentId)
    {
        $this->roleAssignmentId = $roleAssignmentId;

        return $this;
    }

    /**
     * Get roleAssignmentId
     *
     * @return integer
     */
    public function getRoleAssignmentId()
    {
        return $this->roleAssignmentId;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     *
     * @return RoleDTO
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }
}
