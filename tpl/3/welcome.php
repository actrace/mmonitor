<!DOCTYPE html>
<html>
    <head>
        <title>mMonitor - welcome</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?php echo $_TPL; ?>main/window.js"></script>
        <script>
            $('body').ready(function(){
                $('#welcome-w-line2').transition({scale: 0.6,opacity: 0}, 0);
                setTimeout(function(){
                    $('#welcome-w-line2').transition({scale: 1,opacity: 1}, 500,'snap');
                },1500);
                setInterval(function(){
                    $('#welcome-w-line3').transition({opacity: 0}, 1000,'linear').transition({opacity: 1}, 1000,'linear');;
                },3000);
            });
            function cool_push(){
                $('#welcome-w-line2').transition({scale: 1.2,opacity: 0}, 1000,'snap');
                setTimeout(function(){window.location='install.php';},1000);
            }
        </script>
    </head>
    <body class="bg">
        <!--[预加载部分]-->
        <?php $this->load('inc.load.php'); ?>
        <div id="wrap">
            <div class="welcome-box">
                <!--[第一行]-->
                <div id="welcome-w-line1"><?php echo $_TPL_CL_SLF; ?></div>
                <!--[第三行]-->
                <div id="welcome-w-line2" class="sprite-logo-big" onclick="cool_push();"></div>
                <!--[第四行]-->
                <div id="welcome-w-line3"><?php echo $_TPL_CL_CSL; ?></div>
            </div>
            <div id="push"></div>
        </div>
    </body>
</html>
