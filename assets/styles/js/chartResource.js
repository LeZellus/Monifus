import {Chart} from "chart.js";
import moment from 'moment';
import 'chartjs-adapter-moment';

document.addEventListener('DOMContentLoaded', function () {
   const chartElement = document.getElementById('myChart');
   const resourceId = chartElement.dataset.resourceId;

   const chartUrl = '/resource/'+ resourceId +'/data';
   let chart;

   function fetchData(period) {
      fetch(`${chartUrl}?period=${period}`)
         .then(response => response.json())
         .then(data => updateChart(data, period))
          .catch(error => console.log('Error:', error));

   }

   function updateChart(data, period) {
      if (chart) {
         chart.destroy();
      }

      // Transformer les données pour obtenir des dates au format correct
      const transformedData = data.map(d => {
         return {
            x: moment(d.x.date).toDate(), // Convertir en objet Date JavaScript
            y: d.y
         };
      });

      //Trouver les valeurs min et maxe pour l'axe Y
      const yValues = transformedData.map(d => d.y);
      const yMin = Math.min(...yValues);
      const yMax = Math.max(...yValues);

      // Calculer le min et max ajustés pour l'axe Y
      let adjustedYMin = Math.round(yMin - (yMax - yMin) * 0.1);
      let adjustedYMax = Math.round(yMax + (yMax - yMin) * 0.1);
      adjustedYMin = adjustedYMin < 0 ? 0 : adjustedYMin;

      const ctx = chartElement.getContext('2d');
      chart = new Chart(ctx, {
         type: 'line',
         data: {
            datasets: [{
               label: 'Prix de la Ressource',
               data: transformedData,
               fill: true,
               tension: 0.1,
               borderColor: 'rgb(133, 77, 14)',
               pointBorderColor: 'rgb(202, 138, 4)',
               pointBackgroundColor: 'rgb(234, 179, 8)'
            }]
         },
         options: {
            scales: {
               x: {
                  type: 'time',
                  time: {
                     unit: period // ou 'week', 'month', etc.
                  },
                  display: true,
                  title: {
                     display: true,
                     text: 'Date'
                  }
               },
               y: {
                  beginAtZero: false,
                  min: adjustedYMin,
                  max: adjustedYMax,
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

   document.querySelectorAll('.period-selector').forEach(button => {
      button.addEventListener('click', function () {
         console.log(this.dataset.period)
         fetchData(this.dataset.period);
      })
   })

   fetchData('year');
});