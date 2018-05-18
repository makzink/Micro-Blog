<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use DB;

class BlogController extends Controller
{

    public function fetch_blog(Request $request)
    {
        $result = array('status'=>0,'msg'=>'Could not fetch blog','css'=>'alert alert-danger');

        $token = JWTAuth::getToken();
        $payload = array();
        if(!is_bool($token)){
            $payload = JWTAuth::decode($token);
        }

        if($request->has('b_sort'))
        {
            $b_sort = $request->b_sort;

            $uid = 0;
            if(isset($payload['uid']))
                $uid = $payload['uid'];

            $articles_res = iterator_to_array(DB::collection('blogs')->get());
            $user_list = array();
            $category_list = array();
            foreach ($articles_res as $key => $value) {
                if(!in_array($value['auth_uid'], $user_list))
                    $user_list[] = $value['auth_uid'];
                if(!in_array($value['category_id'], $category_list))
                    $category_list[] = $value['category_id'];
            }

            $user_res = iterator_to_array(DB::collection('users')->get());//   $user_coll->find(array('uid'=>array('$in'=>$user_list))));
            $user_data = array();
            foreach ($user_res as $key => $value) {
                if(in_array($value['uid'], $user_list))
                    $user_data[$value['uid']] = $value;
            }

            $cat_res = iterator_to_array(DB::collection('categories')->get());
            $cat_data = array();
            foreach ($cat_res as $key => $value) {
                if(in_array($value['category_id'], $category_list))
                    $cat_data[$value['category_id']] = $value;
            }

            $blog_content = array();
            foreach ($articles_res as $key => $value) {
                $value['auth_usr'] = $user_data[$value['auth_uid']]['usr'];
                $value['auth_img'] = $user_data[$value['auth_uid']]['usr_img'];

                $value['category'] = $cat_data[$value['category_id']]['category_title'];

                $value['like_status'] = 0;
                $likes = array();
                foreach ($value['likes'] as $k => $v) {
                    $likes[] = $v;
                }

                if(is_string($uid) && in_array($uid, $likes))
                {
                    $value['like_status'] = 1;
                }

                $blog_content[] = $value;
            }

            switch ($b_sort) {
                case '1':
                    $likes = array();
                    foreach ($blog_content as $key => $row)
                    {
                        $likes[$key] = count($row['likes']);
                    }
                    array_multisort($likes, SORT_DESC, $blog_content);
                    break;
                case '2':
                    $c_t = array();
                    foreach ($blog_content as $key => $row)
                    {
                        $c_t[$key] = $row['create_time'];
                    }
                    array_multisort($c_t, SORT_DESC, $blog_content);
                    break;
            }

            $result = array('status'=>1,'msg'=>'Fetched blog content','content'=>$blog_content);
        }

        return json_encode($result);
    }

    public function read_blog(Request $request)
    {
        $result = array('status'=>0,'msg'=>'Could not read blog','css'=>'alert alert-danger');

        if($request->has('article_id')){
            $article_id = $request->article_id;

            DB::collection('blogs')->where('article_id', $article_id)->increment('views', 1);

            $articles_res = iterator_to_array(DB::collection('blogs')->where('article_id', $article_id)->get());
            $user_list = array();
            $category_list = array();
            foreach ($articles_res as $key => $value) {
                if(!in_array($value['auth_uid'], $user_list))
                    $user_list[] = $value['auth_uid'];
                if(!in_array($value['category_id'], $category_list))
                    $category_list[] = $value['category_id'];
            }

            $user_res = iterator_to_array(DB::collection('users')->get());
            $user_data = array();
            foreach ($user_res as $key => $value) {
                if(in_array($value['uid'], $user_list))
                    $user_data[$value['uid']] = $value;
            }

            $cat_res = iterator_to_array(DB::collection('categories')->get());
            $cat_data = array();
            foreach ($cat_res as $key => $value) {
                if(in_array($value['category_id'], $category_list))
                    $cat_data[$value['category_id']] = $value;
            }

            $blog_content = array();
            foreach ($articles_res as $key => $value) {
                $value['auth_usr'] = $user_data[$value['auth_uid']]['usr'];
                $value['auth_img'] = $user_data[$value['auth_uid']]['usr_img'];

                $value['category'] = $cat_data[$value['category_id']]['category_title'];
                $value['likes_c'] = count($value['likes']);

                $blog_content = $value;
            }

            $result = array('status'=>1,'msg'=>'Fetched blog content','content'=>$blog_content);
        }

        return json_encode($result);
    }

    public function like_blog(Request $request)
    {
        $result = array('status'=>0,'msg'=>'Could not like blog','css'=>'alert alert-danger');

        $token = JWTAuth::getToken();
        $payload = array();

        if(!is_bool($token) && $request->has('article_id') && $request->has('l_status')){
            $payload = JWTAuth::decode($token);
            if(isset($payload['uid']))
            {
                $uid = $payload['uid'];
                $l_status = $request->l_status;
                $article_id = $request->article_id;

                $likes = array();
                $blog_res = iterator_to_array(DB::collection('blogs')->where('article_id', $article_id)->get());
                foreach ($blog_res as $key => $value) {
                    $likes = $value['likes'];
                }

                $msg = "Blog liked!";
                if($l_status == 1)
                {
                    if(!in_array($uid, $likes))
                        $likes[] = $uid;
                }else{
                    $msg = "Blog unliked!";
                    if(in_array($uid, $likes))
                    {
                        $tmp_likes = $likes;
                        $likes = array();
                        foreach ($tmp_likes as $key => $value) {
                            if($value != $uid)
                                $likes[] = $value;
                        }
                    }
                }

                DB::collection('blogs')->where('article_id', $article_id)->update(['likes' => $likes]);

                $result = array('status'=>1,'msg'=>$msg,'css'=>'alert alert-success');
            }
        }

        return json_encode($result);
    }

}
