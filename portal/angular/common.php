<?php

    /*top banner*/
    function top_banner($button=0,$fixed=0)
    {
        global $g_cookie_prefix;
        ?>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Kazmik Corp.</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(!isset($_COOKIE[$g_cookie_prefix.'ut'])){ ?>
                        <li ng-click="login_signup(1);"><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li ng-click="login_signup(2);"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <?php }else{ ?>
                        <li ng-click="logout_user();"><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                    <?php } ?>
                </ul>
        </nav>

        <?php if(!isset($_COOKIE[$g_cookie_prefix.'ut'])){ ?>
            <div class="modal" id="login_modal" role="dialog"  visible="login_modal">
        		<div class="modal-dialog login_dialog" role="document" >
        			<div class="modal-content">
        				<div class="modal-header login_modal_header">
        					<button type="button" class="close" data-dismiss="modal" aria-label="Close"  ng-click="login_signup_close()" ><span aria-hidden="true">&times;</span></button>
        				</div>
        			  <div class="modal-body">
        					<?php
        						login_popup();
        					?>
        				</div>
        			</div><!-- Modal Content -->
        		</div><!-- Modal Dialog -->
        	</div>	<!-- login modal -->
        <?php }
    }

    /* login popup */
    function login_popup($popup=1,$login_same_page=1)
    {
        global $g_url;
        $r_url=urlencode('https://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $str=base64_encode($r_url);

        ?>

        <div class="login_signup_box clearfix">
            <div class="col-sm-12 col-xs-12 login_col_right">
                <div class="login_home clearfix" ng-show="lm_home">
                    <div class="text-center"><h4>Start contributing to the blog</h4></div><br/>
                    <div class="col-sm-12">
                        <a class="login_btn btn-1" href="javascript:void(0)" ng-click="login_signup(2);">Sign In</a>
                        <a class="login_btn btn-1" href="javascript:void(0)" ng-click="login_signup(1);">Register</a><br/>
                    </div><!-- login box -->
                </div><!-- Login signup box -->

                <!-- REGISTER -->
                <form class="form clearfix" method="POST" id="signup_form" ng-show="lm_signup">
                    <div  class="signup_box clearfix">
                        <a href="javascript:void(0)" class="back-btn"  ng-click="login_signup(3);"><i class="glyphicon glyphicon-menu-left"></i></a>
                        <div class="text-center"><h4>Create an account</h4></div>
                        <br>
                        <input type="hidden" class="r_url" name="r_url" value="<?php echo $r_url; ?>" ng-model="signup_r_url"/>
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1 form_container">
                            <div class="input-outer-div" style="position:relative;">
                                <input type="text" class="form-control add-form-control inp_field inp_name" placeholder="Full name"  name="usr" ng-model="signup_usr" required ><br/>
                                <span class="full-name-error hidden">Enter valid name</span>
                            </div>

                            <div class="input-outer-div" style="position:relative;">
                                <input type="text" class="form-control add-form-control inp_field inp_email" placeholder="Email ID"  name="email" ng-model="signup_email" required ><br/>
                                <span class="email-error hidden">Enter Email</span>
                            </div>

                            <div class="input-outer-div" style="position:relative;">
                                <a class="show_pswd hidden" onclick="changePswdType($(this))"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <input type="password" class="form-control add-form-control inp_field inp_pass" placeholder="Password"  name="pswd" ng-model="signup_pswd" style='margin-bottom:2px;' required>
                                <span class="pass-error hidden">Enter Password</span>
                            </div>


                            <div class="col-sm-12 progress_main_wrap col-xs-12">
                                <div class="progress hidden col-sm-4 col-xs-4" style="height:5px">
                                    <div class="progress-bar first" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                                <div class="progress hidden col-sm-4 col-xs-4" style="height:5px;">
                                    <div class="progress-bar second" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                                <div class="progress hidden col-sm-4 col-xs-4" style="height:5px;">
                                    <div class="progress-bar third" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-status hidden"><span class="strength">Too Short</span><span class="strength-info">&nbsp;&nbsp;<i class="fa fa-info-circle" aria-hidden="true"></i></span></div>
                            <div style="position:relative">
                                <div class="strength-popup hidden">
                                    Minimum chars is 6.
                                </div>
                            </div>
                            <br>
                            <input class="btn-1 continue-btn" type="submit" value="SIGNUP" ng-click="signup_user()"/><br/>
                        </div>

                        <div class="col-xs-12 text-center" >
                            <div class="new_to_cs" ng-show="!signup_loading">Already a member? <a href="javascript:void(0)" ng-click="login_signup(2);" >Login</a></div>
                        </div>
                    </div>
                </form>

                <!-- Login -->
                <div  class="login_box clearfix" ng-show="lm_login">
                    <a href="javascript:void(0)" class="back-btn"  ng-click="login_signup(3);"><i class="glyphicon glyphicon-menu-left"></i></a>
                    <div class="text-center"><h4>Welcome back!</h4></div><br/>
                    <br>
                    <form class="form clearfix" method="POST" id="login_form">
                        <input type="hidden" class="r_url" name="r_url" value="<?php echo $r_url; ?>" ng-model="login_r_url"/>
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1 form_container">
                            <div class="input-outer-div" style="position:relative;">
                                <input type="text" class="form-control add-form-control inp_field" placeholder="Email ID"  name="email" required ng-model="login_email"><br/>
                                <span class="email-error hidden">Enter Email</span>
                            </div>
                            <div class="input-outer-div" style="position:relative;">
                                <a class="show_pswd" onclick="changePswdType($(this))"><i class="hidden glyphicon glyphicon-eye-open"></i></a>
                                <input type="password" class="form-control add-form-control inp_field" placeholder="Password"  name="pswd" required ng-model="login_pswd"><br/>
                                <span class="pass-error hidden">Enter Password</span>
                            </div>
                            <input type="submit" class="btn-1" value="LOGIN" ng-click="login_user()"/><br/>
                        </div>
                        <div class="col-xs-12 text-center" >
                            <div class="new_to_cs" ng-show="!login_loading">New to Kazmik Corp.? <a href="javascript:void(0)" ng-click="login_signup(1);" >Signup</a></div>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div><br/>
                <div class="status clearfix" ng-show="status_show" ng-bind-html="status_text"></div>
                <div class="enable_cookie_status clearfix hidden">
                    <div class="alert alert-danger">
                        Please enable your browsers cookies to continue.
                    </div>
                </div>
            </div>
        </div><!-- Login signup box -->

        <?php
    }

    /*global error message*/
    function global_error_msg()
    {   ?>
        <div class="global_error_msg hidden">
            <div class="msg_head"></div>
            <div class="msg_body"></div>
            <button class="msg_cta"></button>
        </div>
    <?php
    }

    /* global banner response */
    function global_banner_response()
    {
        ?>
        <div class="global_banner_response hidden">
            <div class="row">
                <div class="col-sm-10 col-xs-10 content_wrapper">
                    <span class="banner_msg"></span>
                </div>
                <div class="col-sm-2 col-xs-2 text-right content_wrapper">
                    <a href="javascript:void(0)" class="close_banner" onclick="$('.global_banner_response').addClass('hidden')">
                        <i class="fa fa-times close_icon"></i>
                    </a>
                </div>
            </div>
        </div>

        <?php
    }
?>
