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
 * @uses \Phorrst\Post\AbstractPost
 * @uses \Phorrst\Exception  
 * @uses \Phorrst\Exception\InvalidArgument
 */

namespace Phorrst\Post;

require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Exception/InvalidArgument.php';
require_once 'Phorrst/Post/AbstractPost.php';


require_once 'Phorrst/Post/Code.php';
require_once 'Phorrst/Post/Link.php';
require_once 'Phorrst/Post/Snap.php';
require_once 'Phorrst/Post/Question.php';

use \Phorrst\Post\AbstractPost         as AbstractPost,
    \Phorrst\Exception                 as Exception,
    \Phorrst\Exception\InvalidArgument as ArgumentException;


/**
 * Factory
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Factory
{
    /**
     *
     * @param object|array $data
     * @return \Phorrst\AbstractPost
     */
    public function createPost($data)
    {
        ArgumentException::check(
            array(ArgumentException::HASH, ArgumentException::OBJECT), $data);

        if(is_object($data)) {
            $data = (array)$data;
        }

        $class = "\\Phorrst\\Post\\";

        switch ($data['post_type']) {
            case AbstractPost::CODE:
            case AbstractPost::LINK:
            case AbstractPost::SNAP:
            case AbstractPost::QUESTION:
                $class .= \ucfirst($data['post_type']);
                break;

            default:
                throw new Exception('Can\'t detect post type');            
        }

        return \call_user_func(array($class, 'create'), $data);
    }
}