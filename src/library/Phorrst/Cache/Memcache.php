<?php
/**
 *
 * Copyright (c) 2010, Sven Eisenschmidt.
 * All rights reserved.
 *
 * Redistribution with or without modification, are permitted.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category Cache
 * @package Phorrst
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * Inspired by Doctrine2 AbstractCache
 * @see http://doctrine-project.org
 */

namespace Phorrst\Cache;

require_once 'Phorrst/Cache/AbstractCache.php';

/**
 * Memcache
 *
 * @category Cache
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Memcache extends AbstractCache
{
    /**
     * @var \Memcache
     */
    protected $_memcache;

    /**
     *
     * @param \Memcache $memcache
     * @return Phorrst\Cache\Memcache
     */
    public function __construct(\Memcache $memcache = null)
    {
        if(null !== $memcache) {
            $this->setMemcache($memcache);
        }
    }

    /**
     *
     * @param \Memcache $memcache
     */
    public function setMemcache(\Memcache $memcache)
    {
        $this->_memcache = $memcache;
    }

    /**
     *
     * @return Memcache
     */
    public function getMemcache()
    {
        return $this->_memcache;
    }

    /**
     *
     * @param string $id
     * @return string
     */
    protected function _doFetch($id)
    {
        return $this->_memcache->get($id);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    protected function _doContains($id)
    {
        return (bool) @$this->_memcache->get($id);
    }

    /**
     *
     * @param string $id
     * @param string|array $data
     * @param integer $lifeTime
     * @return string
     */
    protected function _doSave($id, $data, $lifeTime = false, $tags = null)
    {
        return $this->_memcache->set($id, $data, 0, $lifeTime);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    protected function _doDelete($id)
    {
        return $this->_memcache->delete($id);
    }
}