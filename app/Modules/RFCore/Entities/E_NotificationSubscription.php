<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="notificationSubscriptions")
 **/
class E_NotificationSubscription extends RF_Entity
{
    protected $nullableProperties = ['id'];

    /** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
	 * @JoinColumn(name="user",referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/** @Column(type="text") */
	protected $subscriptionJSON;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return E_User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getSubscriptionJSON(): string
	{
		return $this->subscriptionJSON;
	}

	/**
	 * @return array
	 */
	public function getSubscriptionJSONDecoded(): array
	{
		return json_decode($this->subscriptionJSON,true);
	}
}
