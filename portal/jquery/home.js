var default_img = "https://www.asknisa.org/wp-content/themes/ri-charitable/images/placeholder.jpg";
var default_usr = "http://medondoor.com/wp-content/themes/health/img/placeholder.png";
$(function(){


    $('.blog_options #b_sort').on('change', function() {
        fetch_blog();
    })
    /*fetch blog content*/
    fetch_blog();

});

/*fetch blog content*/
function fetch_blog()
{
    $('.blog_container .hush_loading').removeClass("hidden");
    $('.blog_container .blog_list').addClass("hidden");

    var b_sort = $(".blog_options  #b_sort").val();

    var user_token = getCookie("_hush_ut");
    $.ajax({
        url: g_api_url+"blog/fetch/",
        dataType: "json",
        headers:{
            "Authorization":user_token
        },
        type:"POST",
        data:
        {
            'b_sort':b_sort
        },success: function( json )
        {
            $('.blog_container .hush_loading').addClass("hidden");
            $('.blog_container .blog_list').removeClass("hidden");
            if(json.status == 1)
            {
                $('.blog_container .blog_list').empty();
                for (var i = 0; i < json.content.length; i++) {
                    card_cloner(json.content[i],$('.blog_container .blog_list'));
                }
            }else{
                $(".global_error_msg").removeClass("hidden");
                $(".global_error_msg .msg_head").html("Oh No!");
                $(".global_error_msg .msg_body").html(json.msg);
                $(".global_error_msg .msg_cta").unbind("click");
                $(".global_error_msg .msg_cta").html("Retry").click(function(){
                    $(".global_error_msg").addClass("hidden");
                    fetch_blog();
                });
            }

        },error : function(json)
        {
            $('.blog_container .hush_loading').addClass("hidden");
            $('.blog_container .blog_list').removeClass("hidden");
            $(".global_error_msg").removeClass("hidden");
            $(".global_error_msg .msg_head").html("Oh No!");
            $(".global_error_msg .msg_body").html("Something went wrong!");
            $(".global_error_msg .msg_cta").unbind("click");
            $(".global_error_msg .msg_cta").html("Retry").click(function(){
                $(".global_error_msg").addClass("hidden");
                fetch_blog();
            });
        }

    });
}

/*clone blog card*/
function card_cloner(card_content,container)
{
    var b_card = $('#b_card').clone();

    b_card.attr('data-bid',card_content.article_id);
    b_card.find('.b_img').attr('src',card_content.img);
    b_card.find('.b_title').html(card_content.title);
    b_card.find('.b_auth_name').html(card_content.auth_usr);

    if(card_content.like_status == 1)
    {
        b_card.find('.b_like .toggle-icon').addClass('-checked');
    }else{
        b_card.find('.b_like .toggle-icon').removeClass('-checked');
    }

    b_card.find('.b_like .toggle-icon').click(function(e){
        e.stopPropagation();
        var user_token = getCookie("_hush_ut");
        if(user_token == undefined)
        {
            login_signup(3);
        }else{
            if(b_card.find('.b_like .toggle-icon').hasClass('-checked'))
            {
                like_blog(0,card_content.article_id);
                b_card.find('.b_like .toggle-icon').removeClass('-checked');
            }else{
                like_blog(1,card_content.article_id);
                b_card.find('.b_like .toggle-icon').addClass('-checked');
            }
        }
    });

    b_card.click(function(){
        read_blog(card_content.article_id);
    });

    b_card.removeClass('hidden');
    container.append(b_card);
}

/*read the blog*/
function read_blog(article_id)
{
    $('#detail_modal').modal('show');
    $('#detail_modal .modal-body .dm_body').addClass("hidden");
    $('#detail_modal .modal-body .hush_loading').removeClass("hidden");

    var user_token = getCookie("_hush_ut");
    $.ajax({
        url: g_api_url+"blog/read/",
        dataType: "json",
        headers:{
            "Authorization":user_token
        },
        type:"POST",
        data:
        {
            'article_id':article_id
        },
        success: function( json )
        {
            if(json.status == 1)
            {
                $('#detail_modal .dm_img').attr('src',json.content.img);
                $('#detail_modal .dm_title').html(json.content.title);
                $('#detail_modal .dm_content').html(json.content.content);
                $('#detail_modal .likes_c').html("  "+json.content.likes_c);
                $('#detail_modal .views_c').html("  "+json.content.views);
                $('#detail_modal .dm_auth_name').html(json.content.auth_usr);

                $('#detail_modal .modal-body .dm_body').removeClass("hidden");
                $('#detail_modal .modal-body .hush_loading').addClass("hidden");
            }else{
                $('#detail_modal').modal('hide');
                $(".global_error_msg").removeClass("hidden");
                $(".global_error_msg .msg_head").html("Oh No!");
                $(".global_error_msg .msg_body").html(json.msg);
                $(".global_error_msg .msg_cta").unbind("click");
                $(".global_error_msg .msg_cta").html("Retry").click(function(){
                    $(".global_error_msg").addClass("hidden");
                    read_blog(article_id);
                });
            }

        },
        error : function(json)
        {
            $('#detail_modal').modal('hide');
            $(".global_error_msg").removeClass("hidden");
            $(".global_error_msg .msg_head").html("Oh No!");
            $(".global_error_msg .msg_body").html("Something went wrong!");
            $(".global_error_msg .msg_cta").unbind("click");
            $(".global_error_msg .msg_cta").html("Retry").click(function(){
                $(".global_error_msg").addClass("hidden");
                read_blog(article_id);
            });
        }
    });
}

/*like the blog*/
function like_blog(l_status,article_id)
{
    var user_token = getCookie("_hush_ut");
    $.ajax({
        url: g_api_url+"blog/like/",
        dataType: "json",
        headers:{
            "Authorization":user_token
        },
        type:"POST",
        data:
        {
            'l_status':l_status,
            'article_id':article_id
        },success: function( json )
        {
            if(json.status != 1)
            {
                $(".global_error_msg").removeClass("hidden");
                $(".global_error_msg .msg_head").html("Oh No!");
                $(".global_error_msg .msg_body").html(json.msg);
                $(".global_error_msg .msg_cta").unbind("click");
                $(".global_error_msg .msg_cta").html("Retry").click(function(){
                    $(".global_error_msg").addClass("hidden");
                    like_blog(l_status,article_id);
                });
            }

        },error : function(json)
        {
            $(".global_error_msg").removeClass("hidden");
            $(".global_error_msg .msg_head").html("Oh No!");
            $(".global_error_msg .msg_body").html("Something went wrong!");
            $(".global_error_msg .msg_cta").unbind("click");
            $(".global_error_msg .msg_cta").html("Retry").click(function(){
                $(".global_error_msg").addClass("hidden");
                like_blog(l_status,article_id);
            });
        }
    });
}
