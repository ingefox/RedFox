<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="NotificationChannel")
 **/
class E_NotificationChannel extends RF_Entity
{
    protected $nullableProperties = ['id'];

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    

    /** @Column(type="integer") */
    protected $index;

     /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_Channel",fetch="EAGER")
     * @JoinColumn(name="channel",referencedColumnName="id")
     */
    protected $channel;

    /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_Notification",fetch="EAGER")
     * @JoinColumn(name="notification",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

}
