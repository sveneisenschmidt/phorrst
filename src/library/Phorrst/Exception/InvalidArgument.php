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
 */

namespace Phorrst\Exception;

require_once 'Phorrst/Exception.php';

/**
 * InvalidArgument
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class InvalidArgument extends \Phorrst\Exception
{
    /**
     *
     * @var const
     */
    const INTEGER = 'integer';
    /**
     *
     * @var const
     */
    const BOOLEAN = 'boolean';
    
    /**
     *
     * @var const
     */
    const STRING  = 'string';

    /**
     *
     * @var const
     */
    const OBJECT  = 'object';

    /**
     *
     * @var const
     */
    const HASH  = 'array';

    /**
     *
     *
     * @param string $type
     * @param mixed $var
     * @param integer $index
     * @param integer $code
     * @return self
     */
    public static function check($type, $var, $index = 1, $code = 500)
    {
        if(!\is_array($type)) {
            $type = array($type);
        }

        if(\in_array(\gettype($var), $type)) {
            return null;
        }

        $msg = \vsprintf('Argument #%s passed-in is no %s. Instead it is: %s', array(
            $index, \implode('|', $type), \gettype($var)
        ));

        throw new self($msg, $code);
    }
}