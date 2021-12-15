/*
Report for Administrator users on the Dashboard that shows Total Sales by Seller by Month.
*/
$(document).ready(function () {
    $(function () {
        var salesReportByNameData = [];
        var dataName = [];
        var dataColors = [];
        var dataTotal = [];
        $.ajax({
            url: '/api/charts/administrator/daily-sales-by-seller',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                salesReportByNameData = Object.values(response);
                $.each(salesReportByNameData, function (index, value) {
                    dataName.push(value['name']);
                    dataTotal.push(value['totalSales']);
                    dataColors.push(value['color']);
                });

                loadChart();

            }
        });

        function loadChart() {
            if(dataName.length === 0){
                dataName = ['No Data Available'];
                dataTotal = [1];
            }
            var ctx = document.getElementById("daily_sales_by_seller").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                options: {
                    legend: {
                        display: true,
                        position: "bottom"
                    }
                },
                data: {
                    labels: dataName,
                    datasets: [{
                        backgroundColor: dataColors,
                        data: dataTotal
                    }]
                }
            });
        }
    });
});
