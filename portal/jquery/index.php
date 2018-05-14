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
        <link rel="stylesheet" href="<?php echo $g_url;?>static/css/common.css">
    </head>
    <body>

        <?php top_banner(); ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="<?php echo $g_url;?>static/js/common.js"></script>
    </body>
</html>
