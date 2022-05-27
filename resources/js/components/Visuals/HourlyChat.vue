<template>
  <tbody id = "displayTableVisitors">
  </tbody>
</template> 


<script>
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios,axios)
export default{
  name:"HourlyChat",
  data(){
    return{}
  },
  mounted() {
    Vue.axios.get('/api/reports/chats/todays-hourly ').then((resp)=>{
    
      this.divContainer = document.getElementById("displayTableVisitors");
      this.divContainer.innerHTML = "";
    
      let visitorsHr = Object.keys(resp.data.data);

      visitorsHr.forEach((key) => {
           this.divContainer.innerHTML += `<tr style="border: 1px solid gray;">
              <td style="border: 1px solid gray; padding: 0px 5px 0px 5px">${key}</td>
              <td style="border: 1px solid gray; padding: 0px 5px 0px 5px">${resp.data.data[key]}</td>
          </tr>`;
      });

      console.warn(this.divContainer);

    })
  },
    
}
</script>