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
 * @uses \Phorrst\Exception\InvalidArgument
 */

namespace Phorrst\Request;

require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Exception/InvalidArgument.php';

use \Phorrst\Exception                  as Exception,
    \Phorrst\Exception\InvalidArgument  as ArgumentException;

/**
 * Client
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Client
{
    /**
     *
     * @var const
     */
    const GET = 'get';

    /**
     *
     * @var const
     */
    const POST = 'post';

    /**
     *
     * @var string
     */
    protected $_mode;

    /**
     *
     * @var array
     */
    protected $_parameters;

    /**
     *
     * @var string
     */
    protected $_responseHeader = null;

    /**
     *
     * @var array
     */
    protected $_uri = '';

    /**
     *
     * @return \Phorrst\Request\Client
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     *
     * @param string $uri
     * @return \Phorrst\Request\Client
     */
    public function setUri($uri)
    {
        ArgumentException::check(ArgumentException::STRING, $uri);

        $this->_uri = $uri;
        return $this;
    }

    /**
     *
     * @param string $mode
     * @return \Phorrst\Request\Client
     */
    public function setMode($mode = self::GET)
    {
        $this->_checkMode($mode);

        $this->_mode = $mode;
        return $this;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @param string $mode
     * @return \Phorrst\Request\Client
     */
    public function setParameter($key, $value, $mode = self::GET)
    {
        ArgumentException::check(array(
            ArgumentException::STRING, ArgumentException::INTEGER), $key);
        ArgumentException::check(array(
            ArgumentException::STRING, ArgumentException::INTEGER), $value);

        $this->_checkMode($mode);
        $this->_parameters[$mode][$key] = $value;

        return $this;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return \Phorrst\Request\Client
     */
    public function setParameterPost($key, $value)
    {
        return $this->setParameter($key, $value, self::POST);
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return \Phorrst\Request\Client
     */
    public function setParameterGet($key, $value)
    {
        return $this->setParameter($key, $value, self::GET);
    }

    /**
     *
     * @param string $mode
     * @return void
     */
    protected function _checkMode($mode)
    {
        ArgumentException::check(ArgumentException::STRING, $mode);

        if($mode != self::GET && $mode != self::POST) {
            throw new ArgumentException('No supported mode!');
        }
    }

    /**
     *
     * @return \Phorrst\Request\Client\Response
     */
    public function request()
    {
        $query = \http_build_query($this->_parameters[$this->_mode]);
        $uri   = $this->_uri;
        $curl  = \curl_init();
        
        switch ($this->_mode) {
            case self::POST:
                \curl_setopt($curl, \CURLOPT_POST, true);
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $query);
                break;
            default:
                $uri .= '?' . $query;
        }
        
        \curl_setopt_array($curl, array(
            \CURLOPT_URL => $uri,
            \CURLOPT_TIMEOUT => 60,
            \CURLOPT_CONNECTTIMEOUT => 6,
            \CURLOPT_HEADER => false,
            \CURLOPT_RETURNTRANSFER => true
        ));

        try {
            $data = \curl_exec($curl);
        } catch (\Exception $e) {
            throw new Exception('Curl threw an error: ' . $e->getMessage(), 500);
        }
        
        $code = \curl_getinfo($curl, \CURLINFO_HTTP_CODE);
        switch($code) {
            case 200:   return $data; break;
//            default:    throw new Exception('Not found!', $code);
        }

        return null;
    }
    
    /**
     *
     * @return \Phorrst\Request\Client
     */
    public function reset()
    {
        $this->_uri = '';
        $this->_responseHeader = null;
        $this->_mode =  self::GET;
        $this->_parameters = array(
            self::GET  => array(),
            self::POST => array()
        );

        return $this;
    }
}