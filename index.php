<?php

/*
 * This file is part of the zhkugh/epdo.
 *
 * (c) zhkugh <zhkugh@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

$list = \Zhkugh\Epdo\DB::query('SELECT content FROM travis;');

exit(json_encode($list));
