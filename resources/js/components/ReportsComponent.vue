<template>
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-1 mainsidebar">
            <a href="/home"
                ><ion-icon name="mail-outline"></ion-icon
                ><span class="menutitle">Messaging</span></a>
            <a class="activemenu" href="/reports"
                ><ion-icon name="bar-chart-outline"></ion-icon
                ><span class="menutitle">Reporting</span></a>
            <a href="/widgets"
                ><ion-icon name="copy-outline"></ion-icon
                ><span class="menutitle">Widget</span></a>
        </div>

        <!--reports LISTS-->

        <div class="col-lg-2 sidebar">
            <div class="row py-0 mt-0">

                <p class="subtitle">Live Analytics</p>
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#livesessions">Live Sessions</a></p>
                        </div>
                    </div>
                </div>
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#livevisitors">Live Visitors</a></p>
                        </div>
                    </div>
                </div>
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#livechats">Live Chats</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <p class="subtitle sidebartitle">Historical Analytics</p>
                
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#chatvolume">Chat Volume</a></p>
                        </div>
                    </div>
                </div>
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#missedchats">Missed Chats</a></p>
                        </div>
                    </div>
                </div>
                <div class="reportblock">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#offlinemessages">Offline Messages</a></p>
                        </div>
                    </div>
                </div>
                <div class="reportblock ">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#avgchat">Average Chat Duration</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--TABLES SECTION-->
        <div class="col-lg-9 reportsSection" style="overflow-y: scroll; overflow-x: hidden;">

            <div class="chat-container">
                
                <!--LIVE SESSION SECTION-->
                <div class="row reportsection">
                    <h1>Live Analytics</h1>
                
                    <div class="col-sm-12">
                        <div class="card" id="livesessions">
                            <div  class="card-body">
                                <div>
                                    <h5 class="card-title">Live Sessions  </h5>
                                </div>
                                <div
                                    v-for="socketReport in socketReports"
                                    :key="socketReport.socketId">
                                    <div class="card-body">
                                        <span>Socket ID: {{ socketReport.socketId }}</span>
                                        <span>IP Address: {{ socketReport.ipAddress }}</span>
                                        <span>Browser: {{ socketReport.browser.slice(socketReport.browser.lastIndexOf(" ")) }}</span>
                                        <span>Website: {{ socketReport.fromURL }}</span>
                                        <span>Link to Chat: {{ socketReport.roomId }}</span>
                                        <span>Duration: {{ socketReport.time }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- VISITORS AND CHATS -->

                <div class="row reportsection">
                    <!--VISITORS-->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                <h5>Visitors</h5>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card" id="livevisitors">
                                            <div class="card-body">
                                                <h5 class="card-title">Live</h5>
                                                <p class="card-text">{{ socketReports.length }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                    <!--VISITORS END-->

                    <!--CHATS-->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title"><h5>Chats</h5></div>
                                
                                <div class="row">

                                    <!--CHATS ANSWERED-->
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body"> 
                                            <h5 class="card-title">Answered</h5>
                                            <p class="card-text"><AnsweredChat></AnsweredChat></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--CHATS ANSWERED-->

                                    <!--CHATS MISSED-->
                                    <div class="col-sm-6">
                                        <div class="card" id="missedchats">
                                            <div class="card-body">
                                            <h5 class="card-title">Missed</h5>
                                            <p class="card-text"><MissedChat></MissedChat></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--CHATS MISSED-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--VISITORS AND CHATS END-->

                <!--VISITORS PER HOUR AND CHATS PER HOUR START-->
                <div class="row reportsection">

                    <!--visitors per hour -->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Visitors per hour</h5>
                                
                                <div class="card">
                                <div class="card-body">
                                    <HourlyVisitor></HourlyVisitor>
                                </div>
                                </div>
                        
                            </div>
                        </div>
                    </div>
                    <!--visitors per hour -->

                    <!--chats per hour -->
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Chats per hour</h5>
                                
                                <div class="card">
                                    <div class="card-body">
                                        <HourlyChat></HourlyChat>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--chats per hour -->
                </div>
                <!--VISITORS PER HOUR AND CHATS PER HOUR END-->


                <!--HISTORICAL ANALYSIS START-->
                <div class="row reportsection">

                    <h1>Historical Analytics</h1>
                    <p id="chatvolume">Chat Volume</p>

                        <div class="col-sm-5">
                        <input type="date" id="start_date_input">
                        <input type="date" id="end_date_input">
                        <button @click="getDates">Submit</button>
                        </div>

                        <div class="col-sm-12">
                        <table id="chat-container-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Chat Volume</th>
                                </tr>
                            </thead>
                            <tbody id="chat-container-body">
                            </tbody>
                        </table>
                        </div>
                
                </div>
                <!--HISTORICAL ANALYSIS END-->

            </div>
        </div>

    </div>
</template>


<script>

const client = new cj.ClientJS();

const socket = io(process.env.MIX_SOCKET_SERVER, {
    secure: true,
    autoConnect: false,
});

import VisitorsToday from './Visuals/VisitorsToday.vue';
import AnsweredChat from './Visuals/AnsweredChat.vue';
import MissedChat from './Visuals/MissedChat.vue';
import axios from 'axios';
import HourlyVisitor from './Visuals/HourlyVisitor.vue';
import HourlyChat from './Visuals/HourlyChat.vue';

export default {
    props: ["user"],
    components:{
    VisitorsToday,
    AnsweredChat,
    MissedChat,
    HourlyVisitor,
    HourlyChat
},
    data() {
        return {
            currentUser: this.user,
            socketReports: [],
        };
    },

    computed: {

    },

    created() {

        socket.auth = {
            // For admin/agent
            clientId: this.user._id,
            clientName: "agentako",
            clientType: "user",
        };

        socket.connect();

        socket.on("report", ({ report }) => {
            this.socketReports.push(report);
        });
        socket.on("report-disconnect", ({ discon }) => {
            //console.log(discon);
            this.socketReports = this.socketReports.filter(reports => reports.socketId != discon);
        });

        setInterval(()=>{
            this.timeUpdate();
        }, 1000);

    },

    methods: {
        timeUpdate(){
            this.socketReports.forEach(function(report){
                var diff = new Date(new Date() - new Date(report.startAt));
                report.time = diff.toISOString().substr(11, 8);
            });
        },
        async getChatVolume(start, end) {
            let post = await fetch("/api/reports/chats/daily", {
                method: "POST",
                body: {
                    "start_date": start,
                    "end_date": end
                }
            });

            let data = await post.json();

            return data;
        },

        async getDates(event) {
            let tableElem = document.getElementById("chat-container-body");
            tableElem.innerHTML = "";

            let start_date = document.getElementById("start_date_input").value;
            let end_date = document.getElementById("end_date_input").value;
            let data = await this.getChatVolume(start_date, end_date);

            console.log("==========================");
            console.log("start: " + start_date);
            console.log("end: " + end_date);
            console.log(data.data);

            let reversedKeys = Object.keys(data.data).reverse();

            reversedKeys.forEach((key) => {
                tableElem.innerHTML += `<tr style="border-bottom: 1px solid;">
                    <td>${key}</td>
                    <td>${data.data[key]}</td>
                </tr>`;
            });


        }


        // selectRoom: function (roomId) {
        //     this.activeRoom = roomId;
        //     // this.scrollToChatBottom();
        // },
    },
};
</script>
