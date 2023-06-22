<?php

namespace RFCore\Entities;

use Doctrine\{Common\Collections\ArrayCollection,
    ORM\Mapping\Column,
    ORM\Mapping\Entity,
    ORM\Mapping\Id,

    ORM\Mapping\JoinColumn,
    ORM\Mapping\JoinTable,
    ORM\Mapping\ManyToMany,
    ORM\Mapping\UniqueConstraint,
    ORM\PersistentCollection};

/**
 * @Entity(repositoryClass="RFCore\Repositories\R_APIRepository") @Table(name="API", uniqueConstraints={@UniqueConstraint(name="api_key_unique", columns={"key"})})
 **/
class E_API extends RF_Entity
{
    protected $nullableProperties = ['id','description', 'value'];
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") */
    protected $key;

    /** @Column(type="string", nullable=true) */
    protected $description;

    /** @Column(type="string", nullable=true) */
    protected $value;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
}