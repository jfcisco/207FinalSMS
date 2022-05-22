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
  name:"MissedChat",
  data(){
    return{list:undefined}
  },
  mounted() {
    Vue.axios.get('/api/reports/chats/missed').then((resp)=>{
      this.list=resp.data.data;
      console.warn(resp.data.data);
    })
  },

}
</script>