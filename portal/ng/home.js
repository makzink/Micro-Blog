
var app = angular.module('hush_app', ['ui.bootstrap','ngSanitize']);

// controller
app.controller('hush_ctrl', function($scope, $rootScope, $http, $modal) {

    $scope.detail_modal = false;
    $scope.b_sort = '1';

    //login
    $scope.login_signup = function(mode)
    {
        $rootScope.login_mode = mode;
        var login_modal = $modal.open({
            templateUrl: 'login_modal.html',
            controller: 'login_ctrl'
        });
        login_modal.result.then(function (json) {}, function () {});
    }

    $scope.logout_user = function()
    {
        remove_user_cookies();
        window.location = g_url;
    }

    //fetch_blog
    fetch_blog();

    $scope.sort_blog = function()
    {
        fetch_blog();
    }

    $scope.card_click = function(article_id)
    {
        read_blog(article_id);
    }

    function fetch_blog() {
        $scope.hush_loading = true;
        b_sort = $scope.b_sort;
        var user_token = getCookie("_hush_ut");
        $http({
            method: 'POST',
            url: g_api_url+'blog/fetch/',
            headers: {
                "Authorization":'Bearer '+user_token
            },
            data: {
                'b_sort':b_sort
            }
        }).then(function (json){
            $scope.hush_loading = false;
            $scope.blog_data = json.data;
            console.log(json.data);
        },function (json){
            $scope.hush_loading = false;
            console.log(json);
        });
    }

    //read blog
    function read_blog(article_id)
    {
        $rootScope.article_id = article_id;
        var dm_modal = $modal.open({
            templateUrl: 'detail_modal.html',
            controller: 'dm_ctrl'
        });
        dm_modal.result.then(function (json) {}, function () {});
    }

    //like blog
    $scope.like_blog = function(event,$card)
    {
        event.stopPropagation();

        var user_token = getCookie("_hush_ut");
        if(user_token == undefined)
        {
            $scope.login_signup(3);
        }else{
            console.log($card.article_id,$card.like_status);
            if($card.like_status==0){
                submit_like(1,$card.article_id);
                $card.like_status=1;
            }
            else{
                submit_like(0,$card.article_id);
                $card.like_status=0;
            }

        }

    }

    function submit_like(l_status,article_id)
    {
        var user_token = getCookie("_hush_ut");
        $http({
            method: 'POST',
            url: g_api_url+'blog/like/',
            headers: {
                "Authorization":'Bearer '+user_token
            },
            data: {
                'l_status':l_status,
                'article_id':article_id
            }
        }).then(function (json){
            console.log(json.data);
        },function (json){
            console.log(json);
        });
    }

});

//login controller
app.controller('login_ctrl', function ($scope, $rootScope, $http, $modalInstance){

    var mode = $rootScope.login_mode;

    $scope.lm_home = false;
    $scope.lm_signup = false;
    $scope.lm_login = false;
    $scope.signup_loading = false;
    $scope.login_loading = false;
    $scope.status_show = false;
    modal_switch(mode);

    $scope.login_signup = function(mode)
    {
        $scope.lm_home = false;
        $scope.lm_signup = false;
        $scope.lm_login = false;
        modal_switch(mode);
    }

    function modal_switch(mode)
    {
        switch(mode)
        {
            case 1:
                $scope.lm_signup = true;
                break;
            case 2:
                $scope.lm_login = true;
                break;
            case 3:
            default:
                $scope.lm_home = true;
                break;
        }
    }

    $scope.signup_user = function()
    {
        $scope.signup_loading = true;
        $scope.status_show = true;
        $scope.status_text = "<div class='loading'><i></i><i></i><i></i></div>";

        var email = $scope.signup_email;
        var usr = $scope.signup_usr;
        var pswd = $scope.signup_pswd;
        var r_url = window.location.href.split('#')[0];

        $http({
            method: 'POST',
            url: g_api_url+'user/signup/',
            data: {
                'email':email,
                'usr':usr,
                'pswd':pswd,
                'r_url':r_url
            }
        }).then(function (json){

            if(json.data.status==1)
            {
                $scope.status_text = "<div class='"+json.data.css+"'>"+json.data.msg+"</div>";
                document.cookie = "_hush_ut=" + json.data.user.token_data.token + ";domain="+g_domain+";path=/";
                setTimeout(function(){ window.location = decodeURIComponent(json.data.r_url); }, 2000);
            }else{
                $scope.status_text = "<div class='"+json.data.css+"'>"+json.data.msg+"</div>";
                setTimeout(function(){
                    $scope.status_text = "";
                    $scope.signup_loading = false;
                    $scope.status_show = false;
                },3000);
            }

        },function (json){
            $scope.status_text = "<div><strong>Error ! </strong> Sorry we could not create your account now. Please try again.</div>";
            setTimeout(function(){
                $scope.status_text = "";
                $scope.signup_loading = false;
                $scope.status_show = false;
            },3000);
        });

    }

    $scope.login_user = function()
    {
        $scope.login_loading = true;
        $scope.status_show = true;
        $scope.status_text = "<div class='loading'><i></i><i></i><i></i></div>";

        var email = $scope.login_email;
        var pswd = $scope.login_pswd;
        var r_url = window.location.href.split('#')[0];

        $http({
            method: 'POST',
            url: g_api_url+'user/login/',
            data: {
                'email':email,
                'pswd':pswd,
                'r_url':r_url
            }
        }).then(function (json){

            if(json.data.status==1)
            {
                $scope.status_text = "<div class='"+json.data.css+"'>"+json.data.msg+"</div>";
                document.cookie = "_hush_ut=" + json.data.user.token_data.token + ";domain="+g_domain+";path=/";
                setTimeout(function(){ window.location = decodeURIComponent(json.data.r_url); }, 2000);
            }else{
                $scope.status_text = "<div class='"+json.data.css+"'>"+json.data.msg+"</div>";
                setTimeout(function(){
                    $scope.status_text = "";
                    $scope.login_loading = false;
                    $scope.status_show = false;
                },3000);
            }

        },function (json){
            $scope.status_text = "<div><strong>Error ! </strong> Sorry we could not create your account now. Please try again.</div>";
            setTimeout(function(){
                $scope.status_text = "";
                $scope.login_loading = false;
                $scope.status_show = false;
            },3000);
        });
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});

// detail modal controller
app.controller('dm_ctrl', function ($scope, $rootScope, $http, $modalInstance){

    var article_id = $rootScope.article_id;

    $scope.dm_loading = true;

    var user_token = getCookie("_hush_ut");
    $http({
        method: 'POST',
        url: g_api_url+'blog/read/',
        headers: {
            "Authorization":'Bearer '+user_token
        },
        data: {
            'article_id':article_id
        }
    }).then(function (json){
        $scope.dm_loading = false;
        $scope.dm_data = json.data.content;
    },function (json){
        $scope.dm_loading = false;
    });

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
});
