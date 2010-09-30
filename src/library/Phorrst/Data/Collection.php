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

use \Phorrst\Exception\InvalidArgument  as ArgumentException;

/**
 * Collection
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Collection implements \Iterator, \Countable, \ArrayAccess
{
    /**
     *
     * @var integer
     */
    protected $_key = 0;
    
    /**
     *
     * @var array
     */
    protected $_data = array();

    /**
     *
     * @param array $data
     * @return \Phorrst\Data\Collection
     */
    public function __construct(array $data = array())
    {
        $this->_data = $data;
    }

    /**
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     *
     * @return \Phorrst\Data\Object
     */
    public function current()
    {
        return $this->offsetGet($this->_key);
    }

    /**
     *
     * @return integer
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     *
     * @return \Phorrst\Data\Collection
     */
    public function next()
    {
        $this->_key++;
        return $this;
    }

    /**
     *
     * @return \Phorrst\Data\Collection
     */
    public function rewind()
    {
        return $this->reset();
    }

    /**
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->offsetExists($this->_key);
    }

    /**
     *
     * @param integer|string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     *
     * @param integer|string $offset
     * @return \Phorrst\Data\Object
     */
    public function offsetGet($offset)
    {
        return $this->parse($this->_data[$offset], $offset);
    }

    /**
     *
     * @param integer|string $offset
     * @param mixed $value
     * @return \Phorrst\Data\Collection
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
        return $this;
    }

    /**
     *
     * @param integer|string $offset
     * @return \Phorrst\Data\Collection
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
        return $this;
    }

    /**
     *
     * @param closure|function $callback
     * @return \Phorrst\Data\Collection
     */
    public function filter($callback)
    {
        if(!is_callable($callback)) {
            throw new ArgumentException('$callback is no function!', 500);
        }

        $iterator = new \IteratorIterator($this);
        foreach($iterator as $offset => $row) {
            if(!\call_user_func_array($callback, array($row))) {
                $this->offsetUnset($offset);
            }
        }
        
        return $this->reset();
    }

    /**
     *
     * @return \Phorrst\Data\Collection
     */
    public function reset()
    {
        $this->_data = \array_values($this->_data);
        $this->_key  = 0;

        return $this;
    }

    /**
     *
     * @param array|object $data
     * @param integer $key
     * @return \Phorrst\Data\Object
     */
    abstract function parse($data, $key);
}