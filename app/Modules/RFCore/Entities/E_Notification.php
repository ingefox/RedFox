<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_NotificationRepository") @Table(name="notifications")
 **/
class E_Notification extends RF_Entity
{
    protected $nullableProperties = ['id'];

    /** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
    
    /** @column(type="date") */
    protected $date;

    /** @column(type="integer") */
    protected $level;

    /** @column(type="string") */
    protected $informations;
}
