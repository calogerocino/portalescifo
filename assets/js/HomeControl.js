function HomeVar() {
    $.post(currentURL + 'assets/inc/home-control.php', { azione: 'caricahome' }, function (r) {
        (ModalitaDebug ? console.log(r) : '')

        var rs = r.split('|-|');

        $('.a1').html(rs[0]); // Ordini di oggi
        $('.a1').attr('data-countup', '{"endValue":' + rs[0] + '}');
        $('.a2').html('€ ' + rs[1]); // Vendite totali (oggi)
        $('.a3').html(rs[2] + ' prodotti'); // PRODOTTI FUORI STOCK
        $('.a3').attr('data-countup', '{"endValue":' + rs[2] + '}');
        $('.a4').html(rs[3] + ' ordini'); // ORDINI IMPORTATI
        $('.a4').attr('data-countup', '{"endValue":' + rs[3] + '}');
        $('.a5').html(rs[4] + '+ ordini'); // ORDINI DA EVADERE
        $('.a5').attr('data-countup', '{"endValue":' + rs[4] + '}');
        $('.a6').html('€ ' + rs[5]); // Ultimi 7 giorni
        docReady(VenditeSettimanaliInit(rs[6]));
        $('.a7').html(rs[7] + '%'); // Prodotti publicati
        docReady(ProdottiPublicatiInit(rs[7] + '-' + (100 - rs[7])))
        $('.a8').html(rs[9]); // Marketplace
        $('.a8').attr('data-countup', '{"endValue":' + rs[9] + '}');
        docReady(MarketplaceVenditeInit(rs[8]));
        $('.a9').html('€ ' + rs[10]); // Ultimi 4 mesi
        docReady(OrdiniTotaliInit(rs[11]));
        $('.a10').html(rs[12]); // Oggetti venduti (mese)
        $('.a10').attr('data-countup', '{"endValue":' + rs[12] + '}');
        $('.a11').html(rs[13]); // Oggetti venduti (mese -1)
        $('.a11').attr('data-countup', '{"endValue":' + rs[13] + '}');
        $('.a12').html(rs[14]); // Ordini in ritardo
        $('.a12').attr('data-countup', '{"endValue":' + rs[14] + '}');
        $('.a13').html('€ ' + rs[15]); // Rimborsi (mese - 0)
        $('.a14').html('€ ' + rs[16]); // Rimborsi (mese - 1)
        $('.a15').html('€ ' + rs[17]); // Spese spedizione
        $('.a16').html(rs[18]); // Pacchi spediti -> Spese spedizione
        $('.a17').html('€ ' + rs[19]); // Costi spedizione
        docReady(VenditeEcomAnno1eM1(rs[20], rs[21]))
        docReady(countupInit);
    })





    $('.b1').attr('id', 'PoPM-');
    $('.b1').attr('tt', 'Funzione momentaneamente non disponibile!');
    $('.b2').attr('id', 'PoPM-');
    $('.b2').attr('tt', 'Funzione momentaneamente non disponibile!');
    $('.b3').attr('id', 'PoPM-');
    $('.b3').attr('tt', 'Funzione momentaneamente non disponibile!');
}


