<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

use Config;
use DB;

class UserController extends Controller
{

    public function user_login(Request $request)
    {
        $result = array('status'=>0,'msg'=>'Could not login','css'=>'alert alert-danger');

        if($request->has('email') && $request->has('pswd'))
        {
            $email = $request->input('email');
            $pswd = $request->input('pswd');
            $r_url = $request->input('r_url');

            $user_res = iterator_to_array(DB::collection('users')->where('email',$email)->get());
            $result=array('status'=>0,'msg'=>"It seems you dont have an account in Kazmik Corp. Please register an account.",'css'=>"alert alert-danger");
            if(count($user_res) > 0)
            {
                $result=array('status'=>0,'msg'=>"Sorry we could not log you in. Your email address or password is incorrect.",'css'=>"alert alert-danger");
                $user_record = $user_res[0];

                if ($pswd == $user_record['pswd'] )
                {
                    unset($user_record['_id']);
                    unset($user_record['pswd']);

                    $customClaims = ['uid' => $user_record['uid'], 'usr_type' => $user_record['usr_type'], 'email' => $user_record['email']];
                    $payload = JWTFactory::make($customClaims);
                    $token_res = JWTAuth::encode($payload);
                    $token = "";
                    foreach ((array)$token_res as $key => $value) {
                        $token = $value;
                    }

                    $user_record['token_data'] = array('token'=>$token);
                    $result = array('status'=>1,'msg'=>'Logged in. Please wait while we redirect you.',"css"=>"alert alert-success",'user'=>$user_record,'r_url'=>$r_url);

                }
            }
        }

        return json_encode($result);
    }

    function user_signup(Request $request)
    {
        $result = array('status'=>0,'msg'=>'Could not singup user','css'=>'alert alert-danger');

        if($request->has('email') && $request->has('pswd'))
        {
            $email = $request->input('email');
            $pswd = $request->input('pswd');
            $usr = $request->input('usr');
            $r_url = $request->input('r_url');

            $user_res = iterator_to_array(DB::collection('users')->where('email',$email)->get());

            $result=array('status'=>0,'msg'=>"Email id already exists. Please login with email.",'css'=>"alert alert-danger");
            if(count($user_res) == 0)
            {

                $uid = generate_uid();
                $time = time();

                $user_record = array(
                    'uid' => $uid,
                    'usr' => $usr,
                    'email' => $email,
                    'pswd' => $pswd,
                    'usr_type' => 1,
                    'create_time' => $time
                );

                DB::collection('users')->insert($user_record);

                $customClaims = ['uid' => $uid, 'usr_type' => '1', 'email' => $email];
                $payload = JWTFactory::make($customClaims);
                $token_res = JWTAuth::encode($payload);
                $token = "";
                foreach ((array)$token_res as $key => $value) {
                    $token = $value;
                }

                $user_record['token_data'] = array('token'=>$token);
                $result = array('status'=>1,'msg'=>'Logged in. Please wait while we redirect you.',"css"=>"alert alert-success",'user'=>$user_record,'r_url'=>$r_url);


            }
        }

        return json_encode($result);
    }

}
