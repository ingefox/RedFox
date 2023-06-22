<?php

namespace RFCore\Entities;

use Doctrine\{Common\Collections\ArrayCollection,
	ORM\Mapping\Column,
	ORM\Mapping\Entity,
	ORM\Mapping\Id,
	ORM\Mapping\JoinColumn,
	ORM\Mapping\ManyToOne,
	ORM\Mapping\UniqueConstraint};
use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use RFCore\Entities\RF_Entity;

/**
 * @Entity @Table(name="eventLogs")
 **/
class E_EventLog extends RF_Entity
{
	// EVENT LOG ENTITY PROPERTIES
	public const ELP_EVENT_TYPE = 'eventType';
	public const ELP_NEW_DATA = 'newData';
	public const ELP_OLD_DATA = 'oldData';
	public const ELP_TARGET_ENTITY = 'targetEntity';

	protected $nullableProperties = [
		'id',
		'loggedUser',
		'eventDate',
		'newData',
		'targetEntity',
		'oldData',
		'eventType'
	];

    public function __construct($params = null)
	{
		$this->eventDate = new DateTime();
		parent::__construct($params);
	}

	/** @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY") **/
	protected $id;

    /** @Column(type="datetime") **/
    protected $eventDate;

    /** @Column(type="integer") **/
    protected $eventType;

	/**
	 * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
	 * @JoinColumn(name="loggedUser",referencedColumnName="id", onDelete="SET NULL", nullable=true)
	 */
    protected $loggedUser;

    /** @Column(type="string", nullable=true) **/
    protected $targetEntity;

    /** @Column(type="text", nullable=true) **/
    protected $oldData;

    /** @Column(type="text", nullable=true) **/
    protected $newData;

    /**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return DateTime
	 */
	public function getEventDate(): DateTime
	{
		return $this->eventDate;
	}

	/**
	 * @return int
	 */
	public function getEventType()
	{
		return $this->eventType;
	}

	/**
	 * @return E_User|null
	 */
	public function getLoggedUser()
	{
		return $this->loggedUser;
	}

	/**
	 * @return string|null
	 */
	public function getTargetEntity()
	{
		return $this->targetEntity;
	}

	/**
	 * @return string|null
	 */
	public function getOldData()
	{
		return $this->oldData;
	}

	/**
	 * @return string|null
	 */
	public function getNewData()
	{
		return $this->newData;
	}
}
