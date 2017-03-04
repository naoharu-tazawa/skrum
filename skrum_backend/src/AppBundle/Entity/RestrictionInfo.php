<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RestrictionInfo
 *
 * @ORM\Table(name="RESTRICTION_INFO")
 * @ORM\Entity
 */
class RestrictionInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ROLE_ID", type="integer", nullable=true)
     */
    private $roleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="TL_DISP_FLG", type="integer", nullable=false)
     */
    private $tlDispFlg = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="MAP_DISP_FLG", type="integer", nullable=false)
     */
    private $mapDispFlg = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="REST_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $restId;



    /**
     * Set roleId
     *
     * @param integer $roleId
     *
     * @return RestrictionInfo
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
     * Set tlDispFlg
     *
     * @param integer $tlDispFlg
     *
     * @return RestrictionInfo
     */
    public function setTlDispFlg($tlDispFlg)
    {
        $this->tlDispFlg = $tlDispFlg;

        return $this;
    }

    /**
     * Get tlDispFlg
     *
     * @return integer
     */
    public function getTlDispFlg()
    {
        return $this->tlDispFlg;
    }

    /**
     * Set mapDispFlg
     *
     * @param integer $mapDispFlg
     *
     * @return RestrictionInfo
     */
    public function setMapDispFlg($mapDispFlg)
    {
        $this->mapDispFlg = $mapDispFlg;

        return $this;
    }

    /**
     * Get mapDispFlg
     *
     * @return integer
     */
    public function getMapDispFlg()
    {
        return $this->mapDispFlg;
    }

    /**
     * Get restId
     *
     * @return integer
     */
    public function getRestId()
    {
        return $this->restId;
    }
}
