<?php

    use Lcobucci\JWT\Builder;
    use Lcobucci\JWT\ValidationData;
    use Lcobucci\JWT\Parser;
    use Lcobucci\JWT\Signer\Hmac\Sha256;

    /*generate user token*/
    function generate_token($request){
        global $g_audience_map,$g_issuer,$g_signer,$g_token_expiry,$g_url;
        $result = array("status"=>0,"msg"=>"Unable to generate User token","css"=>"alert alert-danger");
        if(isset($request["uid"]) && isset($request["email"]) && isset($request["device_type"]))
        {
            $request_params = hush_decrypt($request);
            $uid = $request_params["uid"];
            $usr = (isset($request_params["usr"]))?$request_params["usr"]:"Kazmik Corp. User";
            $usr_type = (isset($request_params["usr_type"]))?$request_params["usr_type"]:"1";
            $email = $request_params["email"];
            $time = time();
            $device_type = $request_params["device_type"];
            // $audience = $g_audience_map[$device_type];
            $audience = "*";
            $signer = new Sha256();
            $token_key = "HUSH_AUTH_".$uid."_".$device_type."_".$time;
            $token = (new Builder())->setIssuer($g_issuer)
                                ->setAudience($audience)
                                ->setId($token_key, TRUE)
                                ->setIssuedAt($time)
                                ->setNotBefore($time)
                                ->setExpiration($time+$g_token_expiry)
                                ->set('uid',$uid)
                                ->set('usr',$usr)
                                ->set('usr_type',$usr_type)
                                ->set('email',$email)
                                ->sign($signer,$g_signer)
                                ->getToken();
            $token = (string)$token;
            /*store the token in redis*/
            $user_list = json_decode(file_get_contents('auth/user.json'), true);
            $user_list[$token_key] = json_encode(array("token"=>$token));
            file_put_contents('auth/user.json', json_encode($user_list));

            $res = $token_key;
            /*return result*/
            $result = array("status"=>1,"token"=>$token,"expiry"=>$time+$g_token_expiry,"msg"=>"User token created sucessfully","css"=>"alert alert-success",'res'=>$res);
        }

        return $result;
    }

    /*decrypt token into request params for middlewares*/
    function decrypt_token($request)
    {
        $request_params = array();
        $headers = $request->getHeaders();
        $body = $request->getBody();

        /*set request body parameters if present*/
        if(isset($body)){
            $body_params = json_decode($body,true);
            if(count($body_params)>0)
                foreach ($body_params as $key => $value) {
                    $request_params[$key] = $value;
                }
        }

        if(isset($headers["HTTP_AUTHORIZATION"]) && count($headers["HTTP_AUTHORIZATION"])>0)
        {
            try {
                $token = $headers["HTTP_AUTHORIZATION"][0];
                $token = (new Parser())->parse((string)$token);
                $request_params["uid"] = $token->getClaim("uid");
                $request_params["usr"] = $token->getClaim("usr");
                $request_params["usr_type"] = $token->getClaim("usr_type");
                $request_params["email"] = $token->getClaim("email");
                $request_params["audience"] = $token->getClaim("aud");
                $request_params['valid_token'] = 1;
            } catch (Exception $e) {
                $request_params['valid_token'] = 0;
            }
        }
        return $request_params;
    }

    /*check if the device limit has reached*/
    function check_device_limit($request,$response)
    {
        global $g_device_limit,$g_url;
        $result = array("status"=>0,"msg"=>"User not found.","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();
        if(isset($request_params["uid"]))
        {
            $request_params = hush_decrypt($request_params);
            $uid = trim($request_params["uid"]);
            $result = array("status"=>1,"msg"=>"Device limit not reached.","css"=>"alert alert-success");
            /*get count of tokens*/
            // $rdb = redisDB(0);
            // $tokens = $rdb->keys("AUTH_".$uid."_*");
            /*if limit reached return error*/
            if(count($tokens)>$g_device_limit)
            {
                $result = array("status"=>0,"msg"=>"Device limit reached. Please check your mail to verify your account.","css"=>"alert alert-danger");
            }
        }
        return return_response($request,$response,$result);
    }

    /*verify user token*/
    function verify_token($request,$response,$next)
    {
        global $g_signer;
        $headers = $request->getHeaders();
        /*if authorization token present*/
        if(isset($headers["HTTP_AUTHORIZATION"]) && count($headers["HTTP_AUTHORIZATION"])>0 && strlen($headers["HTTP_AUTHORIZATION"][0]) > 10 )
        {
            $token = $headers["HTTP_AUTHORIZATION"][0];
            $user_list = json_decode(file_get_contents('auth/user.json'), true);
            try{
                $token = (new Parser())->parse((string)$token);
                $data = new ValidationData();
                $signer = new Sha256();
            }catch (Exception $e) {
                return error_response($request,$response,302,'INVALID_TOKEN');
            }
            /*if token is valid*/
            if($token->validate($data) && $token->verify($signer,$g_signer))
            {
                $token_key = $token->getHeader("jti");
                $server_token = $user_list[$token_key];
                $server_token = json_decode($server_token,true);
                /*if token exists in rdb*/
                if($server_token["token"] != $token){
                    return error_response($request,$response,302,'INVALID_TOKEN');
                }
                /*add token details to the attributes*/
                else
                {
                    /*verify referrer*/
                    $audience = $token->getClaim("aud");
                    $audience_host = parse_url($audience, PHP_URL_HOST);
                    $referrer = $request->getServerParam("HTTP_REFERER");
                    $referrer_host = parse_url($referrer, PHP_URL_HOST);
                    if($audience==$referrer || $audience=="*")
                    {
                        $token_params = array();
                        try {
                            $token_params["uid"] = $token->getClaim("uid");
                            $token_params["usr"] = $token->getClaim("usr");
                            $token_params["usr_type"] = $token->getClaim("usr_type");
                            $token_params["email"] = $token->getClaim("email");
                            $token_params["audience"] = $token->getClaim("aud");
                            $token_params["token_key"] = $token_key;
                        } catch (Exception $e) {
                            error_logging($e->getMessage());
                        }
                        $request = $request->withAttribute('token_params',$token_params);
                    }
                    else
                        return error_response($request,$response,403,'ACCESS_DENIED');
                }
            }
            // if verified, but timeout
            else if($token->verify($signer,$g_signer))
            {
                $token_key = $token->getHeader("jti");
                $server_token = $user_list[$token_key];
                $server_token = json_decode($server_token,true);
                /*if token exists in rdb*/
                if($server_token["token"] != $token){
                    return error_response($request,$response,302,'INVALID_TOKEN');
                }
                /*add token details to the attributes*/
                else
                {
                    $user_data=array();
                    try
                    {
                        $user_data["uid"] = $token->getClaim("uid");
                        $user_data["usr"] = $token->getClaim("usr");
                        $user_data["usr_type"] = $token->getClaim("usr_type");
                        $user_data["email"] = $token->getClaim("email");
                        $user_data["audience"] = $token->getClaim("aud");
                        $user_data["token_key"] = $token_key;

                        $result = generate_token(hush_encrypt($user_data));
                        /*delete the old token in redis*/
                        unset($user_list[$token_key]);
                        file_put_contents('auth/user.json', json_encode($user_list));
                        $request = $request->withAttribute('auth_token', $result);
                    }
                    catch (Exception $e) {
                        error_logging($e->getMessage());
                    }
                    $request = $request->withAttribute('token_params',$user_data);
                }
            }
            /*token invalid*/
            else
                return error_response($request,$response,302,'INVALID_TOKEN');
        }
        /*continue*/
        $response = $next($request, $response);
        return $response;
    }

    /*rate limiter*/
    function rate_limit($request,$response,$next)
    {
        $rdb = redisDB(0);
        /*get request params*/
        $request_params = decrypt_token($request);
        if(isset($request_params['valid_token']) && $request_params['valid_token']==0)
        {
            return error_response($request,$response,302,'INVALID_TOKEN');
        }
        if(isset($request_params["uid"]))
        {
            $uid = $request_params["uid"];
            $time = time();
            $score = $rdb->zscore("hush-ratelimit",$uid);
            $expiry = $rdb->zscore("hush-rateexpiry",$uid);
            if($time<$expiry)/*check if the key exists*/
            {
                if($score==0 || $score=="")/*if score negetive show error*/
                    return error_response($request,$response,429,"TOO_MANY_REQUESTS");
                else/*update the count*/
                {
                    /*decrement score and set score*/
                    $score--;
                    $rdb->zAdd("hush-ratelimit",$score,$uid);
                }
            }
            else/*reset the counter*/
                reset_ratelimit($rdb,$uid);
        }
        /*continue*/
        $response = $next($request, $response);
        return $response;
    }

    /*reset the ratelimit*/
    function reset_ratelimit($rdb,$uid)
    {
        global $g_rate_limit;
        $time = time();
        $rdb->zAdd("hush-ratelimit",$g_rate_limit,$uid);
        $rdb->zAdd("hush-rateexpiry",$time+60,$uid);
    }

    /*encrypt request params for token less authentication*/
    function hush_encrypt($dict)
    {
        foreach ($dict as $key => $value) {
            if (is_string($value))
                $dict[$key] = encrypt_code($value);
        }
        return $dict;
    }

    /*decrypt the token less authentication data*/
    function hush_decrypt($dict)
    {
        foreach ($dict as $key => $value) {
            if (is_string($value))
                $dict[$key] = decrypt_code($value);
        }
        return $dict;
    }

    /*generate user id*/
    function generate_uid($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $uid = '';
        for ($i = 0; $i < $length; $i++) {
            $uid .= $characters[rand(0, $charactersLength - 1)];
        }

        $mdb = mongoDB(0);
        $user_coll = $mdb->users;
        $user_record = $user_coll->findOne(array('uid'=>$uid));
        if($user_record != null)
            $uid = generate_uid();
        return $uid;
    }

    /*new user signup*/
    function user_signup($request,$response)
    {
        global $g_url;
        $result = array("status"=>0,"msg"=>"Could not register user. Please try again","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();

        $mdb = mongoDB(0);
        $user_coll = $mdb->users;

        if(isset($request_params['usr']) && isset($request_params['email']) && isset($request_params['pswd']))
        {
            $usr = $request_params['usr'];
            $email = $request_params['email'];
            $pswd = $request_params['pswd'];

            $result=array('status'=>0,'msg'=>"Email id already exists. Please login with email.",'css'=>"alert alert-danger");

            $user_record = $user_coll->findOne(array('email'=>$email));
            // var_dump($user_record);
            if($user_record == null)
            {
                $pswd_encrypt = crypt($pswd, substr($pswd, 0, 12));

                $uid = generate_uid();
                $time = time();
                $ip = getIP();

                $user_record = array(
                    'uid' => $uid,
                    'usr' => $usr,
                    'email' => $email,
                    'pswd' => $pswd_encrypt,
                    'usr_type' => 1,
                    'create_time' => $time
                );

                if($user_coll->insertOne($user_record))
                {
                    $user_record['device_type'] = 1;
                    $token = generate_token(hush_encrypt($user_record));
                    $user_record['token_data'] = $token;
                    $result = array('status'=>1,'msg'=>'Logged in. Please wait while we redirect you.','user'=>$user_record,'r_url'=>$g_url,'token_data'=>$token);
                }else{
                    $result = array("status"=>0,"msg"=>"Could not register user. Please try again","css"=>"alert alert-danger");
                }
            }
        }

        return_response($request,$response,$result);
    }

    /*user login*/
    function user_login($request,$response)
    {
        global $g_url;
        $result = array("status"=>0,"msg"=>"Unable to login.Please try again","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();

        $mdb = mongoDB(0);
        $user_coll = $mdb->users;

        if(isset($request_params['email']) && isset($request_params['pswd']))
        {
            $email = $request_params['email'];
            $pswd = $request_params['pswd'];

            $result=array('status'=>0,'msg'=>"Sorry we could not log you in. Your email address or password is incorrect.",'css'=>"alert alert-danger");
            $user_record = $user_coll->findOne(array('email'=>$email));
            // var_dump($user_record);
            if($user_record != null)
            {
                //check pswd match
                if (crypt($pswd, substr($user_record['pswd'], 0, 12)) == $user_record['pswd'] )
                {
                    unset($user_record['_id']);
                    unset($user_record['pswd']);

                    $user_record['device_type'] = 1;
                    $user_apps = isset($user_record['apps'])?$user_record['apps']:array();
                    $token = generate_token(hush_encrypt($user_record),$user_apps);
                    $user_record['token_data'] = $token;
                    $result = array('status'=>1,'msg'=>'Logged in. Please wait while we redirect you.','user'=>$user_record,'r_url'=>$g_url,'token_data'=>$token);
                }

            }else{
                $result=array('status'=>0,'msg'=>"It seems you dont have an account in Kazmik Corp. Please register an account.",'css'=>"alert alert-danger");
            }

        }

        return_response($request,$response,$result);
    }

?>
