/**
 * 
 * 绘制界面所需函数,一次性打包.
 * 
 * @copyright (c) 2013, mMonitor.org
 * @version 1.0
 * @author Actrace
 * @date 2013-11-17 11:38:20
 */

var Chatx = {
    api_url: 'charts_api.php',
    target: '',
    label: 'NO..SERVER.',
    drawid: '#draw',
    model: 0, //0是日志模式,1是实时模式.
    data: null,
    keep: 0, //在下一次请求时保持状态标记.
    time_start: 0,
    time_end: 0,
    relay_time: 8,
    realtime_runing: false,
    realtime_id: null,
    ajax_runing: false,
    id_time_start: '#datetimepicker1',
    id_time_end: '#datetimepicker2',
    opt: {
        colors: ['#FD7C00', '#00601B', '#6F156C'],
        chart: {
            spacingTop: 30,
            spacingRight: 10,
            spacingBottom: 15,
            spacingLeft: 10,
            shadow: false,
            animation:true
        },
        title: {
            text: null
        },
        tooltip: {
            shared: true,
            xDateFormat: "%Y-%m-%d %H:%M:%S",
        },
        xAxis: {
            type: 'datetime',
            tickmarkPlacement: 'on',
            title: {
                text: null
            }
        },
        yAxis: {
            title: {
                text: null
            },
            min: 0
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                lineWidth: 1.5,
                animation: true,
                allowPointSelect: false,
                fillOpacity: 2,
                marker: {
                    enabled: false,
                    states: {
                        hover: {
                            enabled: true,
                            radius: 4
                        }
                    }
                }
            }
        }
    },
    create: function() {
        var tpl = '<div id="draw-cpu"></div><div id="draw-mem"></div><div id="draw-load"></div><div id="draw-network"></div><div id="draw-disk"></div>';
        $('#let-us-go').html('').html(tpl);
        return this;
    },
    draw: function() {
        AtiveTogle('#active-' + Chatx.target);
        $('.server-on').html(Chatx.label + '...loading.');
        if (this.model === 0) {
            console.log('日志模式运行.');
            //显示日期选择器
            $('.content-date').transition({y: 10}, 500, 'snap');
            //修改界面提示
            $('#redraw').html('Loading...');
            //创建界面
            this.create();
            //获取时间
            this.setTime($(this.id_time_start).val(), $(this.id_time_end).val());
            //日志模式
            this.getData(function(data) {
                Chatx.data = data;
                Chatx.drawCpu(false);
                Chatx.drawNet(false);
                Chatx.drawLoad(false);
                Chatx.drawDisk(false);
                Chatx.drawMemory(false);
                $('#redraw').html('Redraw');
                $('.server-on').html(Chatx.label);
            });

        } else {
            //隐藏日期选择器
            $('.content-date').transition({y: -26}, 500, 'snap');
            console.log('实时模式运行.');
            //第一次运行创建界面
            if (this.realtime_runing === false) {
                console.log('实时模式运行::创建界面.');
                //设置定时器循环运行.
                Chatx.realtime_id = setInterval("Chatx.draw()", Chatx.relay_time * 1000);
                this.create();
                this.keep = 0;
                this.realtime_runing = true;
                this.getData(function(data) {
                    Chatx.data = data;
                    Chatx.drawCpu(false);
                    Chatx.drawNet(false);
                    Chatx.drawLoad(false);
                    Chatx.drawDisk(false);
                    Chatx.drawMemory(false);
                    $('.server-on').html(Chatx.label);
                });
            } else {
                //实时模式
                this.getData(function(data) {
                    Chatx.data = data;
                    Chatx.drawCpu(true);
                    Chatx.drawNet(true);
                    Chatx.drawLoad(true);
                    Chatx.drawDisk(true);
                    Chatx.drawMemory(true);
                    $('.server-on').html(Chatx.label);
                });
            }

        }
        return this;
    },
    setTarget: function(target) {
        Chatx.target = target;
        //检查realtime模式是否设置了定时任务并清除掉
        if (Chatx.realtime_runing) {
            console.log('Target改变,清除定时任务.');
            clearInterval(this.realtime_id);
            Chatx.keep = 0;
            Chatx.realtime_runing = false;
        }
        return this;
    },
    setLabel: function(name) {
        Chatx.label = name;
        console.log(name);
        return this;
    },
    setTime: function(start, end) {
        Chatx.time_start = start;
        Chatx.time_end = end;
        return this;
    },
    setModel: function(type) {
        //检查realtime模式是否设置了定时任务并清除掉
        if (Chatx.realtime_runing) {
            console.log('Model改变,清除定时任务.');
            clearInterval(this.realtime_id);
            Chatx.keep = 0;
            Chatx.realtime_runing = false;
        }
        Chatx.model = type;
        return this;
    },
    getData: function(call) {
        var postg = {
            time_start: this.time_start,
            time_end: this.time_end,
            host_name: this.target,
            realtime: this.model,
            realkeep: this.keep,
            relay_time: this.relay_time
        };
        //如果已经在运行了,就中断.
        if (Chatx.ajax_runing !== false) {
            console.log('强制中断未完成请求.');
            Chatx.ajax_runing.abort();
        }
        Chatx.ajax_runing = $.post(this.api_url, postg, function(json) {
            if (json.status) {
                console.log('通信完成.');
                //数据抓取成功
                call(json.data);
                Chatx.ajax_runing = false;
            }
        }, 'json');
        //如果设置了实时请求模式,那么在下一次请求将会发送keep标记.
        if (Chatx.realtime_runing) {
            Chatx.keep = 1;
        }
        return this;
    },
    drawCpu: function(update) {
        if (update) {
            var chartx = $(this.drawid + '-cpu').highcharts();
            chartx.series[0].update({name: 'User', data: Chatx.data.cpu.usr});
            chartx.series[1].update({name: 'System', data: Chatx.data.cpu.sys});
            chartx.series[2].update({name: 'I/O Wait', data: Chatx.data.cpu.wa});
            return;
        }
        var opt = this.opt;
        opt.chart.type = 'spline';
        opt.tooltip.valueSuffix = '%';
        opt.title.text = 'CPU';
        opt.plotOptions.spline = {stacking: 'normal'};
        opt.yAxis.labels = {formatter: function() {
                return this.value + ' %';
            }};
        opt.series = [
            {name: 'User', data: Chatx.data.cpu.usr},
            {name: 'System', data: Chatx.data.cpu.sys},
            {name: 'I/O Wait', data: Chatx.data.cpu.wa}
        ];
        $(this.drawid + '-cpu').highcharts(opt);
    },
    drawNet: function(update) {
        var data = Chatx.data.network;
        for (i in data) {
            //插入需要的元素
            var div_id = 'draw-network-' + data[i].id;
            if (update) {
                var chartx = $('#' + div_id).highcharts();
                chartx.series[0].update({name: 'Uplink', data: data[i].tx});
                chartx.series[1].update({name: 'Downlink', data: data[i].rx});
            } else {
                if ($('#' + div_id).length <= 0) {
                    //如果对象不存在,创建,实时模式时,不会重复创建对象.
                    $(this.drawid + '-network').append('<div class="status-items-box" id="' + div_id + '"></div>');
                }
                var opt = this.opt;
                opt.chart.type = 'spline';
                opt.tooltip.valueSuffix = ' KB/s';
                opt.title.text = 'Network-' + data[i].ipv4;
                opt.yAxis.labels = {formatter: function() {
                        return renderSize(this.value * 1024) + '/s';
                    }};
                opt.plotOptions.spline = {};
                opt.series = [
                    {name: 'Uplink', data: data[i].tx},
                    {name: 'Downlink', data: data[i].rx}
                ];
                $('#' + div_id).highcharts(opt);
            }

        }
    },
    drawLoad: function(update) {
        if (update) {
            var chartx = $(this.drawid + '-load').highcharts();
            chartx.series[0].update({name: '1 Min', data: Chatx.data.load[1]});
            chartx.series[1].update({name: '5 Min', data: Chatx.data.load[2]});
            chartx.series[2].update({name: '15 Min', data: Chatx.data.load[3]});
            return;
        }
        var opt = this.opt;
        opt.chart.type = 'spline';
        opt.tooltip.valueSuffix = '';
        opt.title.text = 'Load';
        opt.yAxis.labels = {formatter: function() {
                return this.value;
            }};
        opt.plotOptions.spline = {};
        opt.series = [
            {name: '1 Min', data: Chatx.data.load[1]},
            {name: '5 Min', data: Chatx.data.load[2]},
            {name: '15 Min', data: Chatx.data.load[3]}
        ];
        $(this.drawid + '-load').highcharts(opt);
    },
    drawDisk: function(update) {
        var data = Chatx.data.disk;
        for (i in data) {
            //插入需要的元素
            var div_id = 'draw-disk-' + data[i].onid;
            if (update) {
                var chartx = $('#' + div_id).highcharts();
                chartx.series[1].update({name: 'Avail', data: data[i].avail});
                chartx.series[0].update({name: 'Used', data: data[i].Used});
            } else {
                if ($('#' + div_id).length <= 0) {
                    //如果对象不存在,创建,实时模式时,不会重复创建对象.
                    $(this.drawid + '-disk').append('<div class="status-items-box" id="' + div_id + '"></div>');
                }
                var opt = Chatx.opt;
                opt.chart.type = 'spline';
                opt.tooltip.valueSuffix = ' GB';
                opt.yAxis.labels = {formatter: function() {
                        return this.value + ' %';
                    }};
                opt.title.text = 'Disk-' + data[i].onid + ':' + data[i].on;
                opt.plotOptions.spline = {stacking: 'percent'};
                opt.series = [
                    {name: 'Avail', data: data[i].avail},
                    {name: 'Used', data: data[i].used},
                ];
                $('#' + div_id).highcharts(opt);
            }

        }
    },
    drawMemory: function(update) {
        if (update) {
            var chartx = $(this.drawid + '-mem').highcharts();
            chartx.series[0].update({name: 'Used', data: Chatx.data.mem.used});
            chartx.series[1].update({name: 'Cache', data: Chatx.data.mem.cached});
            chartx.series[2].update({name: 'Buffers', data: Chatx.data.mem.buffers});
            chartx.series[3].update({name: 'Free', data: Chatx.data.mem.free});
            return;
        }
        var opt = Chatx.opt;
        opt.chart.type = 'spline';
        opt.tooltip.valueSuffix = ' MB';
        opt.yAxis.labels = {formatter: function() {
                return this.value + ' %';
            }};
        opt.title.text = 'Memory';
        opt.plotOptions.spline = {stacking: 'percent'};
        opt.series = [
            {name: 'Used', data: Chatx.data.mem.used},
            {name: 'Cache', data: Chatx.data.mem.cached},
            {name: 'Buffers', data: Chatx.data.mem.buffers},
            {name: 'Free', data: Chatx.data.mem.free}
        ];
        $(this.drawid + '-mem').highcharts(opt);
    }
};