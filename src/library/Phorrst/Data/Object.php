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
 * @uses \Phorrst\Exception\InvalidArgument
 */

namespace Phorrst\Data;

require_once 'Phorrst/Exception/InvalidArgument.php';

use \Phorrst\Service                    as Service,
    \Phorrst\Exception\InvalidArgument  as ArgumentException;


/**
 * DataObject
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Object
{
    /**
     *
     * @var \Phorrst\Service
     */
    protected $_service;

    /**
     *
     * @param object|array $data
     */
    public function __construct($data = array(), Service $service = null)
    {
        $this->setData($data);

        if(null !== $service) {
            $this->setService($service);
        }
    }

    /**
     *
     * @param \Phorrst\Service $service
     * @return \Phorrst\Data\Object
     */
    public function setService(Service $service)
    {
        $this->_service = $service;
        return $this;
    }

    /**
     *
     * @return \Phorrst\Service
     */
    public function getService()
    {
        if(null === $this->_service) {
            $this->_service = new Service();
        }

        return $this->_service;
    }

    /**
     *
     * @param object|array $data
     * @param Service $service
     * @return \Phorrst\Data\Object
     */
    public static function create($data = array(), Service $service = null)
    {
        return new static($data, $service);
    }

    /**
     *
     * @return \Phorrst\Service
     */
    public function setData($data)
    {
        ArgumentException::check(
            array(ArgumentException::HASH, ArgumentException::OBJECT), $data);

        if(is_object($data)) {
            $data = (array)$data;
        }

        $this->_data = $data;
        return $this;
    }

    /**
     *
     * @param string $key
     * @param boolean $toArray
     * @return array
     */
    public function getData($key = null, $toArray = false)
    {

        if(null === $key) {
            return $this->_data;
        }

        ArgumentException::check(ArgumentException::BOOLEAN, $toArray);
        ArgumentException::check(ArgumentException::STRING, $key);

        if(!\array_key_exists($key, $this->_data)) {
            return null;
        }

        $data = $this->_data[$key];

        if(true === $toArray && is_object($data)) {
            $data = (array)$data;
        } else
            
        if(is_numeric($data)) {
            $data = (int)$data;
        }

        return $data;
    }

    /**
     *
     * @return integer|null
     */
    public function getId()
    {
        $id = $this->getData('id');
        return is_numeric($id) ? (int)$id : null;
    }
}