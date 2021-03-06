<section class="content">          
<div class="row">
    <div class="col-md-4">
        <div  class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Bar chart</h3>
                <div class="box-tools pull-right"></div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <canvas id="bar" style="width: 100%;"></canvas>
                <script>
                $(function () {

                    function randomScalingFactor() {
                        return Math.floor(Math.random() * 100)
                    }

                    window.chartColors = {
                        red: 'rgb(255, 99, 132)',
                        orange: 'rgb(255, 159, 64)',
                        yellow: 'rgb(255, 205, 86)',
                        green: 'rgb(75, 192, 192)',
                        blue: 'rgb(54, 162, 235)',
                        purple: 'rgb(153, 102, 255)',
                        grey: 'rgb(201, 203, 207)'
                    };

                    var barChartData = {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                        datasets: [{
                            label: 'Dataset 1',
                            borderColor: window.chartColors.red,
                            borderWidth: 1,
                            data: [
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor()
                            ]
                        }, {
                            label: 'Dataset 2',
                            borderColor: window.chartColors.blue,
                            borderWidth: 1,
                            data: [
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor(),
                                randomScalingFactor()
                            ]
                        }]

                    };

                    var ctx = document.getElementById('bar').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: barChartData,
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Chart.js Bar Chart'
                            }
                        }
                    });
                });
                </script>
            </div><!-- /.box-body -->
        </div>
    </div>
<div class="col-md-4"><div  class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Scatter chart</h3>
            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
        <canvas id="scatter" style="width: 100%;"></canvas>
<script>
$(function () {
    function randomScalingFactor() {
        return Math.floor(Math.random() * 100)
    }

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    var ctx = document.getElementById("scatter").getContext('2d');
    var scatterChart = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'My First dataset',
                borderColor: window.chartColors.red,
                data: [{
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }]
            }, {
                label: 'My Second dataset',
                borderColor: window.chartColors.blue,
                data: [{
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }, {
                    x: randomScalingFactor(),
                    y: randomScalingFactor(),
                }]
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'linear',
                    position: 'bottom'
                }]
            }
        }
    });
});
</script>
    </div><!-- /.box-body -->
    </div>

<script>
    
</script></div><div class="col-md-4"><div  class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Line chart</h3>
            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
        <canvas id="line" style="width: 100%;"></canvas>
<script>
$(function () {

    function randomScalingFactor() {
        return Math.floor(Math.random() * 100)
    }

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    var config = {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'My First dataset',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
                fill: false,
            }, {
                label: 'My Second dataset',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Chart.js Line Chart'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    };

    var ctx = document.getElementById('line').getContext('2d');
    new Chart(ctx, config);
});
</script>
    </div><!-- /.box-body -->
    </div>

<script>
    
</script></div></div><div class="row"><div class="col-md-4"><div  class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Doughnut chart</h3>
            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
        <canvas id="doughnut" style="width: 100%;"></canvas>
<script>
$(function () {

    function randomScalingFactor() {
        return Math.floor(Math.random() * 100)
    }

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                'Red',
                'Orange',
                'Yellow',
                'Green',
                'Blue'
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Chart.js Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    var ctx = document.getElementById('doughnut').getContext('2d');
    new Chart(ctx, config);
});
</script>
    </div><!-- /.box-body -->
    </div>

<script>
    
</script></div><div class="col-md-4"><div  class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Chart.js Combo Bar Line Chart</h3>
            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
        <canvas id="bar-line" style="width: 100%;"></canvas>
<script>
$(function () {

    function randomScalingFactor() {
        return Math.floor(Math.random() * 100)
    }

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    var chartData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            type: 'line',
            label: 'Dataset 1',
            borderColor: window.chartColors.blue,
            borderWidth: 2,
            fill: false,
            data: [
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor()
            ]
        }, {
            type: 'bar',
            label: 'Dataset 2',
            backgroundColor: window.chartColors.red,
            data: [
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor()
            ],
            borderColor: 'white',
            borderWidth: 2
        }, {
            type: 'bar',
            label: 'Dataset 3',
            backgroundColor: window.chartColors.green,
            data: [
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor(),
                randomScalingFactor()
            ]
        }]

    };

    var ctx = document.getElementById('bar-line').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Chart.js Combo Bar Line Chart'
            },
            tooltips: {
                mode: 'index',
                intersect: true
            }
        }
    });
});
</script>
    </div><!-- /.box-body -->
    </div>

<script>
    
</script></div><div class="col-md-4"><div  class="box">
            <div class="box-header with-border">
            <h3 class="box-title">Chart.js Line Chart - Stacked Area</h3>
            <div class="box-tools pull-right">
                            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
        <canvas id="line-stacked" style="width: 100%;"></canvas>
<script>
$(function () {

    function randomScalingFactor() {
        return Math.floor(Math.random() * 100)
    }

    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    var config = {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'My First dataset',
                borderColor: window.chartColors.red,
                backgroundColor: window.chartColors.red,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }, {
                label: 'My Second dataset',
                borderColor: window.chartColors.blue,
                backgroundColor: window.chartColors.blue,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }, {
                label: 'My Third dataset',
                borderColor: window.chartColors.green,
                backgroundColor: window.chartColors.green,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }, {
                label: 'My Third dataset',
                borderColor: window.chartColors.yellow,
                backgroundColor: window.chartColors.yellow,
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor()
                ],
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Chart.js Line Chart - Stacked Area'
            },
            tooltips: {
                mode: 'index',
            },
            hover: {
                mode: 'index'
            },
            scales: {
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    };

    var ctx = document.getElementById('line-stacked').getContext('2d');
    new Chart(ctx, config);
});
</script>
    </div><!-- /.box-body -->
    </div>

<script>
    
</script></div></div>

</section>
