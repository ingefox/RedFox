<?php

namespace RFCore\Entities;

use Doctrine\ORM\Mapping\Column,
    Doctrine\ORM\Mapping\Entity,
    Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(name="channels")
 **/
class E_Channel extends RF_Entity
{
    protected $nullableProperties = ['id','firstIndex','lastIndex'];

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="integer") **/
    protected $key;

    /** @Column(type="string") */
    protected $label;

     /** @Column(type="integer") */
     protected $firstIndex = 0;

     /** @Column(type="integer") */
     protected $lastIndex = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getKey(): int
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
     * @return int
     */
    public function getFirstIndex(): int
    {
        return $this->firstIndex;
    }

    /**
     * @return int
     */
    public function getLastIndex(): int
    {
        return $this->lastIndex;
    }
}
