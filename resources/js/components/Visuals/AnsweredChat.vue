<template>
<div>
<h5>{{ list }}</h5>
</div>
</template>
<script>
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios,axios)
export default{
  name:"AnsweredChat",
  data(){
    return{list:undefined}
  },
  mounted() {
    Vue.axios.get('/api/reports/chats/answered').then((resp)=>{
      this.list=resp.data.data;
      console.warn(resp.data.data);
    })
  },

}
</script>