<?php

/*
 * This file is part of the zhkugh/epdo.
 *
 * (c) zhkugh <zhkugh@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Zhkugh\Epdo;

use PDO;
use Zhkugh\Epdo\Exceptions\Exception;

class DB
{
    protected static $instance = null;

    public function __construct()
    {
    }

    public function __clone()
    {
    }

    /**
     * @return PDO
     *
     * @throws \Exception
     */
    public static function instance()
    {
        if (null === self::$instance) {
            if (!class_exists('PDO')) {
                throw new Exception('PDO class not exists .');
            }

            $configFile = __DIR__.'/../config/database.php';

            if (!file_exists($configFile)) {
                throw new Exception('database configuration file not found .');
            }

            $configs = require_once $configFile;

            $dsn = 'mysql:host='.$configs['host'].';dbname='.$configs['dbname'].';charset='.$configs['charset'];

            self::$instance = new PDO($dsn, $configs['username'], $configs['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);
        }

        return self::$instance;
    }

    /**
     * Proxy to native PDO static methods.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::instance(), $method], $args);
    }

    /**
     * Run SQL.
     *
     * @param $sql
     * @param array $args
     *
     * @return bool|PDOStatement
     *
     * @throws \Exception
     */
    public static function run($sql, $args = [])
    {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }

    /**
     * Create a new data on the table.
     *
     * @param $table
     * @param array $data
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @example  INSERT INTO {$table} SET field = :field, field2 = :field2;
     */
    public static function insert($table, $data = [])
    {
        if (empty($table) || empty($data)) {
            return false;
        }

        $sql = "INSERT INTO {$table} SET ";

        foreach (array_keys($data) as $v) {
            $sql .= $v.' =:'.$v.', ';
        }

        $sql = rtrim(trim($sql), ',');

        return self::run($sql, $data)->rowCount();
    }

    /**
     * Update records according to conditions.
     *
     * @param $table
     * @param array  $data
     * @param string $where
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @example UPDATE {$table} SET field = :field, field2 = :field2 where id=1;
     */
    public static function update($table, $data = [], $where = '')
    {
        if (empty($table) || empty($data) || empty($where)) {
            return false;
        }

        $sql = "UPDATE {$table} SET ";

        foreach (array_keys($data) as $v) {
            $sql .= $v.' = :'.$v.', ';
        }

        $sql = rtrim(trim($sql), ',');

        $sql .= (strstr($where, 'where') || strstr($where, 'WHERE')) ? " {$where}" : " WHERE {$where}";

        return self::run($sql, $data)->rowCount();
    }

    /**
     * Delete records according to conditions.
     *
     * @param $table
     * @param string $where
     *
     * @return bool DELETE FROM {$table} WHERE id=1;
     *
     * @throws \Exception
     */
    public static function delete($table, $where = '')
    {
        if (empty($table) || empty($where)) {
            return false;
        }

        $sql = "DELETE FROM {$table} ";

        $sql .= (strstr($where, 'where') || strstr($where, 'WHERE')) ? " {$where}" : " WHERE {$where}";

        return self::run($sql)->rowCount();
    }

    /**
     * Find a piece of data.
     *
     * @param $sql
     * @param array $parameters
     *
     * @return bool|mixed
     *
     * @throws \Exception
     */
    public static function find($sql, $parameters = [])
    {
        if (empty($sql)) {
            return false;
        }

        if (!strstr($sql, ';')) {
            $sql .= (strstr($sql, 'LIMIT') || strstr($sql, 'limit')) ? $sql : ' LIMIT 1';
        }

        return self::run($sql, $parameters)->fetch();
    }
}
