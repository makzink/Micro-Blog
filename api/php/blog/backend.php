<?php

    /*fethc blog content*/
    function fetch_blog($request,$response)
    {
        $result = array("status"=>0,"msg"=>"Unable to fetch blog","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();
        $token_params = $request->getAttribute("token_params");

        $mdb = mongoDB(0);
        $blog_coll = $mdb->blogs;
        $user_coll = $mdb->users;
        $cat_coll =  $mdb->categories;

        if(isset($request_params['b_sort']))
        {
            $b_sort = $request_params['b_sort'];
            $uid = 0;
            if(isset($token_params['uid']))
                $uid = $token_params['uid'];

            $project = array('$project'=>array('article_id'=>1,'title'=>1,'img'=>1,'category_id'=>1,'auth_uid'=>1,'tags'=>1,'likes'=>1,'views'=>1));
            $addFields = array('$addFields'=>array('like_c'=>array('$size'=>'$likes')));
            $sort = array('$sort'=>array());
            switch ($b_sort) {
                case '1':
                    $sort['$sort']['like_c'] = -1;
                    break;
                case '2':
                    $sort['$sort']['create_time'] = -1;
                    break;
            }

            $res = iterator_to_array($blog_coll->aggregate(array($project,$addFields,$sort)));

            $user_list = array();
            $category_list = array();
            foreach ($res as $key => $value) {
                if(!in_array($value['auth_uid'], $user_list))
                    $user_list[] = $value['auth_uid'];
                if(!in_array($value['category_id'], $category_list))
                    $category_list[] = $value['category_id'];
            }

            $user_res = iterator_to_array($user_coll->find(array('uid'=>array('$in'=>$user_list))));
            $user_data = array();
            foreach ($user_res as $key => $value) {
                $user_data[$value['uid']] = $value;
            }

            $cat_res = iterator_to_array($cat_coll->find(array('category_id'=>array('$in'=>$category_list))));
            $cat_data = array();
            foreach ($cat_res as $key => $value) {
                $cat_data[$value['category_id']] = $value;
            }

            $blog_content = array();
            foreach ($res as $key => $value) {

                $value['auth_usr'] = $user_data[$value['auth_uid']]['usr'];
                $value['auth_img'] = $user_data[$value['auth_uid']]['usr_img'];

                $value['category'] = $cat_data[$value['category_id']]['category_title'];

                $value['like_status'] = 0;
                $likes = array();
                foreach ($value['likes'] as $k => $v) {
                    $likes[] = $v;
                }
                // var_dump($likes,$uid,in_array($uid, $likes),is_string($uid),is_string($uid) && in_array($uid, $likes),"-----");
                if(is_string($uid) && in_array($uid, $likes))
                {
                    $value['like_status'] = 1;
                }

                $blog_content[] = $value;
            }

            $result = array('status'=>1,'msg'=>'Fetched blog content','content'=>$blog_content);

        }

        return_response($request,$response,$result);
    }

    /*read blog - fetch details*/
    function read_blog($request,$response)
    {
        $result = array("status"=>0,"msg"=>"Unable to fetch blog details","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();
        $token_params = $request->getAttribute("token_params");

        $mdb = mongoDB(0);
        $blog_coll = $mdb->blogs;
        $user_coll = $mdb->users;
        $cat_coll =  $mdb->categories;

        if(isset($request_params['article_id']))
        {
            $article_id = $request_params['article_id'];

            $query = array('article_id'=>$article_id);

            //update view count
            $update = array('$inc'=>array('views'=>1));
            $blog_coll->updateOne($query,$update);

            $blog_res = iterator_to_array($blog_coll->findOne($query));

            $user_res = iterator_to_array($user_coll->find(array('uid'=>$blog_res['auth_uid'])));
            $user_data = array();
            foreach ($user_res as $key => $value) {
                $user_data[$value['uid']] = $value;
            }

            $cat_res = iterator_to_array($cat_coll->find(array('category_id'=>$blog_res['category_id'])));
            $cat_data = array();
            foreach ($cat_res as $key => $value) {
                $cat_data[$value['category_id']] = $value;
            }

            if($blog_res != null)
            {
                $blog_content = $blog_res;

                $blog_content['auth_usr'] = $user_data[$blog_content['auth_uid']]['usr'];
                $blog_content['auth_img'] = $user_data[$blog_content['auth_uid']]['usr_img'];

                $blog_content['category'] = $cat_data[$blog_content['category_id']]['category_title'];

                $blog_content['likes_c'] = count($blog_res['likes']);
                $result = array('status'=>1,'msg'=>'Fetched blog content','content'=>$blog_content);
            }
        }

        return_response($request,$response,$result);
    }

    /*add user like to blog*/
    function like_blog($request,$response)
    {
        $result = array("status"=>0,"msg"=>"Unable to like blog","css"=>"alert alert-danger");
        $request_params = $request->getParsedBody();
        $token_params = $request->getAttribute("token_params");

        $mdb = mongoDB(0);
        $blog_coll = $mdb->blogs;

        if(isset($request_params['l_status']) && isset($request_params['article_id']) && isset($token_params['uid']))
        {
            $uid = $token_params['uid'];
            $l_status = $request_params['l_status'];
            $article_id = $request_params['article_id'];

            $query = array('article_id'=>$article_id);
            $update = array('$push'=>array('likes'=>$uid));

            $msg = 'Blog liked!';
            if($l_status == 0)
            {
                $msg = 'Blog unliked!';
                $update = array('$pull'=>array('likes'=>$uid));
            }

            if($blog_coll->updateOne($query,$update))
                $result = array('status'=>1,'msg'=>$msg);
        }

        return_response($request,$response,$result);
    }

?>