var VenditeSettimanaliInit = function VenditeSettimanaliInit(datas) {
    var ECHART_BAR_WEEKLY_SALES = '.echart-bar-weekly-sales';
    var $echartBarWeeklySales = document.querySelector(ECHART_BAR_WEEKLY_SALES);
    if ($echartBarWeeklySales) {
        var userOptions = utils.getData($echartBarWeeklySales, 'options');

        var data = datas.split(','); // Max value of data

        var yMax = Math.max.apply(Math, data);
        var dataBackground = data.map(function () {
            return yMax;
        });

        var chart = window.echarts.init($echartBarWeeklySales);
        var getDefaultOptions = function getDefaultOptions() {
            return {
                tooltip: {
                    trigger: 'axis',
                    padding: [7, 10],
                    formatter: '{b0} : {c0}',
                    transitionDuration: 0,
                    backgroundColor: utils.getGrays()['100'],
                    borderColor: utils.getGrays()['300'],
                    textStyle: {
                        color: utils.getColors().dark
                    },
                    borderWidth: 1,
                    position: function position(pos, params, dom, rect, size) {
                        return getPosition(pos, params, dom, rect, size);
                    }
                },
                xAxis: {
                    type: 'category',
                    data: ['Oggi', 'Ieri', '2 giorni fa', '3 giorni fa', '4 giorni fa', '5 giorni fa', '6 giorni fa'],
                    boundaryGap: false,
                    axisLine: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisPointer: {
                        type: 'none'
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisPointer: {
                        type: 'none'
                    }
                },
                series: [{
                    type: 'bar',
                    showBackground: true,
                    backgroundStyle: {
                        borderRadius: 10
                    },
                    barWidth: '5px',
                    itemStyle: {
                        barBorderRadius: 10,
                        color: utils.getColors().primary
                    },
                    data: data,
                    z: 10,
                    emphasis: {
                        itemStyle: {
                            color: utils.getColors().primary
                        }
                    }
                }],
                grid: {
                    right: 5,
                    left: 10,
                    top: 0,
                    bottom: 0
                }
            };
        };
        echartSetOption(chart, userOptions, getDefaultOptions);
    }
};

var ProdottiPublicatiInit = function ProdottiPublicatiInit(datas) {
    var r = datas.split('-')
    var ECHART_PRODUCT_SHARE = '.echart-bandwidth-saved';
    var $echartProductShare = document.querySelector(ECHART_PRODUCT_SHARE);

    if ($echartProductShare) {
        var userOptions = utils.getData($echartProductShare, 'options');
        var chart = window.echarts.init($echartProductShare);

        var getDefaultOptions = function getDefaultOptions() {
            return {
                color: [utils.getColors().primary, utils.getColors().info, utils.getColors().warning],
                tooltip: {
                    trigger: 'item',
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()['100'],
                    borderColor: utils.getGrays()['300'],
                    textStyle: {
                        color: utils.getColors().dark
                    },
                    borderWidth: 1,
                    transitionDuration: 0,
                    formatter: function formatter(params) {
                        return "<strong>".concat(params.data.name, ":</strong> ").concat(params.percent, "%");
                    }
                },
                position: function position(pos, params, dom, rect, size) {
                    return getPosition(pos, params, dom, rect, size);
                },
                legend: {
                    show: false
                },
                series: [{
                    type: 'pie',
                    radius: ['100%', '80%'],
                    avoidLabelOverlap: false,
                    hoverAnimation: false,
                    itemStyle: {
                        borderWidth: 2,
                        borderColor: utils.getColor('card-bg')
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center',
                            textStyle: {
                                fontSize: '20',
                                fontWeight: '500',
                                color: utils.getGrays()['700']
                            }
                        },
                        emphasis: {
                            show: false
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: [{
                        value: r[0],
                        name: 'Online'
                    }, {
                        value: r[1],
                        name: 'Offline'
                    }]
                }]
            };
        };
        echartSetOption(chart, userOptions, getDefaultOptions);
    }
};

var MarketplaceVenditeInit = function MarketplaceVenditeInit(datas) {
    var r = datas.split('-')
    var ECHART_PRODUCT_SHARE = '.echart-product-share';
    var $echartProductShare = document.querySelector(ECHART_PRODUCT_SHARE);

    if ($echartProductShare) {
        var userOptions = utils.getData($echartProductShare, 'options');
        var chart = window.echarts.init($echartProductShare);

        var getDefaultOptions = function getDefaultOptions() {
            return {
                color: [utils.getColors().primary, utils.getColors().info, utils.getColors().warning],
                tooltip: {
                    trigger: 'item',
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()['100'],
                    borderColor: utils.getGrays()['300'],
                    textStyle: {
                        color: utils.getColors().dark
                    },
                    borderWidth: 1,
                    transitionDuration: 0,
                    formatter: function formatter(params) {
                        return "<strong>".concat(params.data.name, ":</strong> ").concat(params.percent, "%");
                    }
                },
                position: function position(pos, params, dom, rect, size) {
                    return getPosition(pos, params, dom, rect, size);
                },
                legend: {
                    show: false
                },
                series: [{
                    type: 'pie',
                    radius: ['100%', '80%'],
                    avoidLabelOverlap: false,
                    hoverAnimation: false,
                    itemStyle: {
                        borderWidth: 2,
                        borderColor: utils.getColor('card-bg')
                    },
                    label: {
                        normal: {
                            show: false,
                            position: 'center',
                            textStyle: {
                                fontSize: '20',
                                fontWeight: '500',
                                color: utils.getGrays()['700']
                            }
                        },
                        emphasis: {
                            show: false
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: [{
                        value: r[0],
                        name: 'Sito'
                    }, {
                        value: r[1],
                        name: 'ManoMano'
                    }, {
                        value: r[2],
                        name: 'eBay'
                    }]
                }]
            };
        };
        echartSetOption(chart, userOptions, getDefaultOptions);
    }
};

var OrdiniTotaliInit = function OrdiniTotaliInit(datas) {
    var rr = datas.split('-')
    var ECHART_LINE_TOTAL_ORDER = '.total-order-ecommerce'; //

    var $echartLineTotalOrder = document.querySelector(ECHART_LINE_TOTAL_ORDER);

    if ($echartLineTotalOrder) {
        // Get options from data attribute
        var userOptions = utils.getData($echartLineTotalOrder, 'options');
        var chart = window.echarts.init($echartLineTotalOrder); // Default options

        var getDefaultOptions = function getDefaultOptions() {
            return {
                tooltip: {
                    triggerOn: 'mousemove',
                    trigger: 'axis',
                    padding: [7, 10],
                    formatter: '{b0}: {c0}',
                    backgroundColor: utils.getGrays()['100'],
                    borderColor: utils.getGrays()['300'],
                    textStyle: {
                        color: utils.getColors().dark
                    },
                    borderWidth: 1,
                    transitionDuration: 0,
                    position: function position(pos, params, dom, rect, size) {
                        return getPosition(pos, params, dom, rect, size);
                    }
                },
                xAxis: {
                    type: 'category',
                    data: ['Questo mese', 'Mese scorso', '2 mesi fa', '3 mesi fa'],
                    boundaryGap: false,
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: false,
                        lineStyle: {
                            color: utils.getGrays()['300'],
                            type: 'dashed'
                        }
                    },
                    axisLabel: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisPointer: {
                        type: 'none'
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        show: false
                    },
                    axisLine: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    axisPointer: {
                        show: false
                    }
                },
                series: [{
                    type: 'line',
                    lineStyle: {
                        color: utils.getColors().primary,
                        width: 3
                    },
                    itemStyle: {
                        color: utils.getGrays().white,
                        borderColor: utils.getColors().primary,
                        borderWidth: 2
                    },
                    hoverAnimation: true,
                    data: rr,
                    // connectNulls: true,
                    smooth: 0.6,
                    smoothMonotone: 'x',
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 8,
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [{
                                offset: 0,
                                color: utils.rgbaColor(utils.getColors().primary, 0.25)
                            }, {
                                offset: 1,
                                color: utils.rgbaColor(utils.getColors().primary, 0)
                            }]
                        }
                    }
                }],
                grid: {
                    bottom: '2%',
                    top: '0%',
                    right: '10px',
                    left: '10px'
                }
            };
        };

        echartSetOption(chart, userOptions, getDefaultOptions);
    }
};

var VenditeEcomAnno1eM1 = function VenditeEcomAnno1eM1(A1, A2) {
    var a1 = A1.split(',');
    var a2 = A2.split(',');

    var ECHART_LINE_TOTAL_SALES_ECOMM = '.echart-line-total-sales-ecommerce';
    var $echartsLineTotalSalesEcomm = document.querySelector(ECHART_LINE_TOTAL_SALES_ECOMM);
    var months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];

    function getFormatter(params) {
        return params.map(function (_ref16, index) {
            var value = _ref16.value,
                borderColor = _ref16.borderColor;
            return "<span class= \"fas fa-circle\" style=\"color: ".concat(borderColor, "\"></span>\n    <span class='text-600'>").concat(index === 0 ? 'Anno attuale' : 'Anno precedente', ": ").concat(value, "</span>");
        }).join('<br/>');
    }

    if ($echartsLineTotalSalesEcomm) {
        // Get options from data attribute
        var userOptions = utils.getData($echartsLineTotalSalesEcomm, 'options');
        var TOTAL_SALES_LAST_MONTH = "#".concat(userOptions.optionOne);
        var TOTAL_SALES_PREVIOUS_YEAR = "#".concat(userOptions.optionTwo);
        var totalSalesLastMonth = document.querySelector(TOTAL_SALES_LAST_MONTH);
        var totalSalesPreviousYear = document.querySelector(TOTAL_SALES_PREVIOUS_YEAR);
        var chart = window.echarts.init($echartsLineTotalSalesEcomm);

        var getDefaultOptions = function getDefaultOptions() {
            return {
                color: utils.getGrays()['100'],
                tooltip: {
                    trigger: 'axis',
                    padding: [7, 10],
                    backgroundColor: utils.getGrays()['100'],
                    borderColor: utils.getGrays()['300'],
                    textStyle: {
                        color: utils.getColors().dark
                    },
                    borderWidth: 1,
                    formatter: function formatter(params) {
                        return getFormatter(params);
                    },
                    transitionDuration: 0,
                    position: function position(pos, params, dom, rect, size) {
                        return getPosition(pos, params, dom, rect, size);
                    }
                },
                legend: {
                    data: ['lastMonth', 'previousYear'],
                    show: false
                },
                xAxis: {
                    type: 'category',
                    data: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
                    boundaryGap: false,
                    axisPointer: {
                        lineStyle: {
                            color: utils.getColor('300'),
                            type: 'dashed'
                        }
                    },
                    splitLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                },
                yAxis: {
                    type: 'value',
                    axisPointer: {
                        show: false
                    },
                    splitLine: {
                        lineStyle: {
                            color: utils.getColor('300'),
                            type: 'dashed'
                        }
                    },
                    boundaryGap: false,
                    axisLabel: {
                        show: true,
                        color: utils.getColor('400'),
                        margin: 15
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        show: false
                    }
                },
                series: [{
                    name: 'lastMonth',
                    type: 'line',
                    data: a1,
                    lineStyle: {
                        color: utils.getColor('primary')
                    },
                    itemStyle: {
                        borderColor: utils.getColor('primary'),
                        borderWidth: 2
                    },
                    symbol: 'circle',
                    symbolSize: 10,
                    hoverAnimation: true,
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [{
                                offset: 0,
                                color: utils.rgbaColor(utils.getColor('primary'), 0.2)
                            }, {
                                offset: 1,
                                color: utils.rgbaColor(utils.getColor('primary'), 0)
                            }]
                        }
                    }
                }, {
                    name: 'previousYear',
                    type: 'line',
                    data: a2,
                    lineStyle: {
                        color: utils.rgbaColor(utils.getColor('warning'), 0.3)
                    },
                    itemStyle: {
                        borderColor: utils.rgbaColor(utils.getColor('warning'), 0.6),
                        borderWidth: 2
                    },
                    symbol: 'circle',
                    symbolSize: 10,
                    hoverAnimation: true
                }],
                grid: {
                    right: '18px',
                    left: '40px',
                    bottom: '15%',
                    top: '5%'
                }
            };
        };

        echartSetOption(chart, userOptions, getDefaultOptions);
        totalSalesLastMonth.addEventListener('click', function () {
            chart.dispatchAction({
                type: 'legendToggleSelect',
                name: 'lastMonth'
            });
        });
        totalSalesPreviousYear.addEventListener('click', function () {
            chart.dispatchAction({
                type: 'legendToggleSelect',
                name: 'previousYear'
            });
        });
    }
};