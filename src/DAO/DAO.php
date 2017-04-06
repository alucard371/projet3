<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 27/03/2017
 * Time: 14:52
 */

namespace microCMS\DAO;

use Doctrine\DBAL\Connection;

abstract class DAO
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $db;

    /**
     * DAO constructor.
     * @param \Doctrine\DBAL\Connection the database connection object
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Grants access to the database connection object
     *
     * @return \Doctrine\DBAL\Connection the database connection object
     */
    protected function getDb()
    {
        return $this->db;
    }

    /**
     * Builds a domain object from a DB row
     *
     * Must be overridden by child classes
     */
    protected abstract function buildDomainObject(array $row);
}