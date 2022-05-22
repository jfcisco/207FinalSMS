<template>
  <canvas ref="myChart" width="50px;" height="50px;"></canvas>
</template>
<script>
import { LineController } from 'chart.js';
export default {
  props: {
    label: {
      type: Array
    },
    chartData: {
      type: Array
    },
  },
  type: LineController,
  mounted() {
    this.$refs.myChart, {
      data: {
        labels: this.label,
        datasets: [
        {
            label: 'VISITORS',
            borderColor: 'rgba(245, 229, 27, 1)',
            backgroundColor: 'rgba(255, 236, 139,0.2)',
            data: this.chartData
        }]
      },
      responsive:true,
      options: {
    scales: {
      yAxes: [{
        stacked: false,
        scaleLabel:{
          display:true,
          fontColor:'white',
          fontSize:25,
          labelString:'Visitor Volume'
        },
        ticks: {
          fontColor: 'white',
          fontSize: 20,
          min: 0,
          beginAtZero: true
        }
      }],
      xAxes: [{
        stacked: false,
        scaleLabel: {
          display: true,
          fontColor: 'white',
          fontSize: 25,
          labelString: 'Hour'
        },
        ticks: {
          fontColor: 'white',
          fontSize: 20,
          min: 0
        }        
      }]
    }
    }
    }
  }
}
</script>