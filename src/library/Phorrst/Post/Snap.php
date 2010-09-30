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
 */

namespace Phorrst\Post;

require_once 'Phorrst/Post/AbstractPost.php';
require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Exception/InvalidArgument.php';

use \Phorrst\Post\AbstractPost          as AbstractPost,
    \Phorrst\Exception                  as Exception,
    \Phorrst\Exception\InvalidArgument  as ArgumentException;

/**
 * Snap
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Snap extends AbstractPost
{
    /**
     *
     * @var const
     */
    const SIZE_MEGA     = 'mega';

    /**
     *
     * @var const
     */
    const SIZE_LARGE    = 'large';

    /**
     *
     * @var const
     */
    const SIZE_MEDIUM   = 'medium';

    /**
     *
     * @var const
     */
    const SIZE_SMALL    = 'small';

    /**
     *
     * @var const
     */
    const SIZE_THUMB    = 'thumb';

    /**
     *
     * @var const
     */
    const SIZE_ORIGINAL = 'original';

    /**
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData('url');
    }

    /**
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getData('snap_file_name');
    }

    /**
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->getData('snap_file_size');
    }

    /**
     *
     * @param boolean $asDateTime
     * @return string|\DateTime
     */
    public function getFileUpdatedAt($asDateTime = false)
    {
        return $this->_getDate($this->getData('snap_updated_at'), $asDateTime);
    }

    /**
     *
     * @return integer
     */
    public function getFileContentType()
    {
        return $this->getData('snap_content_type');
    }

    /**
     *
     * @return string
     */
    public function getImageLink($size = self::SIZE_ORIGINAL)
    {
        ArgumentException::check(ArgumentException::STRING, $size);

        if(!\in_array($size, array(
            self::SIZE_MEGA, self::SIZE_LARGE, self::SIZE_MEDIUM,
            self::SIZE_SMALL, self::SIZE_THUMB, self::SIZE_ORIGINAL
        ))) {
           throw new ArgumentException('$size is no valid image size', 500);
        }

        $images = $this->getData('images', true);
        if(!\array_key_exists($size, $images)) {
            throw new Exceptino("Size: {$size} is not provided.", 500);
        }

        return $images[$size];
    }

}