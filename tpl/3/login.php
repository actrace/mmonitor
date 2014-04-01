<!DOCTYPE html>
<html>
    <head>
        <title>mMonitor - login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?php echo $_TPL; ?>main/window.js"></script>
        <script>
        </script>
    </head>
    <body class="bg">
        <!--[预加载部分]-->
        <?php $this->load('inc.load.php'); ?>
        <div id="wrap">
            <!--[页脚]-->
            <?php $this->load('inc.head.php'); ?>
            <!--[登陆页]-->
            <form action="login.php?action=login" method="post" autocomplete="off">
                <div class="login-box">
                    <div class="login-box-left">
                        <div class="login-box-box-a">Username</div>
                        <div class="login-box-box-b">Password</div>
                    </div>
                    <div class="login-box-right">
                        <div class="login-box-box-a">
                            <input class="login-box-input bg" id="lg-username" name="lg-username" autocomplete="off" type="text">
                        </div>
                        <div class="login-box-box-b">
                            <input class="login-box-input bg" id="lg-passcode" name="lg-passcode" autocomplete="off" type="password">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="login-box-bottom">
                        <input class="sprite-loginbtn" value="" type="submit">
                    </div>
                </div>
            </form>
            <div id="push"></div>
        </div>
        <!--[页脚]-->
        <?php $this->load('inc.foot.php'); ?>
    </body>
</html>
