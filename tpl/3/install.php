<!DOCTYPE html>
<html>
    <head>
        <title>mMonitor - install</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?php echo $_TPL; ?>main/window.js"></script>
        <script>
            function progress(type) {
                switch (type) {
                    case 0:
                        $('.install-text').html('<?php echo $_TPL_PR; ?>');
                        $('.install-pg-bar').animate({width: "100%"}, 3200, function() {
                            $('.install-text2').html('');
                            $('.install-finish-btn').css('color','#00601B');
                            $('.install-finish-btn').css('border','1px #00601B solid');
                            $('.install-finish-btn').css('cursor','pointer');
                            $('.install-finish-btn').attr('onclick',"window.location='?install';");
                        });
                        break;
                    case 1:
                        $('.install-text').html('<?php echo $_TPL_PR_1; ?>');
                        $('.install-pg-bar').animate({width: "20%"}, 800, function() {
                            $('.install-text2').html('<?php echo $_TPL_PRM_1; ?>');
                        });
                        break;
                    case 2:
                        $('.install-text').html('<?php echo $_TPL_PR_2; ?>');
                        $('.install-pg-bar').animate({width: "50%"},   1600, function() {
                            $('.install-text2').html('<?php echo $_TPL_PRM_2; ?>');
                        });
                        break;
                    case 3:
                        $('.install-text').html('<?php echo $_TPL_PR_3; ?>');
                        $('.install-pg-bar').animate({width: "80%"}, 2400, function() {
                            $('.install-text2').html('<?php echo $_TPL_PRM_3; ?>');
                        });
                        break;
                }
            }
            $('body').ready(function() {
                $('.sprite-line-colord').transition({opacity: 0}, 0);
                $('.head').transition({y: -999}, 0);
                $('#footer').transition({y: 100}, 0);
                $('#content').transition({opacity: 0}, 0);
                setTimeout(function() {
                    $('#footer').transition({y: 0}, 800, 'snap');
                    $('.head').transition({y: 0}, 800, 'snap', function() {
                        $('#content').transition({opacity: 1}, 800);
                        $('.sprite-line-colord').transition({opacity: 1}, 800);
                        progress(<?php echo $_TPL_CHECK;?>);
                    });
                }, 1500);
            });
        </script>
    </head>
    <body class="bg">
        <!--[预加载部分]-->
        <?php $this->load('inc.load.php'); ?>
        <div id="wrap">
            <!--[页头]-->
            <?php $this->load('inc.head.php'); ?>
            <!--[内容页]-->
            <div id="content">
                <div class="install-box">
                    <div class="install-pg-box">
                        <div class="install-text color-font">wait....</div>
                        <div class="install-pg sprite-progress-box">
                            <div class="install-pg-bar"></div>
                        </div>
                        <div class="install-text2"></div>
                    </div>
                    <div class="sprite-line-dashed" style="margin-top: 170px;width: 2000px;"></div>
                    <div class="sprite-isnotice" style="margin-top: 15px;"></div>
                    <div class="install-notice-text" style="margin-top: 15px;">
                        <p>1.<?php echo $_TPL_INS_T1; ?></p>
                        <p>2.<?php echo $_TPL_INS_T2; ?></p>
                    </div>
                </div>
                <div class="install-finish-btn" onclick="alert('not ready');">
                    <p><?php echo $_TPL_INS_BTN; ?></p>
                </div>
            </div>
            <div id="push" style="margin-top: 160px;"></div>
        </div>
        <!--[页脚]-->
        <?php $this->load('inc.foot.php'); ?>
    </body>
</html>
