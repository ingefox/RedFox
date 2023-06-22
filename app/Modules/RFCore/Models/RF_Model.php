<?php


namespace RFCore\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use Config\Services;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use RFCore\Entities\E_EventLog;

/**
 * Class RF_Model
 * Generic Model for all RedFox models
 * @package RFCore\Models
 */
class RF_Model extends Model
{
	protected $entityName = null;

    /** @var EntityManager|null  */
    public static $em = null;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        if (self::$em == null){
            self::$em = service('doctrine');
        }
    }

	/**
	 * Persist a new EventLog entity instance in the DB
	 * @param $eventData array New EventLog data
	 * @return int Status code
	 */
	public function saveEventLog(array $eventData): int
	{
		$ret = SC_INTERNAL_SERVER_ERROR;
		try {
			// Retrieving the currently logged user
			$eventData['loggedUser'] = $this->findOneBy('id',session()->get('id'),'RFCore\Entities\E_User');

			$eventLog = new E_EventLog($eventData);
			$this->persist($eventLog);
			$this->flush();

			$ret = SC_SUCCESS;
		}
		catch (Exception $e)
		{
			log_message('error', __CLASS__.'::'.__FUNCTION__.'() : '.$e);
		}

		return $ret;
	}

	/**
	 * Move and resize a given image
	 * @param $image UploadedFile The image to move and resize
	 * @param int $resX X resolution
	 * @param int $resY Y resolution
	 */
	public function handleImageFile(UploadedFile $image, int $resX = 200, int $resY = 200): string
	{
		// Generating a random filename and making sure that it is not already in use in the avatars folder
		$filename = $image->getRandomName();
		$fileAlreadyExists = file_exists(DEFAULT_AVATAR_FOLDER.DIRECTORY_SEPARATOR.$filename);

		// Looping until an available filename is found
		while ($fileAlreadyExists)
		{
			$filename = $image->getRandomName();
			$fileAlreadyExists = file_exists(DEFAULT_AVATAR_FOLDER.DIRECTORY_SEPARATOR.$filename);
		}

		// Moving the avatar file to its final location
		$image->move(DEFAULT_AVATAR_FOLDER,$filename);

		Services::image()
			->withFile(DEFAULT_AVATAR_FOLDER.DIRECTORY_SEPARATOR.$filename)
			->fit($resX, $resY, 'center')
			->save(DEFAULT_AVATAR_FOLDER.DIRECTORY_SEPARATOR.$filename);

		return $filename;
	}

    /**
     * Find one entity in DB by the given criteria
     * @param $property string|array Property name (criteria) or Search criteria
     * @param $value mixed Value to search for
     * @param string|null $entity
     * @return object|null Either the Entity object or null if not found
     */
    public function findOneBy($property, $value = null, ?string $entity = null): ?object
	{
        /** @var EntityRepository $repository */
        $repository = self::$em->getRepository($entity ?? $this->entityName);

        $ret = null;

        if (is_array($property))
        {
            $ret = $repository->findOneBy($property);
        }
        else{
            $ret = $repository->findOneBy(array($property => $value));
        }

        return $ret;
    }

    /**
     * Find one or more entities in DB by the given criteria
     * @param $property string Property name (criteria)
     * @param $value mixed Value to search for
     * @param string|null $entity
     * @return array Either the Entity object or null if not found
     */
    public function findMultiBy(string $property, $value, ?string $entity = null): array
	{
        /** @var EntityRepository $repository */
        $repository = self::$em->getRepository($entity ?? $this->entityName);
        return $repository->findBy(array($property => $value));
    }


    /**
	 * Find one or more entities in DB by multi given criteria
	 * @param array $data  Criteria array build like ["field"=>"value"]
	 * @param string|null $entity (Optional) Entity name
	 * @param array|null $orderBy Array containing information for ordering results (must be in this format ['field' => 'ASC', 'field2' => 'DESC']
     * @param int|null $limit (Optional)
     * @return array Either the Entity object or null if not found
     */
    public function findBy(array $data, ?string $entity = null, ?array $orderBy = null, ?int $limit = null): array
    {
        /** @var EntityRepository $repository */
        $repository = self::$em->getRepository($entity ?? $this->entityName);
        return $repository->findBy($data,$orderBy,$limit);
    }


    /**
     * Find all entities from the given repository name
     * @param $entity = null string Entity Repository name
     * @return array Collection of entities
     */
    public function findAllEntities($entity = null): array
	{
        /** @var EntityRepository $repository */
        $repository = self::$em->getRepository($entity ?? $this->entityName);
        return $repository->findAll();
    }

    /**
     * Flush the EntityManager
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(){
        self::$em->flush();
    }

    /**
     * Persist an entity in DB
     * @param $entity
     * @throws ORMException
     */
    public function persist($entity){
        self::$em->persist($entity);
    }

    /**
     * Remove an entity from DB
     * @param $entity
     * @throws ORMException
     */
    public function remove($entity){
        self::$em->remove($entity);
    }

    /**
     * Allow to update an Entity
     * @param $property string Entity property reference
     * @param $value mixed Value to look for
     * @param $params array Update data
     * @param string|null $entityName
     * @return int
     */
    public function updateEntity(string $property, $value, array $params, ?string $entityName = null): int
	{
        $ret = SC_INTERNAL_SERVER_ERROR;
        $entity = $this->findOneBy($property,$value,$entityName);
        if ($entity != null) {
            try {
                $entity->update($params);
                $this->flush();
                $ret = SC_SUCCESS;
            } catch (Exception $e) {
                log_message('error', __CLASS__.'::'.__FUNCTION__.'() : '.$e);
                $ret = SC_DOCTRINE_ENTITY_UPDATE_ERROR;
            }
        }
        else
		{
			$ret = SC_DOCTRINE_ENTITY_NOT_FOUND;
		}
        return $ret;
    }

    /**
     * Verify a ReCaptcha token
     * @param $token
     * @return bool
     */
    function checkReCaptcha($token): bool
	{
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $verifyResponse = file_get_contents($url.'?secret='.RECAPTCHA_SECRET_KEY.'&response='.$token);
        $response_final = json_decode($verifyResponse);
        return $response_final->success;
    }

	/**
	 * Function responsible for minifying a set of files (CSS + JS) from the {ROOT}/public/ folder
	 * @return int
	 */
	function minifyPublicFiles(): int
	{
		$ret = SC_INTERNAL_SERVER_ERROR;

		try
		{
			// CSS
			if (defined('CSS_FILES'))
			{
				// Retrieving the CSS files array defined in Constants.php of the integration module
				$CSSFiles = constant('CSS_FILES');

				// Adding each file to the minifier
				foreach ($CSSFiles as $file)
				{
					// Either adding the file to the minifier
					// or instantiating a new minifier instance with the current file path
					// (if no minifier has already been instantiated)
					if (!isset($CSSMinifier))
					{
						$CSSMinifier = new CSS($file);
					}
					else {
						$CSSMinifier->add($file);
					}
				}

				// If at least one file has been added to the minifier, a minified file will be generated
				if (isset($CSSMinifier))
				{
					$CSSMinifier->minify(ROOTPATH.'public'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'minified.css');
				}
			}

			// JS
			if (defined('JS_FILES'))
			{
				// Retrieving the JS files array defined in Constants.php of the integration module
				$JSFiles = constant('JS_FILES');

				// Adding each file to the minifier
				foreach ($JSFiles as $file)
				{
					// Either adding the file to the minifier
					// or instantiating a new minifier instance with the current file path
					// (if no minifier has already been instantiated)
					if (!isset($JSMinifier))
					{
						$JSMinifier = new JS($file);
					}
					else {
						$JSMinifier->add($file);
					}
				}

				// If at least one file has been added to the minifier, a minified file will be generated
				if (isset($JSMinifier))
				{
					$JSMinifier->minify(ROOTPATH.'public'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'minified.js');
				}
			}

			$ret = SC_SUCCESS;
		}
		catch (Exception $e)
		{
			log_message('error', __CLASS__.'::'.__FUNCTION__.'() : '.$e);
		}

		return $ret;
	}
}
