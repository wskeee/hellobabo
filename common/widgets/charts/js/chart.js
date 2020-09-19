"use strict";
/**
 * 柱状图 横向显示
 * @param {type} win
 * @param {type} $
 * @returns {undefined}
 */
(function (win, $) {

    win.wskcharts = win.wskcharts || {};
    /**
     * 曲线图
     * @param {Object} config   配置
     * @param {Dom} dom         chart的容器
     * @param {Array} datas     [[name:string,value:[日期,value]],[],...]
     * @returns
     */
    var LineStackChart = function (config, dom, datas) {
        this.config = $.extend({
            title: '', //大标题
            'series.name':'',//值显示
            "axisLabel.formatter":"{value}",//y轴显示格式
        }, config);
        this.init(dom, datas);
        this.reflashChart(this.config.title, datas);
        var _this = this;
        $(win).resize(function () {
            _this.chart.resize();
        });
    }
    var p = LineStackChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;

    p.init = function (dom, datas) {
        this.canvas = dom;
        //重新计算图标的高度，高度由显示的数据相关

        this.chart = echarts.init(dom);
        this.chartOptions = {
            tooltip: {
                show: true,
                trigger: 'axis',
                axisPointer: {
                    animation: false
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                splitLine: {
                    show: false
                }
            },
            yAxis: {
                type: 'value',
                boundaryGap: [0, '100%'],
                splitLine: {
                    show: true
                },
                axisLabel: {
                    formatter: this.config['axisLabel.formatter']
                }
            },
            series: [{
                    name: this.config['series.name'],
                    type: 'line',
                    showSymbol: true,
                    symbolSize: 6,
                    hoverAnimation: false,
                    data: datas
                }]
        };

    };

    /**
     * 刷新图标
     * @param {Array} data 出错步骤数据
     * @returns 
     */
    p.reflashChart = function (data) {

        var keys = [];
        var values = [];

        for (var i = 0, len = data.length; i < len; i++)
        {
            keys.push(data[i]["name"]);
            values.push(data[i]["value"]);
        }

        this.chartOptions.yAxis.data = keys;
        this.chartOptions.series[0].data = values;
        this.chart.setOption(this.chartOptions, true);
    }

    /**
     * 刷新为多组数据图标
     *
     * @param {Array} datas
     */
    p.reflashByMultiple = function (datas){
        var keys = datas.keys;
        var values = [];
        for(var i=0,len = datas.data.length;i<len;i++){
            values.push({
                name : datas.data[i].goods_name,
                type : 'line',
                showSymbol: true,
                symbolSize: 6,
                hoverAnimation: false,
                data : datas.data[i].value
            });
        }
        this.chartOptions.yAxis.data = keys;
        this.chartOptions.series = values;
        this.chart.setOption(this.chartOptions, true);
    };

    win.wskcharts.LineStackChart = LineStackChart;
})(window, jQuery);

/**
 * 柱状图
 */
(function(win,$){

    win.wskcharts = win.wskcharts || {};
    /**
     * 创建表
     * @param {Object} config   配置
     * @param {Dom} dom         chart的容器
     * @param {Array} datas     [[name:string,value:number],[],...]
     * @returns
     */
    var BarChart = function(config,dom,datas){
        this.config = $.extend({
            title: '标题',                      //大标题
            subTtile: '',                       //副标题
            itemLabelFormatter: '{c}',           //bar 提示格式
        },config);
        this.init(dom,datas);
        this.reflashChart(this.config.title,datas);
        var _this = this;
        $(win).resize(function(){
            _this.chart.resize();
        });
    }
    var p = BarChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;

    p.init = function(dom,datas){
        this.canvas = dom;
        //重新计算图标的高度，高度由显示的数据相关
        //var len=0;
        //for(var i in datas)len++;
        //$(this.canvas).css('height',(len*(30+10)-10+100)+"px");

        this.chart = echarts.init(dom);
        this.chartOptions = {
            title: {
                text: '世界人口总量',
                subtext: '数据来自课程中心平台'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: []
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: ['巴西','印尼','美国','印度','中国','世界人口(万)']
            },
            dataZoom: [
                {
                    type: 'slider',
                    xAxisIndex: 0,
                    start: 10,
                    end: 60
                },
                {
                    type: 'inside',
                    xAxisIndex: 0,
                    start: 10,
                    end: 60
                }
            ],
            series: [
                {
                    name: '',
                    type: 'scatter',
                    label: {
                        normal: {
                            show: true,
                            position:"insideRight",
                            formatter:this.config.itemLabelFormatter
                        }
                    },
                    data: [18203, 23489, 29034, 104970, 131744, 630230]
                }
            ]
        };

    };

    /**
     * 刷新图标
     * @param {Array} data 出错步骤数据
     * @returns
     */
    p.reflashChart = function(data){

        var keys = [];
        var values = [];

        for(var i=0,len=data.length;i<len;i++)
        {
            keys.push(data[i]["name"]);
            values.push(data[i]["value"]);
        }


        this.chartOptions.yAxis.data = keys;
        this.chartOptions.series[0].data = values;
        this.chart.setOption(this.chartOptions,true);
    }

    win.wskcharts.BarChart = BarChart;
})(window,jQuery);

/**
 * 可拖拽柱状图
 */
(function(win,$){

    win.wskcharts = win.wskcharts || {};
    /**
     * 创建表
     * @param {Object} config   配置
     * @param {Dom} dom         chart的容器
     * @param {Array} datas     [[name:string,value:number],[],...]
     * @returns
     */
    var DragBarChart = function(config,dom,datas){
        this.config = config;
        this.init(dom,datas);
        this.reflashChart(datas);
        var _this = this;
        $(win).resize(function(){
            _this.chart.resize();
        });
    }
    var p = DragBarChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;

    p.init = function(dom,datas){
        this.canvas = dom;
        //重新计算图标的高度，高度由显示的数据相关

        this.chart = echarts.init(dom);
        this.chartOptions = $.extend({
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow',
                    label: {
                        show: true
                    }
                }
            },
            toolbox: {
                show: false,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar']},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            legend: {
                show: false,
                data: ['收益'],
                itemGap: 5
            },
            grid: {
                top: '12%',
                left: '1%',
                right: '10%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: []
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: '元',
                    axisLabel: {
                        formatter: function (a) {
                            a = +a;
                            return isFinite(a)
                                ? echarts.format.addCommas(+a)
                                : '';
                        }
                    }
                }
            ],
            dataZoom: [
                {
                    show: false,
                    start: 0,
                    end: 100
                },
                {
                    type: 'inside',
                    start: 94,
                    end: 100
                },
                {
                    show: false,
                    yAxisIndex: 0,
                    filterMode: 'empty',
                    width: 30,
                    height: '90%',
                    showDataShadow: false,
                    left: '93%'
                }
            ],
            series: [
                {
                    name: '收益',
                    type: 'bar',
                    data: []
                }
            ],
            color: ['#63daab','#2f4554', '#61a0a8', '#d48265', '#91c7ae','#749f83',  '#ca8622', '#bda29a','#6e7074', '#546570', '#c4ccd3']
        },this.config);

    };

    /**
     * 刷新图标
     * @param {Array} data 出错步骤数据
     * @returns
     */
    p.reflashChart = function(data){

        var keys = [];
        var values = [];

        for(var i=0,len=data.length;i<len;i++)
        {
            keys.push(data[i]["name"]);
            values.push(data[i]["value"]);
        }
        this.chartOptions.xAxis[0].data = keys;
        this.chartOptions.series[0].data = values;
        this.chart.setOption(this.chartOptions,true);
    }

    win.wskcharts.DragBarChart = DragBarChart;
})(window,jQuery);