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
 * @uses \Phorrst\Service
 * @uses \Phorrst\Data\Object
 * @uses \Phorrst\Exception
 * @uses \Phorrst\Exception\InvalidArgument
 */

namespace Phorrst\Post;

require_once 'Phorrst/Service.php';
require_once 'Phorrst/Data/Object.php';
require_once 'Phorrst/Exception.php';
require_once 'Phorrst/Exception/InvalidArgument.php';

use \Phorrst\Service                    as Service,
    \Phorrst\Data\Object                as Object,
    \Phorrst\Exception                  as Exception,
    \Phorrst\Exception\InvalidArgument  as ArgumentException;

/**
 * AbstractPost
 *
 * @category Service
 * @package Phorrst
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class AbstractPost extends Object
{
    /**
     *
     * @var const
     */
    const CODE = 'code';

    /**
     *
     * @var const
     */

    const LINK = 'link';

    /**
     *
     * @var const
     */

    const SNAP = 'snap';

    /**
     *
     * @var const
     */
    const QUESTION = 'question';

    /**
     *
     * @var const
     */
    const TAGS_AS_STRING = 'string';

    /**
     *
     * @var const
     */
    const TAGS_AS_ARRAY = 'array';

    /**
     *
     * @var const
     */
    const TAGS_AS_OBJECT= 'object';

    /**
     * Just for the IDE class auto completion
     *
     * @param object|array $data
     * @param Service $service
     * @return \Phorrst\AbstractPost
     */
    public static function create($data = array(), Service $service = null)
    {
        return parent::create($data, $service);
    }

    /**
     *
     * @return \Phorrst\User
     */
    public function getUser()
    {
        return $this->getService()
                    ->getUserById($this->getUserId());
    }

    /**
     *
     * @return integer|null
     */
    public function getUserId()
    {
        $id = $this->getData('user_id');
        return is_numeric($id) ? (int)$id : null;
    }

    /**
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getData('slug');
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->getData('page_title');
    }

    /**
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return (int)$this->getData('like_count');
    }

    /**
     *
     * @return integer
     */
    public function getViewCount()
    {
        return (int)$this->getData('views');
    }

    /**
     *
     * @param $withHost boolean
     * @return string
     */
    public function getPostUrl($withHost = false)
    {
        ArgumentException::check(ArgumentException::BOOLEAN, $withHost);

        $url = $this->getData('post_url');        
        return $withHost ? Service::URI . $url : $url;
    }

    /**
     *
     * @return string
     */
    public function getPostType()
    {
        if(null !== $this->getData('post_type')) {
            return $this->getData('post_type');
        }

        $type = \str_replace(__NAMESPACE__, '', \get_class($this));
        $type = \trim($type, '\\');
        $type = \strtolower($type);

        return $type;
    }

    /**
     *
     * @param string $flag
     * @return array|stdClass
     */
    public function getTags($flag = self::TAGS_AS_ARRAY)
    {
        ArgumentException::check(ArgumentException::STRING, $flag);

        if(!\in_array($flag, array(
            self::TAGS_AS_ARRAY, self::TAGS_AS_STRING, self::TAGS_AS_OBJECT
        ))) {
           throw new ArgumentException('$flag is no valid Tag flag', 500);
        }

        switch($flag) {
            case self::TAGS_AS_STRING:
                return $this->getData('tag_string'); break;
            case self::TAGS_AS_ARRAY:
                return $this->getData('tag_obj'); break;
            default:
                return $this->getData('tags');
        }

        throw new Exception('Method should never execute this line.', 500);
    }

    /**
     *
     * @return boolean
     */
    public function isPublic()
    {
        return (bool)$this->getData('is_public');
    }

    /**
     *
     * @return boolean
     */
    public function isReply()
    {
        return $this->getReplyPostId();
    }

    /**
     *
     * @return null|integer
     */
    public function getReplyPostId()
    {
        return $this->getData('in_reply_to_post_id');
    }

    /**
     *
     * @param boolean $asMarkdown
     * @return null|string
     */
    public function getDescription($asMarkdown = false)
    {
        ArgumentException::check(ArgumentException::BOOLEAN, $asMarkdown);

        if(false === $asMarkdown) {
            return $this->getData('description');
        }
        
        return $this->getData('markdown_description');
    }

    /**
     *
     * @param boolean $asMarkdown
     * @return null|string
     */
    public function getContent($asMarkdown = false)
    {
        if(!\in_array($this->getPostType(), array(
            self::CODE, self::QUESTION
        ))) {
            return null;
        }
        
        ArgumentException::check(ArgumentException::BOOLEAN, $asMarkdown);

        if(false === $asMarkdown) {
            return $this->getData('content');
        }

        return $this->getData('markdown_content');
    }

    /**
     *
     * @return string
     */
    public function getReplyKey()
    {
        return $this->getData('reply_key');
    }

    /**
     *
     * @param boolean $withHost
     * @return string
     */
    public function getReplyUri($withHost = false)
    {
        ArgumentException::check(ArgumentException::BOOLEAN, $withHost);

        $url = $this->getData('reply_url');
        return $withHost ? Service::URI . $url : $url;
    }

    /**
     *
     * @return string
     */
    public function getPhrase()
    {
        return $this->getData('phrase');
    }

    /**
     *
     * @return string
     */
    public function getTinyId()
    {
        return $this->getData('tiny_id');
    }

    /**
     *
     * @return integer
     */
    public function getShortUrlRedirectCount()
    {
        return $this->getData('short_url_redirects');
    }

    /**
     *
     * @param boolean $asDateTime
     * @return string|\DateTime
     */
    public function getCreatedAt($asDateTime = false)
    {
        return $this->_getDate($this->getData('created_at'), $asDateTime);
    }

    /**
     *
     * @param boolean $asDateTime
     * @return string|\DateTime
     */
    public function getUpdatedAt($asDateTime = false)
    {
        return $this->_getDate($this->getData('updated_at'), $asDateTime);
    }
    
    /**
     *
     * @param string $date
     * @param boolean $asDateTime
     * @return string|\DateTime
     */
    protected function _getDate($date, $asDateTime = false)
    {
        ArgumentException::check(ArgumentException::STRING, $date);
        ArgumentException::check(ArgumentException::BOOLEAN, $asDateTime);

        if(false === $asDateTime) {
            return $date;
        }

        return $this->_parseDate($date);
    }

    /**
     *
     * @param string $date
     * @return \DateTime
     */
    protected function _parseDate($date)
    {
        return new \DateTime($date, new \DateTimeZone(Service::TIMEZONE));
    }
}