<?php

namespace RFCore\Entities;

use Doctrine\{Common\Collections\ArrayCollection,
	ORM\Mapping\Column,
	ORM\Mapping\Entity,
	ORM\Mapping\Id,
	ORM\Mapping\UniqueConstraint};
use RFCore\Entities\RF_Entity;

/**
 * @Entity @Table(name="users", uniqueConstraints={@UniqueConstraint(name="user_email_unique", columns={"email"})})
 **/
class E_User extends RF_Entity
{
    protected $nullableProperties = ['id','securityToken','cookieToken','avatar','securityTokenExpiration','isActive','firstname','lastname','phone','password','CGUValidated','CGUValidatedDate','__initializer__'];

    public function update($params)
    {
        if (key_exists('password', $params) && !empty($params['password']))
        {
            $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
        }
        else
        {
            unset($params['password']);
        }
        parent::update($params);
    }

    public function __construct($params = null)
	{
		parent::__construct($params);
	}

	/** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY") **/
	protected $id;

    /** @Column(type="string") **/
    protected $email;

    /** @Column(type="string", nullable=true) **/
	protected $password;

    /** @Column(type="integer") **/
    protected $roles;

    /** @Column(type="string", nullable=true) **/
    protected $securityToken;

    /** @Column(type="string", nullable=true) **/
    protected $cookieToken;

    /** @Column(type="date", nullable=true) **/
    protected $securityTokenExpiration;

    /** @Column(type="boolean", nullable=true, options={"default" : "0"}) **/
    protected $isActive;

    /** @Column(type="string", nullable=true) **/
    protected $firstname;

    /** @Column(type="string", nullable=true) **/
    protected $lastname;

    /** @Column(type="string", nullable=true) **/
    protected $phone;

    /** @Column(type="boolean", nullable=true, options={"default" : "0"}) **/
    protected $CGUValidated;

    /** @Column(type="datetime", nullable=true) **/
    protected $CGUValidatedDate;

	/** @Column(type="string", nullable=true) **/
	protected $avatar;

    /**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
	 * @return mixed
	 */
	public function getSecurityToken()
	{
		return $this->securityToken;
	}

	/**
	 * @return mixed
	 */
	public function getSecurityTokenExpiration()
	{
		return $this->securityTokenExpiration;
	}

	/**
	 * @return mixed
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}

	/**
	 * @return mixed
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * @return mixed
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @return mixed
	 */
	public function getPhone()
	{
		return $this->phone;
	}

    /**
     * @return mixed
     */
    public function getCookieToken()
    {
        return $this->cookieToken;
    }

    /**
     * @return bool
     */
    public function isCGUValidated(): bool
    {
        return $this->CGUValidated;
    }

    /**
     * @return mixed
     */
    public function getCGUValidatedDate()
    {
        return $this->CGUValidatedDate;
    }

	/**
	 * @return mixed
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}
}
