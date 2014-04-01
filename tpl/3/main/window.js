function resize_air(animat) {
    if (animat) {
        $('.head-box').transition({scale: 0.6, x: -300}, 500, 'snap');//.transition({x: -300}, 500, 'snap');
        $('.head').animate({height: "140px"});
    } else {
        $('.head-box').transition({scale: 0.6, x: -300}, 0);//.transition({x: -300}, 500, 'snap');
        $('.head').css('height', "140px");
    }
}
function resize_hd(animat) {
    if (animat) {
        $('.head-box').transition({scale: 1, x: 0}, 500, 'snap');//.transition({x: -300}, 500, 'snap');
        $('.head').animate({height: "220px"});
    } else {
        $('.head-box').transition({scale: 1, x: 0}, 0);//.transition({x: -300}, 500, 'snap');
        $('.head').css('height', "220px");
    }
}
function resize(animat) {
    //获取高度
    var wh = $(window).height();
    //开始执行调整策略
    if (wh <= 900) {
        //如果高端小于900,执行调整.
        resize_air(animat);
    }
    if (wh > 900) {
        resize_hd(animat);
    }
}
var panel_status = true;
function PanelToggle() {
    if (panel_status) {
        //下拉控制面板
        $('.server-bgx').transition({y: 0}, 500, 'snap', function() {
            $('.server-bgx').css('z-index', 999);
        });
        //设置触发层
        $('body').prepend('<div class="server-bgx-click" onclick="PanelToggle();"></div>');
        panel_status = false;
    } else {
        //上拉
        $('.server-bgx').css('z-index', 50);
        $('.server-bgx').transition({y: -329}, 500, 'snap');
        //移除触发层
        $('.server-bgx-click').remove();
        panel_status = true;
    }
}
function ModelToggle(e, data) {
    var value = null;
    var text = null;
    if (data.value === true) {
        value = true;
        text = '日志模式启用';
        $('#nti-1').css('color','#FD7C00');
        $('#nti-2').css('color','#BEBEBE');
    } else {
        $('#nti-2').css('color','#FD7C00');
        $('#nti-1').css('color','#BEBEBE');
        value = false;
        text = '实时模式启用';
    }
    Chatx.setModel(value ? 0 : 1);
    Chatx.draw();
    console.log(text);
}
var panel_active = '';
function AtiveTogle(target) {
    if (panel_active === '') {
        $(panel_active).css('background-color', '#eaeaea');
    }
    if (panel_active !== target) {
        $(panel_active).css('background-color', '');
        panel_active = target;
        $(panel_active).css('background-color', '#eaeaea');
    }
}
function refresh_hour() {
    var d_1 = new Date();
    var d_2 = new Date((Math.round(d_1.getTime() / 1000) - 3600) * 1000);
    var now = (d_1.getFullYear()) + "-" + (d_1.getMonth() + 1) + "-" + (d_1.getDate()) + " " + (d_1.getHours()) + ":" + (d_1.getMinutes());
    var before = (d_2.getFullYear()) + "-" + (d_2.getMonth() + 1) + "-" + (d_2.getDate()) + " " + (d_2.getHours()) + ":" + (d_2.getMinutes());
    $('#datetimepicker1').val(before);
    $('#datetimepicker2').val(now);
}
function refresh_day() {
    var d_1 = new Date();
    var d_2 = new Date((Math.round(d_1.getTime() / 1000) - 86400) * 1000);
    var now = (d_1.getFullYear()) + "-" + (d_1.getMonth() + 1) + "-" + (d_1.getDate()) + " " + (d_1.getHours()) + ":" + (d_1.getMinutes());
    var before = (d_2.getFullYear()) + "-" + (d_2.getMonth() + 1) + "-" + (d_2.getDate()) + " " + (d_2.getHours()) + ":" + (d_2.getMinutes());
    $('#datetimepicker1').val(before);
    $('#datetimepicker2').val(now);
}
function refresh_week() {
    var d_1 = new Date();
    var d_2 = new Date((Math.round(d_1.getTime() / 1000) - 604800) * 1000);
    var now = (d_1.getFullYear()) + "-" + (d_1.getMonth() + 1) + "-" + (d_1.getDate()) + " " + (d_1.getHours()) + ":" + (d_1.getMinutes());
    var before = (d_2.getFullYear()) + "-" + (d_2.getMonth() + 1) + "-" + (d_2.getDate()) + " " + (d_2.getHours()) + ":" + (d_2.getMinutes());
    $('#datetimepicker1').val(before);
    $('#datetimepicker2').val(now);
}
/*
 四舍五入保留小数位数
 numberRound 被处理的数
 roundDigit  保留几位小数位
 */
function  roundFun(numberRound, roundDigit)   //处理金额 -by hailang  
{
    if (numberRound >= 0) {
        var tempNumber = parseInt((numberRound * Math.pow(10, roundDigit) + 0.5)) / Math.pow(10, roundDigit);
        return   tempNumber;
    } else {
        numberRound1 = -numberRound
        var tempNumber = parseInt((numberRound1 * Math.pow(10, roundDigit) + 0.5)) / Math.pow(10, roundDigit);
        return   -tempNumber;
    }
}

/*附件大小格式处理*/
function renderSize(value) {
    if (null == value || value == '') {
        return "0 Bytes";
    }
    var unitArr = new Array("Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
    var index = 0;


    var srcsize = parseFloat(value);
    var size = roundFun(srcsize / Math.pow(1024, (index = Math.floor(Math.log(srcsize) / Math.log(1024)))), 2);
    return size + unitArr[index];
}
window.onresize = function() {
    resize(true);
};
window.onbeforeunload = function(event) {
    $('#wating-page').show().transition({opacity: 1}, 1000);
};
$('body').ready(function() {
    //获取高度
    var wh = $(window).height();
    //淡出载入界面
    setTimeout(function() {
        resize(false);
        $('#wating-page').transition({opacity: 0}, 1000).delay(500).queue(function() {
            $('#wating-page').hide();
        }).dequeue();
    }, 1000);
    //提前将位置导入
    $('.content-date').transition({y: 10}, 0);
    $('.server-bgx').transition({y: -329}, 0);
    //绑定头部导航
    $('#nti-1').css('color','#FD7C00');
    $('#nti-1').css('cursor','pointer');
    $('#nti-2').css('cursor','pointer');
    $('#nti-1').on('click',function(){ModelToggle(null,{value:true});$('#modelc').bootstrapSwitch('setState', true); });
    $('#nti-2').on('click',function(){ModelToggle(null,{value:false});$('#modelc').bootstrapSwitch('setState', false); });
    //绑定键盘事件
    $(document).keyup(function(e) {
        console.log('快捷键触发.');
        switch (e.which) {
            case 72:
                console.log('开关面板.');
                PanelToggle();
                break;
            case 82:
                console.log('刷新最近一小时数据.');
                refresh_hour();
                Chatx.draw();
                break;
            case 68:
                console.log('刷新最近一天数据.');
                refresh_day();
                Chatx.draw();
                break;
            case 87:
                console.log('刷新最近一周据.');
                refresh_week();
                Chatx.draw();
                break;
        }
    });
});