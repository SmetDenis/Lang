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

namespace JBZoo\Lang;

use JBZoo\Data\Data;
use JBZoo\Data\Ini;
use JBZoo\Data\JSON;
use JBZoo\Data\PHPArray;
use JBZoo\Data\Yml;
use JBZoo\Path\Path;

/**
 * Class Lang
 * @package JBZoo\Lang
 */
class Lang
{
    const DEFAULT_FORMAT = 'php';
    const DEFAULT_MODULE = '_global';

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var Path
     */
    protected $_path;

    /**
     * @var Data
     */
    protected $_storage;

    /**
     * @param $code
     */
    public function __construct($code)
    {
        $this->_code    = self::_cleanCode($code);
        $this->_path    = Path::getInstance(uniqid('lang_' . $code, true));
        $this->_storage = new Data();
    }

    /**
     * @param string|array $path
     * @param string|null  $module
     * @param string       $format
     * @return bool
     *
     * @throws Exception
     * @throws \JBZoo\Path\Exception
     */
    public function load($path, $module = self::DEFAULT_MODULE, $format = self::DEFAULT_FORMAT)
    {
        $module = $this->_cleanMessage($module);
        $format = $this->_clean($format);
        $path   = realpath($path);

        if ($path && !$this->_storage->get($module . '.' . $format)) {
            $this->_path->add($path, $module);

            if (!in_array($format, ['ini', 'yml', 'php', 'json'], true)) {
                throw new Exception('Undefined format: ' . $format);
            }

            $this->_storage->set($module, $format);
            if ($module !== self::DEFAULT_MODULE) {
                $this->load($path, self::DEFAULT_MODULE, $format);
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $origMessage
     * @return string
     */
    public function translate($origMessage)
    {
        $origMessage = $this->_cleanMessage($origMessage);

        if (false === strpos($origMessage, '.')) {
            $module  = self::DEFAULT_MODULE;
            $message = $origMessage;
        } else {
            list($module, $message) = explode('.', $origMessage, 2);
        }

        $format = $this->_storage->get($module, self::DEFAULT_FORMAT);

        $list = $this->_listFactory($module, $format);
        if ($text = $list->get($message)) {
            return (string)$text;
        }

        $listDefault = $this->_listFactory(self::DEFAULT_MODULE, $format);

        return (string)$listDefault->get($message, $origMessage);
    }

    /**
     * @param string $code
     * @return string
     * @throws Exception
     */
    protected static function _cleanCode($code)
    {
        $code = preg_replace('#[^a-zA-Z]#', '', $code);
        $code = trim(strtolower($code));
        if (strlen($code) !== 2) {
            throw new Exception('Lanuage code is only two chars: ' . $code);
        }

        return $code;
    }

    /**
     * @param string $message
     * @return string
     */
    protected function _cleanMessage($message)
    {
        $message = preg_replace('#[^a-z0-9_\.]#i', '', $message);
        $message = $this->_clean($message);

        return $message;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function _clean($string)
    {
        return trim(strtolower($string));
    }

    /**
     * @param string $module
     * @param string $format
     * @return Data
     */
    protected function _listFactory($module, $format)
    {
        $key = $module . '.' . $format;
        if ($list = $this->_storage->get($key)) {
            return $list;
        }

        if ($module === self::DEFAULT_MODULE) {
            $path = $module . ':langs/' . $this->_code . '.' . $format;
        } else {
            $path = $module . ':langs/' . $this->_code . '-' . $module . '.' . $format;
        }
        $listPath = $this->_path->get($path);

        // @codeCoverageIgnoreStart
        if ('php' === $format) {
            $list = new PHPArray($listPath);

        } elseif ('json' === $format) {
            $list = new JSON($listPath);

        } elseif ('ini' === $format) {
            $list = new Ini($listPath);

        } elseif ('yml' === $format) {
            $list = new Yml($listPath);

        } else {
            $list = new Data([]);
        }
        // @codeCoverageIgnoreEnd

        $this->_storage->set($key, $list);

        return $list;
    }
}
