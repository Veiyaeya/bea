 const ctx = document.getElementById('salesChart').getContext('2d');

    const salesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Monthly Sales',
          data: [10, 25, 30, 45, 50, 60, 55, 70, 80, 90, 85, 100],
          borderColor: '#395C58',
          backgroundColor: 'rgba(57, 92, 88, 0.2)',

          fill: true,
          tension: 0.3,
          pointRadius: 4,
          pointBackgroundColor: '#395C58'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            min: 10,
            max: 100,
            ticks: {
              stepSize: 10,
              precision: 0,
              color: '#395C58',
              autoskip: false
            },
            grid: {
              color: 'rgba(57, 92, 88, 0.2)'
            }
          },
          x: {
            ticks: {
              color: '#395C58'
            },
            grid: {
              color: 'rgba(57, 92, 88, 0.1)'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              generateLabels: function (chart) {
                const data = chart.data;
                return data.datasets.map((dataset, i) => {
                  return {
                    text: dataset.label,
                    fillStyle: '#395C58',
                    strokeStyle: '#395C58',
                    lineWidth: 1,
                    hidden: !chart.isDatasetVisible(i),
                    index: i,
                    fontColor: '#395C58',
                    pointStyle: 'circle'
                  };
                });
              }
            }
          }
        }

      }
    });
