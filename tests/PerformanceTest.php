<?php
/**
 * JBZoo Lang
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Lang
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/Lang
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use JBZoo\Lang\Lang;

/**
 * Class PerformanceTest
 * @package JBZoo\PHPUnit
 */
class PerformanceTest extends PHPUnit
{
    public function testTranslate()
    {
        $lang = new Lang('en');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1');
        $lang->load(PROJECT_TESTS . '/fixtures/path-2');

        runBench([
            'Translate' => function () use ($lang) {
                return $lang->translate('message');
            },
        ], ['count' => 10000]);
    }
}
