<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoleMaster
 *
 * @ORM\Table(name="ROLE_MASTER", indexes={@ORM\Index(name="fk_ROLE_MASTER_RESTRICTION_INFO1_idx", columns={"REST_ID"})})
 * @ORM\Entity
 */
class RoleMaster
{
    /**
     * @var string
     *
     * @ORM\Column(name="ROLE_VALUE", type="string", length=45, nullable=false)
     */
    private $roleValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="ROLE_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $roleId;

    /**
     * @var \AppBundle\Entity\RestrictionInfo
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\RestrictionInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="REST_ID", referencedColumnName="REST_ID")
     * })
     */
    private $rest;



    /**
     * Set roleValue
     *
     * @param string $roleValue
     *
     * @return RoleMaster
     */
    public function setRoleValue($roleValue)
    {
        $this->roleValue = $roleValue;

        return $this;
    }

    /**
     * Get roleValue
     *
     * @return string
     */
    public function getRoleValue()
    {
        return $this->roleValue;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     *
     * @return RoleMaster
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set rest
     *
     * @param \AppBundle\Entity\RestrictionInfo $rest
     *
     * @return RoleMaster
     */
    public function setRest(\AppBundle\Entity\RestrictionInfo $rest)
    {
        $this->rest = $rest;

        return $this;
    }

    /**
     * Get rest
     *
     * @return \AppBundle\Entity\RestrictionInfo
     */
    public function getRest()
    {
        return $this->rest;
    }
}
