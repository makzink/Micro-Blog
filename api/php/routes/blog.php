<?php

    $app->group("/blog",function() use ($app){
        require_once("blog/backend.php");
        // $app->post('/fethc/','fetch_blog');
    });

?>
