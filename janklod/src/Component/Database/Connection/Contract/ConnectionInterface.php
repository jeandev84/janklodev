<?php
namespace Jan\Component\Database\Connection\Contract;


/**
 * Interface ConnectionInterface
 *
 * @package Jan\Component\Database\Connection\Contract
*/
interface ConnectionInterface
{

   /**
     * open connection
     *
     * @param mixed $config
     * @return mixed
   */
   public function connect($config);





   /**
     * @return string
   */
   public function getName(): string;




   /**
    * Get connection driver example PDO(), mysqli() ...
    *
    * @return mixed
   */
   public function getDriverConnection();




   /**
     * @return bool
   */
   public function connected(): bool;




   /**
     * close connection
     *
     * @return mixed
   */
   public function disconnect();



   /**
     * @param string $sql
     * @return mixed
   */
   public function exec(string $sql);



    /**
     * Create a query.
     *
     * @param string $sql
     * @param array $params
     * @return QueryInterface
    */
    public function query(string $sql, array $params = []): QueryInterface;
}