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
 * @uses \Phorrst\Request\Client 
 * @uses \Phorrst\Exception\InvalidArgument
 */

namespace Phorrst;

require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Request/Client.php';
require_once 'Phorrst/Exception/InvalidArgument.php';

use \Phorrst\Service                    as Service,
    \Phorrst\Request\Client             as Client,
    \Phorrst\Exception\InvalidArgument  as ArgumentException;

/**
 * Request
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Request
{

    const API_URI = 'http://api.forrst.com/api/v%s/';

    /**
     *
     * @var integer
     */
    protected $_apiVersion = 1;
    /**
     *
     * @var \Phorrst\Service
     */
    protected $_service;
    
    /**
     *
     * @var \Phorrst\Request\Client
     */
    protected $_client;

    /**
     *
     * @var string
     */
    protected $_responseKey = 'resp';


    /**
     *
     * @return void
     */
    public function __construct(Service $service)
    {
        self::checkup();
        $this->_service = $service;
    }

    /**
     *
     * @return void
     */
    public static function checkup()
    {
        if(!function_exists('curl_version')) {
            throw new Exception('cURL is not enabled. Please activate the cURL Extension.');
        }
    }

    /**
     * @param string $key
     * @return \Phorrst\Request
     */
    public function setResponseKey($key)
    {
        ArgumentException::check(ArgumentException::STRING, $key);

        $this->_responseKey = $key;
        return $this;
    }

    /**
     * @param integer $id
     * @return string
     */
    public function getUserById($id)
    {
        ArgumentException::check(
                array(ArgumentException::STRING, ArgumentException::INTEGER), $id);

        return $this->getUserBy('id', $id);
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getUserByName($name)
    {
        ArgumentException::check(ArgumentException::STRING, $name);

        return $this->getUserBy('username', $name);
    }

    /**
     * @param integer $id
     * @return string
     */
    public function getPostsByUserId($id, $query = array())
    {
        ArgumentException::check(
                array(ArgumentException::STRING, ArgumentException::INTEGER), $id);
        
        return $this->getPostsByUserName(
            $this->getService()->getUserById($id)->getUsername());
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function getPostsByUserName($name, $query = array())
    {
        ArgumentException::check(ArgumentException::STRING, $name);
        return $this->getPostsBy('username', $name, $query);
    }

    /**
     *
     * @param string $key
     * @param integer|string $value
     * @return string
     */
    public function getUserBy($key, $value)
    {
        $cache = $this->hasCache() ? $this->getCache() : false;
        $id    = md5(\serialize(array($key, $value)));

        $client = $this->getClient()
                    ->reset()
                    ->setMode(Client::GET)
                    ->setParameterGet($key, $value)
                    ->setUri($this->getApiUri() . 'users/info');

        if($cache && $cache->contains($id)) {
            $response = $cache->fetch($id);
        } else {
            $response = $this->_fetch($client);
            if($cache && null !== $response) {
                $cache->save($id, $response);
            }
        }

        $object   = $this->decode($response);
        return $object->{$this->_responseKey}->{'user'};
    }

    /**
     *
     * @param string $key
     * @param integer|string $value
     * @return string
     */
    public function getPostsBy($key, $value, $query = array())
    {
        $query = array_merge(array(
            'since' => null
        ), $query);

        $cache = $this->hasCache() ? $this->getCache() : false;
        $id    = md5(\serialize(array($key, $value, $query)));

        $client = $this->getClient()
                    ->reset()
                    ->setMode(Client::GET)
                    ->setParameterGet($key, $value)
                    ->setUri($this->getApiUri() . 'users/posts');

        foreach($query as $paramKey => $paramValue) {
            if(null !== $paramValue) {
                $client->setParameterGet($paramKey, $paramValue);
            }
        }

        if($cache && $cache->contains($id)) {
            $response = $cache->fetch($id);
        } else {
            $response = $this->_fetch($client);
            if($cache && null !== $response) {
                $cache->save($id, $response);
            }
        }

        $object   = $this->decode($response);        
        return $object->{$this->_responseKey}->{'posts'};
    }

    /**
     *
     * @param \Phorrst\Request\Client $client
     * @return string|null
     */
    protected function _fetch(\Phorrst\Request\Client $client)
    {
        return $client->request();
    }

    /**
     *
     * @return \Phorrst\Request\Client
     */
    public function getClient()
    {
        if(null === $this->_client) {
            $this->_client = new Client();
        }

        return $this->_client;
    }

    /**
     *
     * @return \Phorrst\Service
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     *
     * @return \Phorrst\Cache\AbstractCache
     */
    public function getCache()
    {
        if(null === $this->getService()) {
            return null;
        }

        return $this->getService()->getCache();
    }

    /**
     *
     * @return boolean
     */
    public function hasCache()
    {
        return (bool)$this->getCache();
    }

    /**
     *
     * @return string
     */
    public function getApiUri()
    {
        return \vsprintf(self::API_URI, array($this->_apiVersion));
    }

    /**
     *
     * @param string $data
     * @return object
     */
    public function decode($data)
    {
        ArgumentException::check(ArgumentException::STRING, $data);

        $success = true;
        
        try {
            $object = \json_decode($data);
        } catch (\Exception $e) {
            $success = false;
        }

        if($success) {
            $success = is_object($object);
        }

        if($success == false) {
            throw new Exception('Response could not be encoded', 500);
        }

        if(!\property_exists($object, $this->_responseKey)) {
            $msg = 'The response seems to be invalid, ' .
                   'no key "%s" found. Maybe the api has changed?';
            
            throw new Exception(\vsprintf($msg ,array($this->_responseKey)), 500);
        }

        return $object;
    }


}