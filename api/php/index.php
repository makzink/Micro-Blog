<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header('Access-Control-Allow-Origin: *');
    /*library inclusion*/
    require_once('vendor/autoload.php');

    use Lcobucci\JWT\Builder;
    use Lcobucci\JWT\ValidationData;
    use Lcobucci\JWT\Parser;
    use Lcobucci\JWT\Signer\Hmac\Sha256;

    /*common files*/
    require_once('config.php');
    require_once('common.php');

    /*additional slim configs - not for production*/
    $config = array('settings'=>array('displayErrorDetails'=>1));

    /*init the slim web app*/
    $app = new \Slim\App($config);

    /*core routes*/
    require 'routes/auth.php';
    require 'routes/blog.php';

    /*basic routes*/
    // $app->get('/ping/', 'ping_server');

    /*execute app*/
    $app->run();

?>
