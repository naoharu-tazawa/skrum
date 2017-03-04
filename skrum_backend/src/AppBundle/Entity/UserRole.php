<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRole
 *
 * @ORM\Table(name="USER_ROLE", indexes={@ORM\Index(name="fk_USER_ROLE_USER1_idx", columns={"USER_ID"}), @ORM\Index(name="fk_USER_ROLE_ROLE_MASTER1_idx", columns={"ROLE_ID"})})
 * @ORM\Entity
 */
class UserRole
{
    /**
     * @var integer
     *
     * @ORM\Column(name="USER_ROLE_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userRoleId;

    /**
     * @var \AppBundle\Entity\RoleMaster
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RoleMaster")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ROLE_ID", referencedColumnName="ROLE_ID")
     * })
     */
    private $role;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="USER_ID", referencedColumnName="USER_ID")
     * })
     */
    private $user;



    /**
     * Get userRoleId
     *
     * @return integer
     */
    public function getUserRoleId()
    {
        return $this->userRoleId;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\RoleMaster $role
     *
     * @return UserRole
     */
    public function setRole(\AppBundle\Entity\RoleMaster $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\RoleMaster
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserRole
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
