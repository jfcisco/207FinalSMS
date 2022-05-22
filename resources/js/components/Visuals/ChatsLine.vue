<template>
  <div>
    <h1>{{ list }}</h1>
  </div>
</template>
<script>
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios,axios)
export default{
  name:"ChatsLine",
  data(){
    return{list:undefined}
  },
  mounted() {
    Vue.axios.get('/api/reports/chats/todays-hourly').then((resp)=>{
      this.list=resp.data.data;
      console.warn(resp.data.data);
    })
  },

}
</script>

<!--<template>
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
            label: 'CHATS',
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
          labelString:'Chat Volume'
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
</script>-->