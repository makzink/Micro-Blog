<?php
    include("config.php");
    include("common.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <!-- mobile meta -->
    	<meta name="HandheldFriendly" content="True">
    	<meta name="MobileOptimized" content="320">
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    	<!--title/meta_desc-->
    	<title>Blog | Kazmik Corp.</title>
        <meta name="description" content="">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="static/css/common.css">
        <link rel="stylesheet" href="home.css">
    </head>
    <body>

        <?php top_banner(); ?>

        <div class="container blog_container">

            <div class="blog_options">
                <!-- Type Selector-->
                <div class="f_type pull-right b_opt">
                    <div class="f10">Sort by :</div>
                    <div class="form-group">
                        <select class="form-control" id="b_sort">
                            <option value="1">Most Popular</option>
                            <option value="2">Latest</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="blog_list col-xs-offset-1">
            </div>

            <!--hush loading-->
            <center>
                <div class="hush_loading_wrapper">
                    <div class="hush_loading hidden">
                        <svg class="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="15">
                        </svg>
                    </div>
                </div>
            </center>

        </div>

        <!-- blog card clone -->
        <div class="b_card pull-left hidden" id="b_card">
            <div class="b_like">
                <span class="toggle-icon" title="Like Blog"></span>
            </div>
            <div class="clearfix">
                <img class="b_img" src="https://www.asknisa.org/wp-content/themes/ri-charitable/images/placeholder.jpg"/>
            </div>
            <div class="clearfix">
                <div class="b_cat pull-left">Card Category</div>
            </div>
            <div class="clearfix">
                <div class="b_title">Card Title</div>
            </div>
            <div class="footer clearfix">
                <div class="b_readon pull-left">READ ON</div>
                <div class="b_auth pull-right">
                    <span class="b_auth_name">Author Name</span>
                    <img class="b_auth_img" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                </div>
                <div class="clearfix"></div>
                <div class="b_bar"></div>
            </div>
        </div>

        <!-- blog detail modal -->
        <div id="detail_modal" class="modal fade detail_modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body">

                        <div class="dm_body">
                            <div class="clearfix">
                                <img class="dm_img" src="https://www.asknisa.org/wp-content/themes/ri-charitable/images/placeholder.jpg"/>
                            </div>
                            <div class="clearfix">
                                <div class="dm_title">Card Title</div>
                            </div>
                            <div class="clearfix">
                                <div class="dm_cat pull-left">Card Category</div>
                            </div>
                            <div class="clearfix dm_data">
                                <div class="stats pull-left">
                                    <span class="views"><i class="fa fa-eye"></i><span class="views_c"> 10</span></span>
                                    <span class="likes"><i class="fa fa-heart"></i><span class="likes_c"> 2</span></span>
                                </div>
                                <div class="dm_auth pull-right">
                                    <span class="dm_auth_name">Author Name</span>
                                    <img class="dm_auth_img" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="dm_content">Card Content</div>
                            </div>
                        </div>

                        <!--hush loading-->
                        <center>
                            <div class="hush_loading_wrapper">
                                <div class="hush_loading hidden">
                                    <svg class="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="20" cy="20" r="15">
                                    </svg>
                                </div>
                            </div>
                        </center>

                    </div>
                </div>

            </div>
        </div>

        <!--global error-->
        <?php
            global_error_msg();
            global_banner_response();
        ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="static/js/common.js"></script>
        <script src="home.js"></script>
    </body>
</html>
