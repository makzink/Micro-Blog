<?php

    $app->group("/blog",function() use ($app){
        require_once("blog/backend.php");
        $app->post('/fetch/','fetch_blog')->add('verify_token');
        $app->post('/read/','read_blog')->add('verify_token');
        $app->post('/like/','like_blog')->add('verify_token');
    });

?>
