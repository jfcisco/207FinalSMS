<template>
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-2 mainsidebar">
            <a href="/home"
                ><span class="menutitle">Messaging</span></a
            >
            <a class="activemenu" href="/reports"
                ><span class="menutitle">Reporting</span></a
            >
            <a href="/widgets"
                ><span class="menutitle">Widget</span></a
            >
        </div>

        <!--REPORT CONTENT-->

        <div class="col py-4 px-5" style="background-color: #627894;">
        
            <div class="row">
                <p class="menutitle" style="color:whitesmoke;">Chat Volume</p>
                <!-- Report Cards and Charts -->
                <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <div><h5 class="card-title">Visitors  </h5></div><br/>
                    <div class="row">
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Live</h5>
                          <p class="card-text">xx</p>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Today</h5>
                          <p class="card-text"><VisitorsToday></VisitorsToday></p>
                        </div>
                      </div>
                    </div>
                </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Chats  </h5><br/>
                    <div class="row">
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Answered</h5>
                          <p class="card-text"><AnsweredChat></AnsweredChat></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">Missed</h5>
                          <p class="card-text"><MissedChat></MissedChat></p>
                        </div>
                      </div>
                    </div>
                </div>
                </div>
              </div>
            </div>
        </div>

      <br/>
      <br/>

      <div class="row">
            <div class="col-sm-6"> 
                <div class="card">
                    <div class="card-body">
                        <h5>Visitors per hour</h5>
                        <div class="card">
                            <div class="card-body">
                                <VisitorsLine
                                :label="arrHour"
                                :chart-data="arrVisitor"></VisitorsLine> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6"> 
                <div class="card">
                    <div class="card-body">
                        <h5>Chats per hour</h5>
                        <div class="card">
                            <div class="card-body">
                                    <table class="charts-css bar show-heading show-labels show-data-axes data-spacing-10" id="my-chart">
                                    <!--<caption>Chats received per hour </caption>-->
                                        <thead>
                                            <tr>
                                                <th scope="row"> Hour </th>
                                                <th scope="row"> Number of Chats </th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <th scope="row"> 12 AM </th>
                                            <td style="--start: 0.0; --size: 0.20;">
                                            <span class="data"> 20 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 01 AM </th>
                                            <td style="--start: 0.0; --size: 0.36;">
                                            <span class="data"> 36 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 02 AM </th>
                                            <td style="--start: 0.0; --size: 0.45;">
                                            <span class="data"> 45 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 03 AM </th>
                                            <td style="--start: 0.0; --size: 0.52;">
                                            <span class="data"> 52 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 04 AM </th>
                                            <td style="--start: 0.0; --size: 0.20;">
                                            <span class="data"> 20 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 05 AM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 06 AM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 07 AM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 08 AM </th>
                                            <td style="--start: 0.0; --size: 0.15;">
                                            <span class="data"> 15 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 09 AM </th>
                                            <td style="--start: 0.0; --size: 0.26;">
                                            <span class="data"> 26 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 10 AM </th>
                                            <td style="--start: 0.0; --size: 0.39;">
                                            <span class="data"> 39 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 11 AM </th>
                                            <td style="--start: 0.0; --size: 0.45;">
                                            <span class="data"> 45 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 12 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 01 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 02 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 03 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 04 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 05 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 06 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 07 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 08 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 09 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 10 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"> 11 PM </th>
                                            <td style="--start: 0.0; --size: 0.0;">
                                            <span class="data"> 0 </span></td>
                                        </tr>
                                        
                                </table>                          
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>

        <br/>
        <br/>


            <div class="card">
                <div class="card-body">
                    <h5>Historical Analytics</h5>
                    <div class="card">
                    <div class="card-body">
                        <h6>--Insert Chart Here--</h6>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VisitorsToday from './Visuals/VisitorsToday.vue';
import AnsweredChat from './Visuals/AnsweredChat.vue';
import MissedChat from './Visuals/MissedChat.vue';
import ChatsLine from './Visuals/ChatsLine.vue';
import VisitorsLine from './Visuals/VisitorsLine.vue';
import axios from 'axios';
export default {
    props: ["user"],
        data(){
          return{
            arrHour: [],
            arrChat: [],
            arrVisitor: [],
          }
        },
        name:'App',
        components:{
            VisitorsToday,
            AnsweredChat,
            MissedChat,
            ChatsLine,
            VisitorsLine
},
        /*createdChatsLine(){
          const { data } = axios.get("'/api/reports/chats/todays-hourly");
          console.log(data);
          if (data){
              data.forEach(element=>{
                  arrHour.push(element.hour);
                  arrChat.push(element.chat);
              });
              this.renderChart({
                  labels: 'Hours',
                  datasets:[{
                    label: 'Chats',
                    backgroundColor: '#FC2525',
                    data: arrChat
                  }]
              }, {responsive: true, maintainAspectRatio: false})
          }
            else{
                console.log('No data');
            }
        },*/
        createdVisitorsLine(){
          const { data } = axios.get("'/api/reports/sessions/todays-hourly");
          console.log(data);
          if (data){
              data.forEach(element=>{
                  arrHour.push(element.hour);
                  arrChat.push(element.chat);
              });
              this.renderChart({
                  labels: 'Hours',
                  datasets:[{
                    label: 'Chats',
                    backgroundColor: '#FC2525',
                    data: arrChat
                  }]
              }, {responsive: true, maintainAspectRatio: false})
          }
            else{
                console.log('No data');
            }
        }
  }
</script>

