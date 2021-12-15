/*
Report for Administrator users on the Dashboard that shows "Total Sales" by Month.
*/
$(document).ready(function () {
    $(function () {
        var ctx = document.getElementById('sales_report_by_month').getContext('2d');
        var salesReportByNameData = [];
        var dataName = [];
        var dataTotal = [];
        $.ajax({
            url: '/api/charts/administrator/sales-by-month',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                salesReportByNameData = Object.values(response);
                $.each(salesReportByNameData, function (index, value) {
                    dataName.push(value['month']);
                    dataTotal.push(value['totalSales']);
                });
                loadChart();
            }
        });

        function loadChart() {
            new Chart(ctx, {
                type: 'bar',
                options: {
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 50
                            },
                            stacked: true
                        }]
                    },
                    legend: {
                        display: false
                    }
                },
                data: {
                    labels: dataName,
                    datasets: [{
                        label: 'Total Sales',
                        data: dataTotal,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                        ],
                        borderWidth: 2
                    }]
                },
            });
        }
    });
});
