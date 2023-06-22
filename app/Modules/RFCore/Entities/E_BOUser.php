<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_BOUserRepository") @Table(name="BOUsers", uniqueConstraints={@UniqueConstraint(name="BOuser_email_unique", columns={"email"})})
 **/
class E_BOUser extends RF_Entity
{
    protected $nullableProperties = ['id'];

    public function __construct($params = null)
	{
		parent::__construct($params);
	}

	public function update($params)
	{
		if (key_exists('password', $params)) $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
		parent::update($params);
	}

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="string")**/
	protected $email;

	/** @Column(type="string") **/
	protected $password;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email): void
	{
		$this->email = $email;
	}

	/**
	 * @param string $pass
	 */
	public function setPassword(string $pass)
	{
		$this->password = password_hash($pass, PASSWORD_BCRYPT);
	}

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function toJson(){
        $ret = array();
        $ret['id'] = $this->id;
        $ret['username'] = $this->email;
        return $ret;
    }
}
