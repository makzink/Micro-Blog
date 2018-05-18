<?php


    /*generate user id*/
    function generate_uid($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $uid = '';
        for ($i = 0; $i < $length; $i++) {
            $uid .= $characters[rand(0, $charactersLength - 1)];
        }

        $user_res = iterator_to_array(DB::collection('users')->where('uid',$uid)->get());
        if(count($user_res) > 0)
            $uid = generate_uid();
        return $uid;
    }
