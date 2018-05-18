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
        <style>
            .mb_heading
            {
                font-size: 1.5em;
                font-weight: 500;
                font-style: italic;
                margin-top: 5em;
                color: #446CB3;
            }
            .mb_tag
            {
                font-size: 1.2em;
                font-weight: 300;
                margin-top: 1em;
            }
            .mb_sets
            {
                margin-top: 5em;
            }
            .mb_card
            {
                background-color: #FFF;
                border-radius: 5px;
                width: 15em;
                height: 15em;
                border: 1px solid #EEEEEE;
                margin: 1em;
                box-shadow: 0 1px 1px 0 rgba(0,0,0,0.10);
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,0.10);
                -moz-box-shadow: 0 1px 1px 0 rgba(0,0,0,0.10);
                position: relative;
                cursor: pointer;
                text-align: center;
            }
            .mb_card a
            {
                text-decoration: none;
            }
            .mb_card .card_head
            {
                font-size: 1.3em;
                font-weight: 500;
                margin-top: 3em;
                color: #000;
            }
            .mb_card .card_tag
            {
                font-size: 1.1em;
                font-weight: 300;
                margin-top: 1em;
                color: #000;
            }
        </style>
    </head>
    <body>

        <div class="container">

            <div class="mb_heading">Micro Blog</div>

            <div class="clearfix"></div>
            <div class="mb_tag">This is a project which is a micro blog that was made using different frontend and backend technolgies. Each of the follwing set uses one different frontend tech like jquery, angular etc; and backend like slim, laravel etc.</div>

            <div class="clearfix"></div>
            <div class="mb_sets">

                <!-- set 1 -->
                <div class="mb_card pull-left">
                    <a href="http://www.kazmik.in/mb/jq/">
                        <div class="card_head">SET I</div>
                        <div class="clearfix"></div>
                        <div class="card_tag">Frontend : Jquery<br/>Backend : Slim</div>
                </div>

                <!-- set 2 -->
                <div class="mb_card pull-left">
                    <a href="http://www.kazmik.in/mb/ng/">
                        <div class="card_head">SET II</div>
                        <div class="clearfix"></div>
                        <div class="card_tag">Frontend : Angular<br/>Backend : Laravel</div>
                </div>
            </div>

        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>
