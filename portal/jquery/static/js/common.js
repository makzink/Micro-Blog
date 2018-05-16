var g_url = "http://www.kazmik.in/mb/jq/";
var g_api_url = "http://api.kazmik.in/php/";
var g_domain = ".kazmik.in";

$(function(){

    $("#signup_form").submit(function(e){
        return signup_user();
    });// SIGNUP FORM

    $("#login_form").submit(function(e){
        return login_user();
    });// Login Form

});

/** Login Signup Popup**/
function login_signup(mode)
{
    $('#login_modal').modal('show');
    $('.login_signup_box .status').html('');
    if(mode==1)
    {
        $('.signup_box').removeClass('hidden');
        $('.user_name_box').addClass('hidden');
        $('.login_box').addClass('hidden');
        $('.login_home').addClass('hidden');
        $('.user_name_box').addClass('hidden');
        $('#signup_form .inp_pass').val('');
        $('#signup_form .progress_main_wrap').addClass('hidden');
        $('#signup_form .progress-status').addClass('hidden');
        $('#signup_form .email-error').addClass('hidden');
        $('#signup_form .pass-error').addClass('hidden');
        $('#signup_form .full-name-error').addClass('hidden');
        $("#signup_form .continue-btn").css('cursor','pointer');
    }
    else if(mode==2)
    {
        $('.signup_box').addClass('hidden');
        $('.login_box').removeClass('hidden');
        $('.login_home').addClass('hidden');
        $('.user_name_box').addClass('hidden');
    }
    else if(mode==3)
    {
        setTimeout(function(){$('.login_signup_box .login_home').removeClass('hidden');},200);
        $('.login_box').addClass('hidden');
        $('.signup_box').addClass('hidden');
    }
    else if(mode==4)
    {
        $('.signup_box').addClass('hidden');
        $('.login_box').addClass('hidden');
        $('.user_name_box').removeClass('hidden');
        $('.login_home').addClass('hidden');
    }
    else{
        $('.login_signup_box .login_home').removeClass('hidden');
        $('.signup_box').addClass('hidden');
        $('.login_box').removeClass('hidden');
        $('.login_home').addClass('hidden');
        $('.user_name_box').addClass('hidden');
    }
}

/*signup user*/
function signup_user()
{
    $('.login_signup_box .subtext').addClass("hidden");
    $('.new_to_cs').addClass("hidden");
    $('.login_signup_box .status').html("<div class='loading'><i></i><i></i><i></i></div>");
    var user_data=$('.signup_box form input').serialize();
    var email = $("#signup_form input[name='email']").val();
    var pswd = $("#signup_form  input[name='pswd']").val();
    var usr = $("#signup_form  input[name='usr']").val();
    var r_url = window.location.href.split('#')[0];
    $.ajax({
        url: g_api_url+"auth/signup/",
        dataType: "json",
        type:"POST",
        data:
        {
            'email':email,
            'pswd':pswd,
            'r_url':r_url,
            'usr':usr,
        },
        success: function( json )
        {
            if(typeof json !== 'undefined')
            {
                $('.login_signup_box .status').empty().append($('<div>').html(json.msg).addClass(json.css));
                if(json.status==1)
                {
                    document.cookie = "_hush_ut=" + json.user.token_data.token + ";domain="+g_domain+";path=/";
                    setTimeout(function(){ window.location = decodeURIComponent(json.r_url); }, 2000);
                }
                else{
                    setTimeout(function(){
                        $('.login_signup_box .status').empty();
                        $('.login_signup_box .subtext').removeClass("hidden");
                        $('.new_to_cs').removeClass("hidden");
                    },3000);
                }
            }
            else {
                $('.login_signup_box .status').empty().append($('<div/>').html("<strong>Error ! </strong> Sorry we could not create your account now. Please try again.").addClass("alert alert-danger"));
                setTimeout(function(){
                    $('.login_signup_box .status').empty();
                    $('.login_signup_box .subtext').removeClass("hidden");
                    $('.new_to_cs').removeClass("hidden");
                },3000);
            }
        },
        error : function()
        {
            $('.login_signup_box .status').empty().append($('<div/>').html("<strong>Failed</strong> Sorry we could not create your account now. Please try again.").addClass("alert alert-danger"));
            setTimeout(function(){
                $('.login_signup_box .status').empty();
                $('.login_signup_box .subtext').removeClass("hidden");
                $('.new_to_cs').removeClass("hidden");
            },3000);
        }
    });
    return false;
}

