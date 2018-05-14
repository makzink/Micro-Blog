<?php

    if(function_exists('date_default_timezone_set')) date_default_timezone_set("Asia/Kolkata");

    $g_url = "https://www.dailyprepapp.com/modules/mb/jquery/";
    $g_short_url = "https://ams.dailyprepapp.com";
    $g_api_url = "https://api100.dailyprepapp.com/mb/php/";
    $g_cookie_prefix = "_hush_";

    $g_audience = $g_url;

    $g_signer = "CTHIBHVASSBFWW";
    /* token expirey 1 day*/
    $g_token_expiry = 86400;
    /* redis token expirey 7 days */
    $g_redis_expirey = 7*86400;
    $g_rate_limit = 100;

    //Error messages
    $response_msg = array(
      'default_error'=>'Something went wrong. Please try again.',
      'default_success'=>'Success.',
      'generate_token_fail'=>'Unable to generate User token',
      'generate_token_success'=>'User token created sucessfully',
      'user_not_found'=>'User not found.',
      'device_limit_success'=>'Device limit not reached.',
      'device_limit_fail'=>'Device limit reached. Please check your mail to verify your account.',
      'invalid_token'=>'INVALID TOKEN',
      'access_denied'=>'ACCESS DENIED',
      'many_requests'=>'TOO MANY REQUESTS',
      'logout_success'=>'User logged out sucessfully',
      'flush_user_success'=>'All user records flushed sucessfully'
    );

    function mongoDB($db_no=0) /*MongoDB connection*/
	{
		// $db_no=0;

        $db = "";
        $database = array("hush_db");
        $client = new MongoDB\Client('mongodb://hush_user:hush_pswd@cluster0-shard-00-00-kbcgw.mongodb.net:27017,cluster0-shard-00-01-kbcgw.mongodb.net:27017,cluster0-shard-00-02-kbcgw.mongodb.net:27017/admin?ssl=true&replicaSet=Cluster0-shard-0&authSource=admin&retryWrites=true');
        $db = $client->{$database[$db_no]};

		return $db;
	}

    function redisDB($db_no=0) //redis db
	{
		$db_no=0;

		$redis = new Redis();
		$port = 6379;
		$database = array('engage-redis.zaries.0001.apse1.cache.amazonaws.com');
   	    $redis->connect($database[$db_no], $port);
   	    return $redis;
	}

?>
