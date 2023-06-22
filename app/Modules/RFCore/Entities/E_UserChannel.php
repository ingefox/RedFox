<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="UserChannel")
 **/
class E_UserChannel extends RF_Entity
{
    protected $nullableProperties = ['id'];

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

     /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_User",fetch="EAGER")
     * @JoinColumn(name="user",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="RFCore\Entities\E_Channel",fetch="EAGER")
     * @JoinColumn(name="channel",referencedColumnName="id")
     */
    protected $channel;

    /** @Column(type="integer") */
    protected $currentIndex;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return E_User
     */
    public function getUser(): E_User
    {
        return $this->user;
    }

    /**
     * @return E_Channel
     */
    public function getChannel(): E_Channel
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getCurrentIndex(): int
    {
        return $this->currentIndex;
    }
}
