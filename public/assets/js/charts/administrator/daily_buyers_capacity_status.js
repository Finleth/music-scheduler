/*
Report for Administrator users on the Dashboard that shows Buyers Capacity Status.
*/
$(document).ready(function () {
    $(function () {
        var ctx = document.getElementById('daily_buyers_capacity_status').getContext('2d');
        var reportData = [];
        var dataName = [];
        var totalPurchased = [];
        var leftoverCap = [];
        var overage = [];
        $.ajax({
            url: '/api/charts/administrator/daily-buyers-capacity-status',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                reportData = Object.values(response);
                $.each(reportData, function (index, value) {
                    totalPurchased.push(value['totalPurchased']);
                    leftoverCap.push(value['leftoverCap']);
                    overage.push(value['overage']);
                    dataName.push(value['name']);
                });
                loadChart();
            }
        });

        function loadChart() {
            new Chart(ctx, {
                type: 'bar',
                options: {
                    scales: {
                        xAxes: [{stacked: true}],
                        yAxes: [{stacked: true}]
                    },
                    legend: {
                        display: false
                    }
                },
                data: {
                    labels: dataName,
                    datasets: [
                        {
                            label: 'Total Purchased',
                            backgroundColor: '#000000',
                            data: totalPurchased
                        },
                        {
                            label: 'Leftover Cap',
                            backgroundColor: '#9ccf70',
                            data: leftoverCap
                        },
                        {
                            label: 'Overage',
                            backgroundColor: '#D91010',
                            data: overage
                        }
                    ]
                },
            });
        }
    });
});
