<!DOCTYPE html>
<html>
    <head>
        <title>mMonitor - index</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?php echo $_TPL; ?>main/window.js"></script>
        <script>
            $('body').ready(function() {
                $('.switch').bootstrapSwitch();
                //时间选择器
                $('#datetimepicker1').datetimepicker({
                    language: 'zh-CN',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    minuteStep: 60,
                    showMeridian: 1
                });
                $('#datetimepicker2').datetimepicker({
                    language: 'zh-CN',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    minuteStep: 60,
                    showMeridian: 1
                });
                //初始化Highcharts.
                Highcharts.setOptions({
                    global: {
                        useUTC: false
                    }
                });
                //绘制默认第一个图
                refresh_day();
                Chatx.setTarget('<?php echo $SERVER[0]['dir'] ?>').setLabel('<?php echo $SERVER[0]['name'] ?>').draw();
                //初始化状态控制按钮,设定状态控制.
                $('#modelc').on('switch-change', function(e, data) {
                    ModelToggle(e,data);
                });
            });
        </script>
    </head>
    <body class="bg">
        <!--[预加载部分]-->
        <?php $this->load('inc.load.php'); ?>
        <div id="wrap">
            <!--[页头]-->
            <?php $this->load('inc.head.php'); ?>
            <!--[大盒子]-->
            <div class="main">
                <!--[主体内容]-->
                <div class="content">
                    <!--[日期选择器]-->
                    <div class="content-date">
                        <?php echo $_TPL_TZ_1; ?>
                        <input type="text" size="16" value="" id="datetimepicker1" class="datepicker-tool">
                        <?php echo $_TPL_TZ_2; ?>
                        <input type="text" size="16" value="" id="datetimepicker2" class="datepicker-tool">
                        <a href="#" onclick="Chatx.draw();" id="redraw">Redraw</a>
                    </div>
                    <!--[图表区]-->
                    <div class="draw" id="let-us-go">
                    </div>
                </div>
            </div>
            <div id="push"></div>
        </div>
        <!--[页脚]-->
        <?php $this->load('inc.foot.php'); ?>
    </body>
</html>
