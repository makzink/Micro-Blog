var g_url = "http://www.kazmik.in/mb/ng/";
var g_api_url = "http://lvl.kazmik.in/";
var g_domain = ".kazmik.in";


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
