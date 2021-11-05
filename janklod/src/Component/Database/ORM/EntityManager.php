<?php
namespace Jan\Component\Database\ORM;


use Exception;
use InvalidArgumentException;
use Jan\Component\Database\ORM\Query\QueryBuilder;
use Jan\Component\Database\ORM\Query\QueryBuilderFactory;
use Jan\Component\Database\ORM\Records\Persistence;
use Jan\Component\Database\ORM\Records\Deletion;
use Jan\Component\Database\Connection\Configuration;
use Jan\Component\Database\Connection\PDO\PdoConnection;
use Jan\Component\Database\ORM\Contract\EntityManagerInterface;
use Jan\Component\Database\ORM\Repository\EntityRepository;
use PDO;



/**
 * Class EntityManager
 *
 * @package Jan\Component\Database\ORM
*/
class EntityManager implements EntityManagerInterface
{

    /**
     * @var PDO
    */
    protected $connection;



    /**
     * @var string
    */
    protected $classMap;




    /**
     * @var Persistence
    */
    protected $persistence;



    /**
     * @var Deletion
    */
    protected $deletion;




    /**
     * @var array
    */
    protected $metas = [];





    /**
     * @param PdoConnection $connection
    */
    public function __construct(PdoConnection $connection)
    {
          $this->connection  = $connection;
          $this->persistence = new Persistence($this);
          $this->deletion    = new Deletion($this);
    }





    /**
     * @return Persistence
    */
    public function getPersistence(): Persistence
    {
        return $this->persistence;
    }




    /**
     * @return Deletion
    */
    public function getDeletion(): Deletion
    {
        return $this->deletion;
    }




    /**
     * Register entity class
     *
     * @param string $entity
     * @param EntityRepository $repository
    */
    public function map(string $entity, EntityRepository $repository)
    {
         $this->metas[$entity] = $repository;
    }



    /**
     * @param string $classMap
    */
    public function registerClassMap(string $classMap)
    {
         $this->classMap = $classMap;
    }



    /**
     * @return string
    */
    public function getReferenceClass(): string
    {
        return $this->classMap;
    }





    /**
     * @return array
    */
    public function getMetas(): array
    {
        return $this->metas;
    }




    /**
     * Get repository
     *
     * @param string $entity
     * @return EntityRepository
    */
    public function getRepository(string $entity): EntityRepository
    {
        if (! isset($this->metas[$entity])) {
            throw new InvalidArgumentException('Cannot resolve entity ('. $entity . ') from storage repository.');
        }

        return $this->metas[$entity];
    }



    /**
     * @return PdoConnection
    */
    public function getPdoConnection()
    {
        return $this->connection;
    }




    /**
     * @return PDO
     * @throws Exception
    */
    public function getConnection(): PDO
    {
        return $this->connection->getPdo();
    }





    /**
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
        return $this->connection->getConfiguration();
    }






    /**
     * @param string $sql
     * @return mixed
    */
    public function exec(string $sql)
    {
       return $this->connection->exec($sql);
    }



    /**
     * @param object $object
    */
    public function persist($object)
    {
         $this->persistence->persist($object);
    }



    /**
     * prepare object to remove
     *
     * @param object $object
     * @return void
    */
    public function remove($object)
    {
        $this->deletion->remove($object);
    }




    /**
     * @return array
    */
    public function getRecordHandlers(): array
    {
        return [$this->getPersistence(), $this->getDeletion()];
    }




    /**
     * Flush to database
     *
     * @return void
    */
    public function flush()
    {
        foreach ($this->getRecordHandlers() as $flushable) {
            $flushable->execute();
        }
    }



    /**
     * @return QueryBuilder
     * @throws Exception
    */
    public function createQueryBuilder(): QueryBuilder
    {
        /** @var QueryBuilder $qb */
        $qb = QueryBuilderFactory::make($this);

        if ($this->classMap) {
            $qb->setOption('referenceClass', $this->getReferenceClass());
        }


        return $qb;
    }
}


/*
$users = $repository->findAll();
dump($users);

$i = uniqid();
foreach ($users as $user) {
    $email = 'f'. $i .'@gmail.com';
    $user->setEmail($email);
    $user->setUsername($email);
    $i++;
}


$u = $repository->findOneBy(['id' => 9]);

$this->em->remove($u);

$this->em->flush();
*/