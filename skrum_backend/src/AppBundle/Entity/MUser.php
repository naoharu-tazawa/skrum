<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JSON;

/**
 * MUser
 *
 * @ORM\Table(name="m_user", indexes={@ORM\Index(name="idx_user_01", columns={"company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MUserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @JSON\ExclusionPolicy("all")
 */
class MUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=255, nullable=false)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     * @JSON\Expose()
     * @JSON\Type("integer")
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $phoneNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_access_datetime", type="datetime", nullable=true)
     * @JSON\Expose()
     * @JSON\Type("DateTime<'Y-m-d'>")
     */
    private $lastAccessDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=45, nullable=true)
     * @JSON\Expose()
     * @JSON\Type("string")
     */
    private $imagePath;

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
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JSON\Expose()
     * @JSON\Type("integer")
     */
    private $userId;

    /**
     * @var \AppBundle\Entity\MCompany
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="company_id")
     * })
     */
    private $company;



    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return MUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return MUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return MUser
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return MUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     *
     * @return MUser
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
     * Set position
     *
     * @param string $position
     *
     * @return MUser
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return MUser
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set lastAccessDatetime
     *
     * @param \DateTime $lastAccessDatetime
     *
     * @return MUser
     */
    public function setLastAccessDatetime($lastAccessDatetime)
    {
        $this->lastAccessDatetime = $lastAccessDatetime;

        return $this;
    }

    /**
     * Get lastAccessDatetime
     *
     * @return \DateTime
     */
    public function getLastAccessDatetime()
    {
        return $this->lastAccessDatetime;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return MUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MUser
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
     * @return MUser
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
     * @return MUser
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
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\MCompany $company
     *
     * @return MUser
     */
    public function setCompany(\AppBundle\Entity\MCompany $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\MCompany
     */
    public function getCompany()
    {
        return $this->company;
    }
}
