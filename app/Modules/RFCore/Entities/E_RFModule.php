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
 * @Entity(repositoryClass="RFCore\Repositories\R_RFModuleRepository") @Table(name="RFModules", uniqueConstraints={@UniqueConstraint(name="rfmodule_name_unique", columns={"name"})})
 **/
class E_RFModule extends RF_Entity
{
    protected $nullableProperties = ['id','Project_dependencies', 'Project_children', 'RF_dependencies', 'RF_children', 'releaseNote'];

    public function __construct($params)
	{
		$this->Project_dependencies = new ArrayCollection();
		$this->Project_children = new ArrayCollection();
        $this->RF_dependencies = new ArrayCollection();
        $this->RF_children = new ArrayCollection();
		parent::__construct($params);
	}

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="string") **/
	protected $name;

	/** @Column(type="string") **/
	protected $description;

	/** @Column(type="string") **/
	protected $version;

	/** @Column(type="string") */
	protected $releaseNote;

	/**
	 * @ManyToMany(targetEntity="RFCore\Entities\E_RFModule", inversedBy="RF_children", fetch="EAGER")
	 * @JoinTable(name="RFModulesRFDependencies",
	 *      joinColumns={@JoinColumn(name="id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="RF_dependency_id", referencedColumnName="id")}
	 *      )*/
	protected $RF_dependencies;

	/** @ManyToMany(targetEntity="RFCore\Entities\E_RFModule", mappedBy="RF_dependencies") */
	protected $RF_children;

	/**
	 * @ManyToMany(targetEntity="RFCore\Entities\E_RFModule", inversedBy="Project_children")
	 * @JoinTable(name="RFModulesProjectDependencies",
	 *      joinColumns={@JoinColumn(name="id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="Project_dependency_id", referencedColumnName="id")}
	 *      )*/
	protected $Project_dependencies;

	/** @ManyToMany(targetEntity="RFCore\Entities\E_RFModule", mappedBy="Project_dependencies") */
	protected $Project_children;

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name): void
	{
		$this->name = $name;
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

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion($version): void
	{
		$this->version = $version;
	}

    /**
     * @return mixed
     */
    public function getReleaseNote()
    {
        return $this->releaseNote;
    }

    /**
     * @param mixed $releaseNote
     */
    public function setReleaseNote($releaseNote): void
    {
        $this->releaseNote = $releaseNote;
    }

    /**
     * @return mixed
     */
    public function getRFDependencies()
    {
        return $this->RF_dependencies;
    }

    /**
     * @param ArrayCollection $RF_dependencies
     */
    public function setRFDependencies($RF_dependencies): void
    {
        $this->RF_dependencies = $RF_dependencies;
    }

    /**
     * @return ArrayCollection
     */
    public function getRFChildren()
    {
        return $this->RF_children;
    }

    /**
     * @param ArrayCollection $RF_children
     */
    public function setRFChildren($RF_children): void
    {
        $this->RF_children = $RF_children;
    }

    /**
     * @return ArrayCollection
     */
    public function getProjectDependencies()
    {
        return $this->Project_dependencies;
    }

    /**
     * @param ArrayCollection $Project_dependencies
     */
    public function setProjectDependencies($Project_dependencies): void
    {
        $this->Project_dependencies = $Project_dependencies;
    }

    /**
     * @return ArrayCollection
     */
    public function getProjectChildren()
    {
        return $this->Project_children;
    }

    /**
     * @param ArrayCollection $Project_children
     */
    public function setProjectChildren($Project_children): void
    {
        $this->Project_children = $Project_children;
    }

    public function toJson(){
        $ret = array();
        $ret["id"] = $this->id;
        $ret["name"] = $this->name;
        $ret["description"] = $this->description;
        $ret["version"] = $this->version;
        $ret["release_note"] = $this->releaseNote;
        if($this->RF_dependencies->count() > 0) {
            $dependencies = "";
            /** @var E_RFModule $dependency */
            foreach ($this->RF_dependencies->toArray() as $dependency) {
                if(empty($dependencies)) $dependencies .= $dependency->getName();
                else $dependencies .= ", ".$dependency->getName();
            }
            $ret["RF_dependencies"] = $dependencies;
        } else $ret["RF_dependencies"] = "";
        if($this->Project_dependencies->count() > 0) {
            /** @var E_RFModule $dependency */
            foreach ($this->Project_dependencies->toArray() as $dependency) {
                $ret["Project_dependencies"][] = $dependency->getName();
            }
        } else $ret["Project_dependencies"] = "";
        return $ret;
    }
}
