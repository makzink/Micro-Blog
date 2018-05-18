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
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-sanitize.js"></script>
    </head>
    <body ng-app="hush_app" ng-controller="hush_ctrl">

        <?php top_banner(); ?>

        <div class="container-fluid blog_container">

            <div class="blog_options">
                <!-- Topic Selector-->
                <div class="f_type pull-right b_opt">
                    <div class="f10">Filter by :</div>
                    <div class="form-group">
                        <select class="form-control" ng-model="b_topic" ng-change="filter_blog()">
                            <option value="0">All</option>
                            <option value="1">Career</option>
                            <option value="2">Culture</option>
                            <option value="3">Compensation</option>
                        </select>
                    </div>
                </div>
                <!-- Type Selector-->
                <div class="f_type pull-right b_opt">
                    <div class="f10">Sort by :</div>
                    <div class="form-group">
                        <select class="form-control" ng-model="b_sort" ng-change="sort_blog()">
                            <option value="1">Most Popular</option>
                            <option value="2">Latest</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="blog_list col-xs-offset-1" ng-show="!hush_loading">
                <div class="b_card pull-left" id="b_card" ng-repeat="card in blog_data.content" ng-click="card_click(card.article_id);">
                    <div class="b_like">
                        <span class="toggle-icon" ng-class="{'-checked':card.like_status}" title="Like Blog" ng-click="like_blog($event,card)"></span>
                    </div>
                    <div class="clearfix">
                        <img class="b_img" ng-src="{{card.img}}" src="https://www.asknisa.org/wp-content/themes/ri-charitable/images/placeholder.jpg"/>
                    </div>
                    <div class="clearfix">
                        <div class="b_cat">{{card.category}}</div>
                    </div>
                    <div class="clearfix">
                        <div class="b_title">{{card.title}}</div>
                    </div>
                    <div class="footer clearfix">
                        <div class="b_readon pull-left">READ ON</div>
                        <div class="b_auth pull-right">
                            <span class="b_auth_name">{{card.auth_usr}}</span>
                            <img class="b_auth_img" ng-src="{{card.auth_img}}" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                        </div>
                        <div class="clearfix"></div>
                        <div class="b_bar"></div>
                    </div>
                </div>
            </div>

            <!--hush loading-->
            <center>
                <div class="hush_loading_wrapper">
                    <div class="hush_loading" ng-show="hush_loading">
                        <svg class="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20" r="15">
                        </svg>
                    </div>
                </div>
            </center>

        </div>

        <div class="page_footer">
        </div>

        <!-- login modal -->
        <script type="text/ng-template" id="login_modal.html">
            <div id="login_modal">
                <div class="modal-header login_modal_header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ng-click="cancel()" ><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <?php
                    login_popup();
                    ?>
                </div>
            </div>
        </script>

        <!-- blog detail modal -->
        <script type="text/ng-template" id="detail_modal.html">
            <div id="detail_modal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" ng-click="cancel()">&times; </button>
                </div>
                <div class="modal-body">
                    <div class="dm_body" ng-show="!dm_loading">
                        <div class="clearfix">
                            <img class="dm_img" ng-src="{{dm_data.img}}" src="https://www.asknisa.org/wp-content/themes/ri-charitable/images/placeholder.jpg"/>
                        </div>
                        <div class="clearfix">
                            <div class="dm_title">{{dm_data.title}}</div>
                        </div>
                        <div class="clearfix">
                            <div class="dm_cat">{{dm_data.category}}</div>
                            <div class="dm_date">{{dm_data.date}}</div>
                        </div>
                        <div class="clearfix dm_data">
                            <div class="stats pull-left">
                                <span class="views"><i class="fa fa-eye"></i><span class="views_c"> {{dm_data.views}}</span></span>
                                <span class="likes"><i class="fa fa-heart"></i><span class="likes_c"> {{dm_data.likes_c}}</span></span>
                            </div>
                            <div class="dm_auth pull-right">
                                <span class="dm_auth_name">{{dm_data.auth_usr}}</span>
                                <img class="dm_auth_img" ng-src="{{dm_data.auth_img}}" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                            </div>
                        </div>
                        <div class="clearfix">
                            <div class="dm_content" ng-bind-html="dm_data.content"></div>
                        </div>
                    </div>
                    <!--hush loading-->
                    <center>
                        <div class="hush_loading_wrapper">
                            <div class="hush_loading" ng-show="dm_loading">
                                <svg class="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="15">
                                </svg>
                            </div>
                        </div>
                    </center>

                </div>
            </div>
        </script>

        <script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.12.1.js"></script>
        <script src="home.js"></script>
        <script src="static/js/common.js"></script>
    </body>
</html>
