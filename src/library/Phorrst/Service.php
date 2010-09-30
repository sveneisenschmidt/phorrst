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
 * @category Service
 * @package Phorrst
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses \Phorrst\Exception
 * @uses \Phorrst\Request
 * @uses \Phorrst\User
 */

namespace Phorrst;

require_once 'Phorrst/User.php';
require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Request.php';

use \Phorrst\Cache\AbstractCache as Cache;

/**
 * Service
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
final class Service
{
    /**
     *
     * @var const
     */
    const URI      = 'http://forrst.com';

    /**
     *
     * @var const
     */
    const TIMEZONE = 'Europe/Dublin'; // is GMT +0

    /**
     *
     * @var \Phorrst\Request
     */
    private $_request;
    /**
     *
     * @var \Phorrst\Cache\AbstractCache
     */
    private $_cache;

    /**
     *
     * @return \Phorrst\Request
     */
    public function setCache(Cache $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     *
     * @return \Phorrst\Cache\AbstractCache
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     *
     * @return \Phorrst\Request
     */
    public function getRequest()
    {
        if(null == $this->_request) {
            $this->_request = new Request($this);
        }

        return $this->_request;
    }

    /**
     * @param integer $id
     * @return \Phorrst\User
     */
    public function getUserById($id)
    {
        $data = $this->getRequest()->getUserById((int) $id);

        return User::create($data, $this);
    }

    /**
     *
     * @param string $name
     * @return \Phorrst\User
     */
    public function getUserByName($name)
    {
        $data = $this->getRequest()->getUserById((string) $name);

        return User::create($data, $this);
    }



    /**
     * @param string $username
     * @return \Phorrst\PostCollection
     */
    public function getPostsByUserName($username, $query = array())
    {
        require_once('Phorrst/Post/Collection.php');
        $data = $this->getRequest()->getPostsByUserName((string) $username, $query);

        return new \Phorrst\Post\Collection($data);
    }

    /**
     * @param integer $id
     * @return \Phorrst\PostCollection
     */
    public function getPostsByUserId($id,  $query = array())
    {
        require_once('Phorrst/Post/Collection.php');
        $data = $this->getRequest()->getPostsByUserId((string) $id, $query);

        return new \Phorrst\Post\Collection($data);
    }


}