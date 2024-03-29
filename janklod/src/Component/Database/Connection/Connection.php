<?php
namespace Jan\Component\Database\Connection;


use Jan\Component\Database\Connection\Contract\ConnectionInterface;
use Jan\Component\Database\Connection\Contract\QueryInterface;
use Jan\Component\Database\Connection\Exception\ConnectionException;


/**
 * Class Connection
 *
 * @package Jan\Component\Database\Connection
*/
abstract class Connection implements ConnectionInterface
{


    /**
     * @var Configuration
    */
    protected $config;



    /**
     * @var
    */
    protected $query;




    /**
     * @var mixed
    */
    protected $driver;




    /**
     * @param Configuration|null $config
    */
    public function __construct(Configuration $config = null)
    {
         if ($config) {
             $this->setConfiguration($config);
         }
    }




    /**
     * @param Configuration $config
    */
    public function setConfiguration(Configuration $config)
    {
        $this->config = $config;
    }



    /**
     * @return Configuration
    */
    public function getConfiguration(): Configuration
    {
        return $this->config;
    }




    /**
     * @param $driver
    */
    public function setDriverConnection($driver)
    {
        $this->driver = $driver;
    }



    /**
     * @return mixed
    */
    public function getDriverConnection()
    {
        return $this->driver;
    }




    /**
     * Get connection name
     *
     * @return string
     * @throws \Exception
    */
    public function getName(): string
    {
        throw new \Exception('unable connection name for connection ('. get_called_class() .')');
    }




    /**
     * @return mixed|null
    */
    protected function getUsername()
    {
        return $this->config['username'];
    }




    /**
     * @return mixed|null
    */
    protected function getPassword()
    {
        return $this->config['password'];
    }



    /**
     * Determine if, has connection.
     *
     * @return bool
    */
    abstract public function connected(): bool;




    /**
     * @return QueryInterface
    */
    abstract public function createQuery(): QueryInterface;



    /**
     * Create a query.
     *
     * @param string $sql
     * @param array $params
     * @return QueryInterface
    */
    public function query(string $sql, array $params = []): QueryInterface
    {
        $query = $this->createQuery();
        $query->query($sql);
        $query->params($params);

        return $query;
    }
}