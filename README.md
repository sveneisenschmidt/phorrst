# Phorrst

PHP Access/Service Layer for *http://forrst.com*

### Requierements

   - PHP 5.3.*
   - cURL

### Installation

Drop somewhere the Phorrst source folder in your include path (*src/library/Phorrst*) and include the following code snippet:

         $service = new \Phorrst\Service();

If Everything works as expected you should get a running Phorrst instance.



### Usage

**Get user**

*// php*
    $service->getUserById(3832);   // OR
    $service->getUserByName('hahaitsfate');

Returns null or an user object. (*instance of \Phorrst\User*)

**Get posts**

*//php*
    $service->getPostsByUserId(3832);   // OR
    $service->getPostsByUserName('hahaitsfate');

Returns null or an collection of data (*instance of \Phorrst\Post\Collection*), until you're not iterating over it (*with foreach or Iterator*) the data will contain only objects.


### Detailed Usage

Get a user and retrieve his posts and filter them by type:

*// html*
    $user    = $service->getUserById(3832);
    $posts   = $user->getPosts()->filter(function($post) {
        return ($post->getPostType() == 'code');
    });

You can pass a closure or function name to filter the result set. Returning *true* keeps the item, *false* will remove it. Later you can iterate over the collection (*instance of \Phorrst\Post\Collection*) like over any other array.

*// html*
    <ul>
    <?php foreach($posts as $post): ?>
        <li><?php print $post->getTitle(); ?></li>
    <?php endforeach; ?>
    </ul>
____
**Methods on a service instance** (*instance of \Phorrst\Service*)

- getUserById($id) // returns *\Phorrst\User*

- getUserByName($name) // returns *\Phorrst\User*

- getPostsByUserId($id) // returns *\Phorrst\Post\Collection*

- getPostsByUserName($name) // returns \Phorrst\Post\Collection

- setCache(*\Phorrst\Cache\AbstractCache $cache*)

- getCache // returns null|*\Phorrst\Cache\AbstractCache*


Use the cache (*adapter for memcache is provided*)

*//php*
    require 'Phorrst/Cache/Memcache.php';

    $memcache = new \Memcache();
    $memcache->connect('localhost');

    $service->setCache(
        new \Phorrst\Cache\Memcache($memcache));

**!! It's highly encoureged to use the cache, this will significantly increase the performance !!**
____

**Methods on user objects** (*instance of \Phorrst\User*)

- getUsername() // returns string

- getId() // returns integer

- getPosts() // returns *\Phorrst\Post\Collection*

- create(*$data = array()*, *$service = null*)


Create a mock user to not request extra data from forrst

*//php*
    $mockUser = \Phorrst\User::create(array(
        'id' => 3832, 'username' => 'hahaitsfate
    ), $service); // pass-in a service object if you have one, when not, one is created for you

    $posts = mockUser->getPosts();

____

**Methods on post objects** (*instances of abstract class \Phorrst\Post\AbstractPost*)

Possible classes to return:

- *\Phorrst\Post\Link*
- *\Phorrst\Post\Code*
- *\Phorrst\Post\Question*
- *\Phorrst\Post\Snap*

Shared methods:

- getId() // returns integer

- getUserId() // returns integer

- getUser() // returns null | *\Phorrst\User*

- getSlug() // returns string

- getTitle() // returns string

- getPageTitle() // return string

- getLikeCount() // returns integer

- getViewCount() // returns integer

- getPostUrl($withHost = false) // returns string

- getPostType() // returns string (*link* | *code* | *snap* | *question*)

- getTags($flag = self::TAGS\_AS\_ARRAY) // $flag can be \_\_CLASS\_\_::TAGS\_AS\_ARRY, \_\_CLASS\_\_::TAGS\_AS\_STRING, \_\_CLASS\_\_::TAGS\_AS\_OBJECT // returns *array* | *string* | *object*

- isPublic() // returns boolean

- isReply() // returns boolean

- getReplyPostId() // returns null | integer

- getDescription($asMarkdown = false) // returns string

- getContent($asMarkdown = false) // returns string

- getReplyKey() // returns string

- getReplyUri($withHost = false)  // returns string

- getPhrase() // returns string

- getTinyId() // returns integer

- getShortUrlRedirectCount() // returns integer

- getCreatedAt($asDateTime = false) // returns string | *\DateTime*

- getUpdatedAt($asDateTime = false) // returns string | *\DateTime*

Link (*\Phorrst\Post\Link*) methods

 - getUrl() // returns string

Code (*\Phorrst\Post\Code*) methods

 - getSnippedCode() // returns string

Snap (*\Phorrst\Post\Snap*) methods

 - getUrl() // returns string

 - getFileSize() // returns integer

 - getFileName() // returns string

 - getFileUpdatedAt($asDateTime = false) // returns string | *\DateTime*

 - getFileContentType() // returns string

 - getImageLink($size = self::SIZE_ORIGINAL) // Possible Options:
  - $size = \Phorrst\Post\Snap::SIZE_MEGA
  - $size = \Phorrst\Post\Snap::SIZE_LARGE
  - $size = \Phorrst\Post\Snap::SIZE_MEDIUM
  - $size = \Phorrst\Post\Snap::SIZE_SMALL
  - $size = \Phorrst\Post\Snap::SIZE_THUMB
  - $size = \Phorrst\Post\Snap::SIZE_ORIGINAL

Question (*\Phorrst\Post\Question*) methods

 - \---


