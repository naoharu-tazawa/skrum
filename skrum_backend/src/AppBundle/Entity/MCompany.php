<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * MCompany
 *
 * @ORM\Table(name="m_company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MCompanyRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class MCompany
{
    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="vision", type="string", length=255, nullable=true)
     */
    private $vision;

    /**
     * @var string
     *
     * @ORM\Column(name="mission", type="string", length=255, nullable=true)
     */
    private $mission;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=45, nullable=true)
     */
    private $imagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="default_disclosure_type", type="string", length=2, nullable=true)
     */
    private $defaultDisclosureType;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_domain", type="string", length=45, nullable=true)
     */
    private $subDomain;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="company_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $companyId;



    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return MCompany
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set vision
     *
     * @param string $vision
     *
     * @return MCompany
     */
    public function setVision($vision)
    {
        $this->vision = $vision;

        return $this;
    }

    /**
     * Get vision
     *
     * @return string
     */
    public function getVision()
    {
        return $this->vision;
    }

    /**
     * Set mission
     *
     * @param string $mission
     *
     * @return MCompany
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return string
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return MCompany
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set defaultDisclosureType
     *
     * @param string $defaultDisclosureType
     *
     * @return MCompany
     */
    public function setDefaultDisclosureType($defaultDisclosureType)
    {
        $this->defaultDisclosureType = $defaultDisclosureType;

        return $this;
    }

    /**
     * Get defaultDisclosureType
     *
     * @return string
     */
    public function getDefaultDisclosureType()
    {
        return $this->defaultDisclosureType;
    }

    /**
     * Set subDomain
     *
     * @param string $subDomain
     *
     * @return MCompany
     */
    public function setSubDomain($subDomain)
    {
        $this->subDomain = $subDomain;

        return $this;
    }

    /**
     * Get subDomain
     *
     * @return string
     */
    public function getSubDomain()
    {
        return $this->subDomain;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MCompany
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return MCompany
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return MCompany
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Get companyId
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }
}