/*login user*/
function login_user()
{
    var email = $("#login_form input[name='email']").val();
    var pswd = $("#login_form  input[name='pswd']").val();
    var allow_login = true;
    if(email == undefined || email == ""){
        $("#login_form .email-error").removeClass("hidden");
        allow_login = false;
    }
    if(pswd == undefined || pswd == ""){
        $("#login_form .pass-error").removeClass("hidden");
        allow_login = false;
    }
    $("#login_form input[name='email']").keyup(function(){
        $("#login_form .email-error").addClass("hidden");
    });
    $("#login_form input[name='pswd']").keyup(function(){
        $("#login_form .pass-error").addClass("hidden");
    });

    if(allow_login){

        $('.login_signup_box .new_to_cs').addClass("hidden");
        $('.login_signup_box .status').html("<div class='loading'><i></i><i></i><i></i></div>");

        var r_url = window.location.href;
        $.ajax({
            url: g_api_url+"auth/login/",
            dataType: "json",
            type:"POST",
            data:
            {
                'email':email,
                'pswd':pswd,
                'r_url':r_url
            },
            success: function( json )
            {
                if(typeof json.status !=='undefined')
                {
                    $('.login_signup_box .status').empty().append($('<div>').html(json.msg).addClass(json.css));
                    if(json.status==1)
                    {
                        document.cookie = "_hush_ut=" + json.user.token_data.token + ";domain="+g_domain+";path=/";
                        setTimeout(function(){ window.location = decodeURIComponent(json.r_url); }, 2000);
                    }
                    else{
                        setTimeout(function(){
                            $('.login_signup_box .status').empty();
                            $('.login_signup_box .subtext').removeClass("hidden");
                            $('.login_signup_box .new_to_cs').removeClass("hidden");
                        },3000);
                    }
                }
                else{
                    $('.login_signup_box .status').empty().append($('<div>').html('<strong>Error!</strong> Error processing your login. Please try again!').addClass('alert alert-danger'));
                    setTimeout(function(){
                        $('.login_signup_box .status').empty();
                        $('.login_signup_box .subtext').removeClass("hidden");
                        $('.login_signup_box .new_to_cs').removeClass("hidden");
                    },3000);
                }
            },
            error : function()
            {
                $('.login_signup_box .status').empty().append($('<div>').html('<strong>Error!</strong> Error processing your login. Please try again!').addClass('alert alert-danger'));
                setTimeout(function(){
                    $('.login_signup_box .status').empty();
                    $('.login_signup_box .subtext').removeClass("hidden");
                    $('.login_signup_box .new_to_cs').removeClass("hidden");
                },3000);
            }
        });
    }
    return false;
}

/*logout user*/
function logout_user()
{
    remove_user_cookies();
    window.location = g_url;
}

/*get cookie by name*/
function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}

/*delete cookie*/
var delete_cookie = function(name) {
    console.log(name,g_domain);
    document.cookie = name+'=;path=/;expires='+new Date(0).toUTCString()+";domain="+g_domain;
};

/*check if the cookies are enabled in the browser*/
function isCookiesEnabled()
{
    var cookieEnabled = navigator.cookieEnabled;
    return cookieEnabled;
}

/*remove all user cookies*/
function remove_user_cookies()
{
    delete_cookie('_hush_ut');
}

function error_response(err)
{
    /*error response from server*/
    if(err!=undefined && err.responseText!=undefined)
    {
        var message = err.responseText;
        var status = err.status;
        setTimeout(function() {
            switch(status)
            {
                /*access denied*/
                case 403:
                case 401:
                case 302:
                /*unset cookie token*/
                remove_user_cookies()
                var current_url = window.location.href;
                if(g_url!=undefined && current_url!=g_url)
                    alert("Your session has expired. Please login to authorize your account.");
                window.location = g_url;
                break;
                /*limit reached*/
                case 429:
                    alert("Too many requests from this account. Please wait for a minute and try again.");
                break;
                /*server error*/
                case 500:
                    console.log("Server Error. Contact the support team.");
                break;
            }
        }, 1000);
    }
    /*timeout or no response from server*/
    else
    {
        console.log("Timeout Error. Check your network. Please try again after sometime. If nothing works contact support team.");
    }
}

/*fetch g_url*/
function fetch_g_url()
{
    var hostname = location.hostname;
    var protocol = location.protocol;
    var g_url = protocol+"//"+hostname+"/";
    return g_url;
}

/*show global banner response*/
function show_global_banner_response(msg,status)
{
    $(".global_banner_response").removeClass("success");
    $(".global_banner_response").removeClass("error");
    $(".global_banner_response").addClass(status);
    $(".global_banner_response .banner_msg").html(msg);
    $(".global_banner_response").removeClass("hidden");
    setTimeout(function () {
        $(".global_banner_response").addClass("hidden");
    }, 5000);
}

function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace('-', '+').replace('_', '/');
    var json= JSON.parse(window.atob(base64));
    return json;
};

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires;
    console.log(cvalue+"  cookie created!");
}
