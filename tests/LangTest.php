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

use JBZoo\Lang\Exception;

/**
 * Class PackageTest
 * @package JBZoo\PHPUnit
 */
class PackageTest extends PHPUnit
{
    /**
     * @expectedException \JBZoo\Lang\Exception
     */
    public function testShouldShowException()
    {
        throw new Exception('Test message');
    }
}
