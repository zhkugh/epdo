<?php

/*
 * This file is part of the zhkugh/epdo.
 *
 * (c) zhkugh <zhkugh@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Zhkugh\Epdo\Tests;

use PHPUnit\Framework\TestCase;
use Zhkugh\Epdo\DB;

class EpdoTest extends TestCase
{
    public function test_create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `travis` (
                   `id` INT(11) NOT NULL AUTO_INCREMENT,
                   `content` text,
                    PRIMARY KEY (`id`)
                )
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB
                    AUTO_INCREMENT=1
                ;";

        $this->assertSame(1, DB::query($sql) ? 1 : 0);
    }

    public function test_insert()
    {
        $rowCount = DB::insert('travis', [
            'content' => 'test',
        ]);

        $this->assertSame(1, $rowCount);
    }

    public function test_run()
    {
        /** @var \PDOStatement $PDOStatement */
        $PDOStatement = DB::run('SELECT * FROM travis;');

        $list = $PDOStatement->fetchAll();

        $this->assertGreaterThanOrEqual(1, count($list));
    }

    public function test_query()
    {
        /** @var \PDOStatement $PDOStatement */
        $PDOStatement = DB::run('SELECT * FROM travis;');

        $list = $PDOStatement->fetchAll();

        $this->assertGreaterThanOrEqual(1, count($list));
    }

    public function test_update()
    {
        $rowCount = DB::update('travis', [
            'content' => 'NEW VALUE',
        ], 'id=1');

        $this->assertSame(1, $rowCount);
    }

    public function test_find()
    {
        /** @var bool $bool */
        $travis = DB::find('SELECT content FROM travis WHERE id=1');

        $this->assertArrayHasKey('content', $travis);
    }

    public function test_delete()
    {
        /** @var bool $bool */
        $rowCount = DB::delete('travis', 'id=1');

        $this->assertSame(1, $rowCount);
    }
}
