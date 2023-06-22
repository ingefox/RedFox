<?php


namespace RFCore\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use RFCore\Entities\RF_Entity;

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_ProjectConfigRepository") @Table(name="ProjectConfig")
 */
class E_ProjectConfig extends RF_Entity
{
	protected $nullableProperties = ['id'];

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="integer") **/
	protected $key;

	/** @Column(type="string") **/
	protected $label;

	/** @Column(type="text") **/
	protected $value;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
}
