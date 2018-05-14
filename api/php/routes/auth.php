<?php

    $app->group("/auth",function() use ($app){
        require_once("auth/backend.php");
        $app->post('/login/','user_login');
        $app->post('/signup/','user_signup');
    });

?>
