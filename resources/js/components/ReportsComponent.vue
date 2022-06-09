<template>
    <div class="row">
        <!--MAIN SIDE BAR START-->
          <div class="col-lg-1 col-sm-1 mainsidebar">
            <a href="/home">
              <ion-icon class="main-menu-icon" name="mail-outline"></ion-icon>
              <span class="menutitle">Messaging</span>
            </a>
            <a href="/reports" class="activemenu">
              <ion-icon class="main-menu-icon" name="bar-chart-outline"></ion-icon>
              <span class="menutitle">Reporting</span>
            </a>
            <a href="/widgets">
              <ion-icon class="main-menu-icon" name="copy-outline"></ion-icon>
              <span class="menutitle">Widget</span>
            </a>
          </div>
        <!--MAIN SIDE BAR END-->

        <!--reports LISTS-->

        <div class="col-lg-2 col-sm-3 sidebar">
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
                            <p><a href="#chats">Answered and Missed Chats</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <p class="subtitle sidebartitle">Historical Analytics</p>
                <div class="reportblock ">
                    <div class="details">
                        <div class="listHead">
                            <p><a href="#perHour">Per Hour Statistics</a></p>
                        </div>
                    </div>
                </div>
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
                            <p><a href="#chathistory">Chat History</a></p>
                        </div>
                    </div>
                </div>
                <!--<div class="reportblock">
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
                </div>-->

            </div>
        </div>

        <!--TABLES SECTION-->
        <div class="col-lg-9 col-sm-8 reportsSection" style="overflow-y: scroll; overflow-x: hidden;">

            <div class="chat-container">

                <!--LIVE SESSION SECTION-->
                <h1 style="color:#466289">Live Analytics</h1>
                <div class="row reportsection livesession" style="overflow-y: scroll; overflow-x: hidden;">

                    <div class="col-sm-12">
                        <div class="card my-auto shadow" id="livesessions">
                            <div  class="card-body">
                                <div>
                                    <h5 class="card-title" style="color:#627894"><strong>Live Chat Sessions</strong></h5>
                                </div>
                                <table class="table table-striped" style="border: 1px solid;">
                                    <tr>
                                        <th>Socket ID</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Website</th>
                                        <th>Page Title</th>
                                        <th>Chatroom</th>
                                        <th>Duration</th>
                                    </tr>
                                    <tr
                                    v-for="socketReport in socketReports"
                                    :key="socketReport.socketId">
                                        <td>{{ socketReport.socketId }}</td>
                                        <td>{{ socketReport.ipAddress.split(":")[3] || "127.0.0.1" }}</td>
                                        <td>{{ socketReport.browser.slice(socketReport.browser.lastIndexOf(" ")) }}</td>
                                        <td>{{ socketReport.fullUrl }}</td>
                                        <td>{{ socketReport.pageTitle }}</td>
                                        <td><a v-bind:href="'home/?crm=' + socketReport.roomId">{{ socketReport.roomId }}</a></td>
                                        <td>{{ socketReport.time }}</td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row reportsection livesession" style="overflow-y: scroll; overflow-x: hidden;">

                    <div class="col-sm-12">
                        <div class="card my-auto shadow" id="livesessions">
                            <div  class="card-body">
                                <div>
                                    <h5 class="card-title" style="color:#627894"><strong>Live Browsing Sessions</strong></h5>
                                </div>
                                <table class="table table-striped" style="border: 1px solid;">
                                    <tr>
                                        <th>Socket ID</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Website</th>
                                        <th>Page Title</th>
                                        <th>Chatroom</th>
                                        <th>Duration</th>
                                    </tr>
                                    <tr
                                    v-for="socketReport in siteBrowsers"
                                    :key="socketReport.socketId">
                                        <td>{{ socketReport.socketId }}</td>
                                        <td>{{ socketReport.ipAddress.split(":")[3] || "127.0.0.1" }}</td>
                                        <td>{{ socketReport.browser.slice(socketReport.browser.lastIndexOf(" ")) }}</td>
                                        <td>{{ socketReport.fullUrl }}</td>
                                        <td>{{ socketReport.pageTitle }}</td>
                                        <td><a href="#" v-on:click.prevent="joinRoom(socketReport.roomId, socketReport.convoId)">{{ socketReport.roomId }}</a></td>
                                        <td>{{ socketReport.time }}</td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VISITORS AND CHATS -->

                <div class="row reportsection">
                    <!--VISITORS-->
                    <div class="col-sm-6">
                        <div class="card my-auto shadow" id="livevisitors">
                            <div class="card-body">
                                <div class="card-title">
                                <h5 style="color:#627894"><strong>Visitors</strong></h5>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Live</h5>
                                                <p class="card-text">{{ socketReports.length + siteBrowsers.length}}</p>
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
                        <div class="card my-auto shadow" id="chats">
                            <div class="card-body">
                                <div class="card-title"><h5 style="color:#627894"><strong>Chats</strong></h5></div>

                                <div class="row">

                                    <!--CHATS ANSWERED-->
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                            <h5 class="card-title">Answered</h5>
                                            <p class="card-text">{{ answeredChats }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--CHATS ANSWERED-->

                                    <!--CHATS MISSED-->
                                    <div class="col-sm-6">
                                        <div class="card my-auto shadow" id="missedchats">
                                            <div class="card-body">
                                            <h5 class="card-title">Missed</h5>
                                            <p class="card-text">{{ missedChats }}</p>
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
                    <h1 style="color:#466289">Historical Analytics</h1>
                    <!--visitors per hour -->
                    <div class="col-sm-6">
                        <div class="card my-auto shadow" id="perHour">
                            <div class="card-body">
                                <h5 style="color:#627894"><strong>Visitors per hour</strong></h5>

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
                        <div class="card my-auto shadow">
                            <div class="card-body">
                                <h5 style="color:#627894"><strong>Chats per hour</strong></h5>

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
                <div class="row reportsection" id="chatvolume">
                    <div class="col-sm-12">
                        <div class="card my-auto shadow">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title" style="color:#627894"><strong>Chat Volume</strong></h5>

                                    <div class="col-sm-5">
                                    <input type="date" id="start_date_input">
                                    <input type="date" id="end_date_input">
                                    <button @click="getDates">Submit</button>
                                    </div>

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
                        </div>
                    </div>
                </div>
                <!--HISTORICAL ANALYSIS END-->

                <!--CHAT HISTORY START-->
                <div class="row reportsection" id="chathistory">
                        <div class="col-sm-12">
                            <div class="card my-auto shadow">
                                <div class="card-body">
                                    <div>
                                        <h5 class="card-title" style="color:#627894"><strong>Chat History</strong></h5>

                                        <div class="col-sm-5">
                                            <input type="date" id="start_date_input">
                                            <input type="date" id="end_date_input">
                                            <button @click="filterDates">Submit</button>
                                        </div><br/>

                                        <table id="chat-container-table">
                                            <thead>
                                                <tr>
                                                    <th>Socket ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="chathistory-container-body">
                                            </tbody>            
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                    </div>

                </div>
                <!--CHAT HISTORY END-->

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

import axios from 'axios';
import HourlyVisitor from './Visuals/HourlyVisitor.vue';
import HourlyChat from './Visuals/HourlyChat.vue';

export default {
    props: ["user"],
    components:{

    HourlyVisitor,
    HourlyChat
},
    data() {
        return {
            currentUser: this.user,
            socketReports: [],
            siteBrowsers:[],
            answeredChats: 0,
            missedChats: 0,
        };
    },

    computed: {

    },

    created() {
        
        socket.auth = {
            // For admin/agent
            clientId: this.user._id,
            clientName: this.user.name.toString(),
            clientType: "user",
        };

        socket.connect();

        socket.on("report", ({ report }) => {
            if(report.activeConvo){
                this.socketReports.push(report);
            }else{
                this.siteBrowsers.push(report);
            }
            //console.log("live report", report.activeConvo);
        });
        socket.on("report-disconnect", ({ discon }) => {
            //console.log(discon);
            this.socketReports = this.socketReports.filter(reports => reports.socketId != discon);
            this.siteBrowsers = this.siteBrowsers.filter(reports => reports.socketId != discon);
        });
        socket.on("report-answered", ({ answered }) => {
            //console.log(answered);
            if(answered){
                this.answeredChats++;
            }
            
        });     
        socket.on("report-missed", ({ missed }) => {
            //console.log("miss",missed);
            if(missed){this.missedChats++;}
            
        });
        socket.on("report-chatting", ({ chatting }) => {
            //console.log(chatting);
            //let arr = [];
            for(let brow of this.siteBrowsers){
                if(brow.roomId == chatting){
                    this.socketReports.push(brow);
                }
            }

            this.siteBrowsers = this.siteBrowsers.filter(browse => browse.roomId != chatting);
            //this.socketReports.push(arr);
        });                     

        setInterval(()=>{
            this.timeUpdate();
        }, 1000);

        this.generateSessionList();
        this.getAnsweredUnanswered();


    },

    methods: {
        timeUpdate(){
            this.socketReports.forEach(function(report){
                var diff = new Date(new Date() - new Date(report.startAt));
                report.time = diff.toISOString().substr(11, 8);
            });
            this.siteBrowsers.forEach(function(report){
                var diff = new Date(new Date() - new Date(report.startAt));
                report.time = diff.toISOString().substr(11, 8);
            });            
        },  
        joinRoom(roomId, convoId) {
            //taken from chatscomponent

  
            let confirmAction = confirm("Are you sure you want to chat?");

            if (confirmAction) {

                socket.emit("join", { roomId: roomId, name: this.currentUser.name });
                socket.emit("start_convo", {conversationId: convoId}, (error) => {
                    if (error) {
                        console.error(error);
                    }
                    else {
                        //alert("Successfully joined this room");
                        window.location.href = 'home/?cnv='+convoId+'&crm='+roomId;
                    }
                });
                
            }
            
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


        },
        async getChatHistory(start, end) {
            let post = await fetch("api/reports/past-conversations", {
                method: "POST",
                body: {
                    "start_date": start,
                    "end_date": end
                }
            });

            let data = await post.json();

            return data;
        },

        async filterDates(event) {
            let tableElem = document.getElementById("chathistory-container-body");
            tableElem.innerHTML = "";

            let start_date = document.getElementById("start_date_input").value;
            let end_date = document.getElementById("end_date_input").value;
            let data = await this.getChatHistory(start_date, end_date);

            console.log("==========================");
            console.log("start: " + start_date);
            console.log("end: " + end_date);
            console.log(data.data);

            let conversations = response.data.data;

            /*conversations.forEach((conversation) => {
                let reversedKeys = keys(conversation).reverse();
                reversedKeys.forEach((if(key='messages'){continue}{
                    tableElem.innerHTML += `<tr style="border-bottom: 1px solid;">
                    <td>${key}</td>
                    <td>${data.data[key]}</td>
                </tr>`;  
                )
            });*/


        },
        async getMissedChats(){
            //no longer being used
             try {
                const response = await axios.get('/api/reports/chats/missed');
                //console.log("missed", response.data.data)
                return response.data.data;
            } catch (err) {
                console.error(err);
            }           
        },
        async getAnsweredChats(){
            //no longer being used
             try {
                const response = await axios.get('/api/reports/chats/answered');
                //console.log("answered", response.data.data)
                return response.data.data;
            } catch (err) {
                console.error(err);
            }           
        },        
        async getliveVisitors() {
            try {
                const response = await axios.get("/api/reports/sessions/live-visitors");
                //console.log("live visitors", response.data.data)
                return response.data.data;
            } catch (err) {
                console.error(err);
            }
        },
        async generateSessionList() {
            try {
                //console.log("running generateSessionList");
                const results = await this.getliveVisitors();
                 //console.log("getliveVisitors response", results);
                // console.log("convertedResults", convertedResults);
                let active = [];
                let inactive = [];
                results.forEach(function(result){
                    if(result.activeConvo){
                        active.push(result);
                    }else{
                        inactive.push(result)
                    }
                });

                this.socketReports = _.unionBy(
                    this.socketReports,
                    this.convertLiveVisitors(active),
                    (socketReport) => socketReport.socketId,
                );
                this.siteBrowsers = _.unionBy(
                    this.siteBrowsers,
                    this.convertLiveVisitors(inactive),
                    (siteBrowser) => siteBrowser.socketId,
                );                
                //console.log("this.socketReports after api call", this.socketReports)
            } catch (err) {
                console.error(err);
            }
        },
        convertLiveVisitors(liveVisitors){
            return liveVisitors.map(visitor => {
                return{
                    socketId: visitor.socketId,
                    ipAddress: visitor.ipAddress,
                    browser: visitor.browser,
                    roomId: visitor.roomId,
                    fromURL: visitor.fromURL,
                    startAt: visitor.startAt,
                    time: visitor.time,
                    pageTitle: visitor.pageTitle,
                    fullUrl: visitor.fullUrl,
                    activeConvo: visitor.activeConvo,
                    convoId: visitor.convoId
                }
            });

        },

        async getAnsweredUnanswered(){
           
            try {
                const response = await axios.get('/api/reports/chats/missed-answered');
                //console.log("miss-ans", response.data)
                const results =  response.data;
                this.answeredChats = results.answered;
                this.missedChats = results.missed;
            } catch (err) {
                console.error(err);
            }       
        },

    },
};
</script>
