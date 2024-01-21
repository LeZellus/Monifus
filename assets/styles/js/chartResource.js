import {Chart} from "chart.js";
import moment from 'moment';
import 'chartjs-adapter-moment';

document.addEventListener('DOMContentLoaded', function () {
   const chartElement = document.getElementById('myChart');
   const resourceId = chartElement.dataset.resourceId;

   const chartUrl = '/resource/'+ resourceId +'/data';

   let chart;
   let currentPeriod = 'day';

   function fetchData(period) {
      currentPeriod = period;
      fetch(`${chartUrl}?period=day`)
         .then(response => response.json())
         .then(data => updateChart(data))
          .catch(error => console.log('Error:', error));

   }

   function updateChart(data) {
      if (chart) {
         chart.destroy();
      }

      const ctx = chartElement.getContext('2d');
      chart = new Chart(ctx, {
         type: 'line',
         data: {
            datasets: [{
               label: 'Prix de la Ressource',
               data: data,
               fill: false,
               tension: 0.1
            }]
         },
         options: {
            scales: {
               x: {
                  type: 'time',
                  time: {
                     parser: 'YYYY-MM-DD HH:mm:ss',
                     unit: 'all' // ou 'week', 'month', etc.
                  },
                  display: true,
                  title: {
                     display: true,
                     text: 'Date'
                  }
               },
               y: {
                  display: true,
                  title: {
                     display: true,
                     text: 'Prix'
                  }
               }
            }
         }
      });
   }

   function determineTimeUnit(period) {
      switch (period) {
         case 'day':
            return 'day';
         case 'month':
            return 'month';
         case 'year':
            return 'year';
         case 'all':
            return 'all';
         default:
            return "day";
      }
   }

   document.querySelectorAll('.period-selector').forEach(button => {
      button.addEventListener('click', function () {
         console.log(this.dataset.period)
         fetchData(this.dataset.period);
      })
   })

   fetchData('day');
});