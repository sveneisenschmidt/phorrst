<?php

require_once 'bootstrap.php';
require_once 'functions.php';
require_once 'Phorrst/Service.php';




use Phorrst\Service as Service;

$service = new Service();
$service->setCache($cache);

$user    = $service->getUserById(3832);
$posts   = $user->getPosts();

$posts   = $user->getPosts()->filter(function($post) {
    return ($post->getPostType() == 'code');
});
?>

<ul>
<?php foreach($posts as $post): ?>
    <li><?php print $post->getTitle(); ?></li>
<?php endforeach; ?>
</ul>