<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tree
 *
 * @ORM\Table(name="TREE", indexes={@ORM\Index(name="fk_TREE_OKR_STRUCTURE1_idx", columns={"OKR_ID"})})
 * @ORM\Entity
 */
class Tree
{
    /**
     * @var integer
     *
     * @ORM\Column(name="TREE_PARENT_ID", type="integer", nullable=true)
     */
    private $treeParentId;

    /**
     * @var float
     *
     * @ORM\Column(name="TREE_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $treeLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="TREE_RIGHIT", type="float", precision=10, scale=0, nullable=true)
     */
    private $treeRighit;

    /**
     * @var integer
     *
     * @ORM\Column(name="DELETED_FLG", type="integer", nullable=true)
     */
    private $deletedFlg;

    /**
     * @var integer
     *
     * @ORM\Column(name="TREE_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $treeId;

    /**
     * @var \AppBundle\Entity\OkrStructure
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\OkrStructure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OKR_ID", referencedColumnName="OKR_ID")
     * })
     */
    private $okr;



    /**
     * Set treeParentId
     *
     * @param integer $treeParentId
     *
     * @return Tree
     */
    public function setTreeParentId($treeParentId)
    {
        $this->treeParentId = $treeParentId;

        return $this;
    }

    /**
     * Get treeParentId
     *
     * @return integer
     */
    public function getTreeParentId()
    {
        return $this->treeParentId;
    }

    /**
     * Set treeLeft
     *
     * @param float $treeLeft
     *
     * @return Tree
     */
    public function setTreeLeft($treeLeft)
    {
        $this->treeLeft = $treeLeft;

        return $this;
    }

    /**
     * Get treeLeft
     *
     * @return float
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     * Set treeRighit
     *
     * @param float $treeRighit
     *
     * @return Tree
     */
    public function setTreeRighit($treeRighit)
    {
        $this->treeRighit = $treeRighit;

        return $this;
    }

    /**
     * Get treeRighit
     *
     * @return float
     */
    public function getTreeRighit()
    {
        return $this->treeRighit;
    }

    /**
     * Set deletedFlg
     *
     * @param integer $deletedFlg
     *
     * @return Tree
     */
    public function setDeletedFlg($deletedFlg)
    {
        $this->deletedFlg = $deletedFlg;

        return $this;
    }

    /**
     * Get deletedFlg
     *
     * @return integer
     */
    public function getDeletedFlg()
    {
        return $this->deletedFlg;
    }

    /**
     * Set treeId
     *
     * @param integer $treeId
     *
     * @return Tree
     */
    public function setTreeId($treeId)
    {
        $this->treeId = $treeId;

        return $this;
    }

    /**
     * Get treeId
     *
     * @return integer
     */
    public function getTreeId()
    {
        return $this->treeId;
    }

    /**
     * Set okr
     *
     * @param \AppBundle\Entity\OkrStructure $okr
     *
     * @return Tree
     */
    public function setOkr(\AppBundle\Entity\OkrStructure $okr)
    {
        $this->okr = $okr;

        return $this;
    }

    /**
     * Get okr
     *
     * @return \AppBundle\Entity\OkrStructure
     */
    public function getOkr()
    {
        return $this->okr;
    }
}
