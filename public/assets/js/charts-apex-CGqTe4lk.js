(function () {
    let i, c, e, t, r;
    isDarkStyle ? (i = config.colors_dark.cardColor, c = config.colors_dark.headingColor, e = config.colors_dark.textMuted, r = config.colors_dark.bodyColor, t = config.colors_dark.borderColor) : (i = config.colors.cardColor, c = config.colors.headingColor, e = config.colors.textMuted, r = config.colors.bodyColor, t = config.colors.borderColor);
    const a = {
        column: {series1: "#826af9", series2: "#d2b0ff", bg: "#f8d3ff"},
        donut: {series1: "#fee802", series2: "#F1F0F2", series3: "#826bf8", series4: "#3fd0bd"},
        area: {series1: "#29dac7", series2: "#60f2ca", series3: "#a5f8cd"},
        bar: {bg: "#1D9FF2"}
    };

    function s(o, l) {
        let n = 0, y = [];
        for (; n < o;) {
            let M = "w" + (n + 1).toString(), v = Math.floor(Math.random() * (l.max - l.min + 1)) + l.min;
            y.push({x: M, y: v}), n++
        }
        return y
    }

    const d = document.querySelector("#lineAreaChart"), w = {
        chart: {height: 400, type: "area", parentHeightOffset: 0, toolbar: {show: !1}},
        dataLabels: {enabled: !1},
        stroke: {show: !1, curve: "straight"},
        legend: {show: !0, position: "top", horizontalAlign: "start", labels: {colors: r, useSeriesColors: !1}},
        grid: {borderColor: t, xaxis: {lines: {show: !0}}},
        colors: [a.area.series3, a.area.series2, a.area.series1],
        series: [{
            name: "Visits",
            data: [100, 120, 90, 170, 130, 160, 140, 240, 220, 180, 270, 280, 375]
        }, {name: "Clicks", data: [60, 80, 70, 110, 80, 100, 90, 180, 160, 140, 200, 220, 275]}, {
            name: "Sales",
            data: [20, 40, 30, 70, 40, 60, 50, 140, 120, 100, 140, 180, 220]
        }],
        xaxis: {
            categories: ["7/12", "8/12", "9/12", "10/12", "11/12", "12/12", "13/12", "14/12", "15/12", "16/12", "17/12", "18/12", "19/12", "20/12"],
            axisBorder: {show: !1},
            axisTicks: {show: !1},
            labels: {style: {colors: e, fontSize: "13px"}}
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}},
        fill: {opacity: 1, type: "solid"},
        tooltip: {shared: !1}
    };
    typeof d !== void 0 && d !== null && new ApexCharts(d, w).render();
    const h = document.querySelector("#barChart"), S = {
        chart: {height: 400, type: "bar", stacked: !0, parentHeightOffset: 0, toolbar: {show: !1}},
        plotOptions: {
            bar: {
                columnWidth: "15%",
                colors: {
                    backgroundBarColors: [a.column.bg, a.column.bg, a.column.bg, a.column.bg, a.column.bg],
                    backgroundBarRadius: 10
                }
            }
        },
        dataLabels: {enabled: !1},
        legend: {show: !0, position: "top", horizontalAlign: "start", labels: {colors: r, useSeriesColors: !1}},
        colors: [a.column.series1, a.column.series2],
        stroke: {show: !0, colors: ["transparent"]},
        grid: {borderColor: t, xaxis: {lines: {show: !0}}},
        series: [{name: "Apple", data: [90, 120, 55, 100, 80, 125, 175, 70, 88, 180]}, {
            name: "Samsung",
            data: [85, 100, 30, 40, 95, 90, 30, 110, 62, 20]
        }],
        xaxis: {
            categories: ["7/12", "8/12", "9/12", "10/12", "11/12", "12/12", "13/12", "14/12", "15/12", "16/12"],
            axisBorder: {show: !1},
            axisTicks: {show: !1},
            labels: {style: {colors: e, fontSize: "13px"}}
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}},
        fill: {opacity: 1}
    };
    typeof h !== void 0 && h !== null && new ApexCharts(h, S).render();
    const f = document.querySelector("#scatterChart"), k = {
        chart: {
            height: 400,
            type: "scatter",
            zoom: {enabled: !0, type: "xy"},
            parentHeightOffset: 0,
            toolbar: {show: !1}
        },
        grid: {borderColor: t, xaxis: {lines: {show: !0}}},
        legend: {show: !0, position: "top", horizontalAlign: "start", labels: {colors: r, useSeriesColors: !1}},
        colors: [config.colors.warning, config.colors.primary, config.colors.success],
        series: [{
            name: "Angular",
            data: [[5.4, 170], [5.4, 100], [5.7, 110], [5.9, 150], [6, 200], [6.3, 170], [5.7, 140], [5.9, 130], [7, 150], [8, 120], [9, 170], [10, 190], [11, 220], [12, 170], [13, 230]]
        }, {
            name: "Vue",
            data: [[14, 220], [15, 280], [16, 230], [18, 320], [17.5, 280], [19, 250], [20, 350], [20.5, 320], [20, 320], [19, 280], [17, 280], [22, 300], [18, 120]]
        }, {
            name: "React",
            data: [[14, 290], [13, 190], [20, 220], [21, 350], [21.5, 290], [22, 220], [23, 140], [19, 400], [20, 200], [22, 90], [20, 120]]
        }],
        xaxis: {
            tickAmount: 10, axisBorder: {show: !1}, axisTicks: {show: !1}, labels: {
                formatter: function (o) {
                    return parseFloat(o).toFixed(1)
                }, style: {colors: e, fontSize: "13px"}
            }
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}}
    };
    typeof f !== void 0 && f !== null && new ApexCharts(f, k).render();
    const u = document.querySelector("#lineChart"), z = {
        chart: {height: 400, type: "line", parentHeightOffset: 0, zoom: {enabled: !1}, toolbar: {show: !1}},
        series: [{data: [280, 200, 220, 180, 270, 250, 70, 90, 200, 150, 160, 100, 150, 100, 50]}],
        markers: {strokeWidth: 7, strokeOpacity: 1, strokeColors: [i], colors: [config.colors.warning]},
        dataLabels: {enabled: !1},
        stroke: {curve: "straight"},
        colors: [config.colors.warning],
        grid: {borderColor: t, xaxis: {lines: {show: !0}}, padding: {top: -20}},
        tooltip: {
            custom: function ({series: o, seriesIndex: l, dataPointIndex: n, w: y}) {
                return '<div class="px-3 py-2"><span>' + o[l][n] + "%</span></div>"
            }
        },
        xaxis: {
            categories: ["7/12", "8/12", "9/12", "10/12", "11/12", "12/12", "13/12", "14/12", "15/12", "16/12", "17/12", "18/12", "19/12", "20/12", "21/12"],
            axisBorder: {show: !1},
            axisTicks: {show: !1},
            labels: {style: {colors: e, fontSize: "13px"}}
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}}
    };
    typeof u !== void 0 && u !== null && new ApexCharts(u, z).render();
    const p = document.querySelector("#horizontalBarChart"), B = {
        chart: {height: 400, type: "bar", toolbar: {show: !1}},
        plotOptions: {bar: {horizontal: !0, barHeight: "30%", startingShape: "rounded", borderRadius: 8}},
        grid: {borderColor: t, xaxis: {lines: {show: !1}}, padding: {top: -20, bottom: -12}},
        colors: a.bar.bg,
        dataLabels: {enabled: !1},
        series: [{data: [700, 350, 480, 600, 210, 550, 150]}],
        xaxis: {
            categories: ["MON, 11", "THU, 14", "FRI, 15", "MON, 18", "WED, 20", "FRI, 21", "MON, 23"],
            axisBorder: {show: !1},
            axisTicks: {show: !1},
            labels: {style: {colors: e, fontSize: "13px"}}
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}}
    };
    typeof p !== void 0 && p !== null && new ApexCharts(p, B).render();
    const m = document.querySelector("#candleStickChart"), A = {
        chart: {height: 410, type: "candlestick", parentHeightOffset: 0, toolbar: {show: !1}},
        series: [{
            data: [{x: new Date(15387786e5), y: [150, 170, 50, 100]}, {
                x: new Date(15387804e5),
                y: [200, 400, 170, 330]
            }, {x: new Date(15387822e5), y: [330, 340, 250, 280]}, {
                x: new Date(1538784e6),
                y: [300, 330, 200, 320]
            }, {x: new Date(15387858e5), y: [320, 450, 280, 350]}, {
                x: new Date(15387876e5),
                y: [300, 350, 80, 250]
            }, {x: new Date(15387894e5), y: [200, 330, 170, 300]}, {
                x: new Date(15387912e5),
                y: [200, 220, 70, 130]
            }, {x: new Date(1538793e6), y: [220, 270, 180, 250]}, {
                x: new Date(15387948e5),
                y: [200, 250, 80, 100]
            }, {x: new Date(15387966e5), y: [150, 170, 50, 120]}, {
                x: new Date(15387984e5),
                y: [110, 450, 10, 420]
            }, {x: new Date(15388002e5), y: [400, 480, 300, 320]}, {x: new Date(1538802e6), y: [380, 480, 350, 450]}]
        }],
        xaxis: {
            type: "datetime",
            axisBorder: {show: !1},
            axisTicks: {show: !1},
            labels: {style: {colors: e, fontSize: "13px"}}
        },
        yaxis: {tooltip: {enabled: !0}, labels: {style: {colors: e, fontSize: "13px"}}},
        grid: {borderColor: t, xaxis: {lines: {show: !0}}, padding: {top: -20}},
        plotOptions: {
            candlestick: {colors: {upward: config.colors.success, downward: config.colors.danger}},
            bar: {columnWidth: "40%"}
        }
    };
    typeof m !== void 0 && m !== null && new ApexCharts(m, A).render();
    const g = document.querySelector("#heatMapChart"), D = {
        chart: {height: 350, type: "heatmap", parentHeightOffset: 0, toolbar: {show: !1}},
        plotOptions: {
            heatmap: {
                enableShades: !1,
                colorScale: {
                    ranges: [{from: 0, to: 10, name: "0-10", color: "#90B3F3"}, {
                        from: 11,
                        to: 20,
                        name: "10-20",
                        color: "#7EA6F1"
                    }, {from: 21, to: 30, name: "20-30", color: "#6B9AEF"}, {
                        from: 31,
                        to: 40,
                        name: "30-40",
                        color: "#598DEE"
                    }, {from: 41, to: 50, name: "40-50", color: "#4680EC"}, {
                        from: 51,
                        to: 60,
                        name: "50-60",
                        color: "#3474EA"
                    }]
                }
            }
        },
        dataLabels: {enabled: !1},
        grid: {show: !1},
        legend: {
            show: !0,
            position: "top",
            horizontalAlign: "start",
            labels: {colors: r, useSeriesColors: !1},
            markers: {offsetY: 0, offsetX: -3},
            itemMargin: {vertical: 3, horizontal: 10}
        },
        stroke: {curve: "smooth", width: 4, lineCap: "round", colors: [i]},
        series: [{name: "SUN", data: s(24, {min: 0, max: 60})}, {
            name: "MON",
            data: s(24, {min: 0, max: 60})
        }, {name: "TUE", data: s(24, {min: 0, max: 60})}, {name: "WED", data: s(24, {min: 0, max: 60})}, {
            name: "THU",
            data: s(24, {min: 0, max: 60})
        }, {name: "FRI", data: s(24, {min: 0, max: 60})}, {name: "SAT", data: s(24, {min: 0, max: 60})}],
        xaxis: {
            labels: {show: !1, style: {colors: e, fontSize: "13px"}},
            axisBorder: {show: !1},
            axisTicks: {show: !1}
        },
        yaxis: {labels: {style: {colors: e, fontSize: "13px"}}}
    };
    typeof g !== void 0 && g !== null && new ApexCharts(g, D).render();
    const b = document.querySelector("#radialBarChart"), O = {
        chart: {height: 380, type: "radialBar"},
        colors: [a.donut.series1, a.donut.series2, a.donut.series4],
        plotOptions: {
            radialBar: {
                size: 185,
                hollow: {size: "40%"},
                track: {margin: 10, background: config.colors_label.secondary},
                dataLabels: {
                    name: {fontSize: "2rem", fontFamily: "Public Sans"},
                    value: {fontSize: "1.2rem", color: r, fontFamily: "Public Sans"},
                    total: {
                        show: !0,
                        fontWeight: 400,
                        fontSize: "1.3rem",
                        color: c,
                        label: "Comments",
                        formatter: function (o) {
                            return "80%"
                        }
                    }
                }
            }
        },
        grid: {borderColor: t, padding: {top: -25, bottom: -20}},
        legend: {show: !0, position: "bottom", labels: {colors: r, useSeriesColors: !1}},
        stroke: {lineCap: "round"},
        series: [80, 50, 35],
        labels: ["Comments", "Replies", "Shares"]
    };
    typeof b !== void 0 && b !== null && new ApexCharts(b, O).render();
    const C = document.querySelector("#radarChart"), E = {
        chart: {
            height: 350,
            type: "radar",
            toolbar: {show: !1},
            dropShadow: {enabled: !1, blur: 8, left: 1, top: 1, opacity: .2}
        },
        legend: {show: !0, position: "bottom", labels: {colors: r, useSeriesColors: !1}},
        plotOptions: {radar: {polygons: {strokeColors: t, connectorColors: t}}},
        yaxis: {show: !1},
        series: [{name: "iPhone 12", data: [41, 64, 81, 60, 42, 42, 33, 23]}, {
            name: "Samsung s20",
            data: [65, 46, 42, 25, 58, 63, 76, 43]
        }],
        colors: [a.donut.series1, a.donut.series3],
        xaxis: {
            categories: ["Battery", "Brand", "Camera", "Memory", "Storage", "Display", "OS", "Price"],
            labels: {show: !0, style: {colors: [e, e, e, e, e, e, e, e], fontSize: "13px", fontFamily: "Public Sans"}}
        },
        fill: {opacity: [1, .8]},
        stroke: {show: !1, width: 0},
        markers: {size: 0},
        grid: {show: !1, padding: {top: -20, bottom: -20}}
    };
    typeof C !== void 0 && C !== null && new ApexCharts(C, E).render();
    const x = document.querySelector("#donutChart"), F = {
        chart: {height: 390, type: "donut"},
        labels: ["Operational", "Networking", "Hiring", "R&D"],
        series: [42, 7, 25, 25],
        colors: [a.donut.series1, a.donut.series4, a.donut.series3, a.donut.series2],
        stroke: {show: !1, curve: "straight"},
        dataLabels: {
            enabled: !0, formatter: function (o, l) {
                return parseInt(o, 10) + "%"
            }
        },
        legend: {
            show: !0,
            position: "bottom",
            markers: {offsetX: -3},
            itemMargin: {vertical: 3, horizontal: 10},
            labels: {colors: r, useSeriesColors: !1}
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: !0,
                        name: {fontSize: "2rem", fontFamily: "Public Sans"},
                        value: {
                            fontSize: "1.2rem", color: r, fontFamily: "Public Sans", formatter: function (o) {
                                return parseInt(o, 10) + "%"
                            }
                        },
                        total: {
                            show: !0, fontSize: "1.5rem", color: c, label: "Operational", formatter: function (o) {
                                return "42%"
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 992,
            options: {chart: {height: 380}, legend: {position: "bottom", labels: {colors: r, useSeriesColors: !1}}}
        }, {
            breakpoint: 576,
            options: {
                chart: {height: 320},
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: !0,
                                name: {fontSize: "1.5rem"},
                                value: {fontSize: "1rem"},
                                total: {fontSize: "1.5rem"}
                            }
                        }
                    }
                },
                legend: {position: "bottom", labels: {colors: r, useSeriesColors: !1}}
            }
        }, {breakpoint: 420, options: {chart: {height: 280}, legend: {show: !1}}}, {
            breakpoint: 360,
            options: {chart: {height: 250}, legend: {show: !1}}
        }]
    };
    typeof x !== void 0 && x !== null && new ApexCharts(x, F).render()
})();
