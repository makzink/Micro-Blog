<?php

    /*do the error logging*/
    function error_logging($message)
    {
        /*fetch the request params*/
        // $log = $app->getLog();
        /*do the error logging*/
        // $log->error($message);
    }

    /*return error responses*/
    function error_response($request,$response,$status,$message)
    {
        error_logging($message);
        return $response->withStatus($status)->write($message);
    }

    /*return response*/
    function return_response($request,$response,$result)
    {
        $token_params = $request->getAttribute("token_params");
        if(!isset($token_params["audience"]))
            $token_params["audience"] = "*";
        $token=$request->getAttribute("_bn_ut");
        if($token!='')
            $result["_bn_ut"] = $request->getAttribute("_bn_ut");
        return $response->withStatus(200)
                        ->write(json_encode($result))
                        ->withHeader('Access-Control-Allow-Origin',$token_params["audience"])
                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    //IS SAFE
    function isSafe($data,$mode)
    {
        $data=trim($data);
        $regex='/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix';
        if(!preg_match($regex,$data))
        {
            if($mode==1)
            return 1;
            else if($mode==2 && $data!="")
            return 1;
            else
            return 0;
        }
        else
        return 0;
    }// is Safe

    //GET IP
    function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }// getIP

    /*ping to check if everything works fine*/
    function ping_server($request,$response)
    {
        $result = array("status"=>1,"msg"=>"Everthing is fine","css"=>"alert alert-success");
        echo json_encode($result);
    }

    /*encrypt code*/
    function encrypt_code($code)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'kazmikcorp';
        $secret_iv = 'kazmikcorp123';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        return base64_encode(openssl_encrypt($code, $encrypt_method, $key, 0, $iv));
    }

    /*decrypt userid*/
    function decrypt_code($code)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'kazmikcorp';
        $secret_iv = 'kazmikcorp123';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        return openssl_decrypt(base64_decode($code), $encrypt_method, $key, 0, $iv);
    }

?>
