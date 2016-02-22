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
 * Class PackageTest
 * @package JBZoo\PHPUnit
 */
class PackageTest extends PHPUnit
{
    public function testTranslate()
    {
        $langEn = new Lang('en');
        $langEn->load(PROJECT_TESTS . '/fixtures/path-1');
        isSame('Some message', $langEn->translate('message'));

        $langRu = new Lang('ru');
        $langRu->load(PROJECT_TESTS . '/fixtures/path-1');
        isSame('Какое-то сообщение', $langRu->translate('message'));
    }

    public function testTranslateOverloadPath()
    {
        $langEn = new Lang('en');
        $langEn->load(PROJECT_TESTS . '/fixtures/path-1');
        $langEn->load(PROJECT_TESTS . '/fixtures/path-2');

        isSame('Some message (overload)', $langEn->translate('message'));
    }

    public function testTranslateUndefined()
    {
        $lang = new Lang('en');
        $uniq = uniqid();
        isSame($uniq, $lang->translate($uniq));

        $lang = new Lang('en');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1');
        $uniq = uniqid();
        isSame($uniq, $lang->translate($uniq));
    }

    public function testModuleRegisterOnlyModule()
    {
        $lang = new Lang('en');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1', 'my_module'); // Default path will be registered automaticaly

        // Get global
        isSame('Some message', $lang->translate('message'));

        // Get from module (exists)
        isSame('Some module message (module)', $lang->translate('my_module.module_message'));

        // Get from module (not exists)
        isSame('my_module.another_message_undefined', $lang->translate('my_module.another_message_undefined'));

        // Try to get module key
        isSame('module_message', $lang->translate('module_message'));
    }

    public function testModuleRegisterBoth()
    {
        $lang = new Lang('en');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1', 'my_module');

        // Get global
        isSame('Some message', $lang->translate('message'));

        // Get from module (exists)
        isSame('Some module message (module)', $lang->translate('my_module.module_message'));

        // Get from module (not exists)
        isSame('my_module.another_message_undefined', $lang->translate('my_module.another_message_undefined'));

        // Try to get module key
        isSame('module_message', $lang->translate('module_message'));
    }

    public function testLoadpaths()
    {
        $lang = new Lang('en');
        isTrue($lang->load(PROJECT_TESTS . '/fixtures/path-1'));
        isFalse($lang->load(PROJECT_TESTS . '/fixtures/undefined/path/' . uniqid()));
    }

    /**
     * @expectedException \JBZoo\Lang\Exception
     */
    public function testLoadUndefinedFormat()
    {
        $lang = new Lang('en');
        $lang->load(PROJECT_TESTS . '/fixtures/path-1', 'test', 'qwerty');
    }

    /**
     * @expectedException \JBZoo\Lang\Exception
     */
    public function testInvalidCodeLength()
    {
        (new Lang('qwerty'));
    }

    /**
     * @expectedException \JBZoo\Lang\Exception
     */
    public function testInvalidCodeNums()
    {
        (new Lang('12'));
    }

    /**
     * @expectedException \JBZoo\Lang\Exception
     */
    public function testInvalidCodeEmpty()
    {
        (new Lang(null));
    }
}
