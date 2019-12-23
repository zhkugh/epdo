<?php

/*
 * This file is part of the zhkugh/epdo.
 *
 * (c) zhkugh <zhkugh@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

require __DIR__.'/../vendor/autoload.php';

use Zhkugh\Epdo\DB;

$rowCount = DB::insert('travis', [
    'content' => 'test 2',
]);

echo "insert count {$rowCount}";
