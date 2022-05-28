<template>
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-1 mainsidebar">
            <a href="/home"
                ><ion-icon name="mail-outline"></ion-icon
                ><span class="menutitle">Messaging</span></a
            >
            <a class="activemenu" href="/reports"
                ><ion-icon name="bar-chart-outline"></ion-icon
                ><span class="menutitle">Reporting</span></a
            >
            <a href="/widgets"
                ><ion-icon name="copy-outline"></ion-icon
                ><span class="menutitle">Widget</span></a
            >
        </div>

        <!--reports LISTS-->

        <div class="col-lg-2 sidebar" style="overflow-y: auto">


            <div class="row py-0 mt-0">
                <p class="subtitle">Live Analytics</p>



                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Live Sessions</p>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Live Visitors</p>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Live Chats</p>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row">
                <p class="subtitle sidebartitle">Historical Analytics</p>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Chat Volume</p>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Missed Chats</p>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Offline Messages</p>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Average Chat Duration</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--CHAT DISPLAY-->
        <div class="col mainchat overflow-auto">


            <div class="chat-container">
                <div class="row">
                <h1>Live Analytics</h1>
                <div class="col-sm-15">
                    <div class="card">
                        <div  class="card-body">
                            <div><h5 class="card-title">Live Session  </h5></div>
                            <div
                                v-for="socketReport in socketReports"
                                :key="socketReport.socketId"
                            >
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
                          <p class="card-text">{{ socketReports.length }}</p>
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
                                <!-- <table style="border: 1px solid gray;"><thead style="border: px solid gray; padding: 0px 5px 0px 5px"><th style="padding: 0px 5px 0px 5px;">Time (hour)</th><th style="padding: 0px 5px 0px 5px;">Number of Visitors</th></thead><HourlyVisitor></HourlyVisitor></table> -->
                                <HourlyVisitor></HourlyVisitor>
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
                                   <!-- <table style="border: 1px solid gray;"><thead style="border: px solid gray; padding: 0px 5px 0px 5px"><th style="padding: 0px 5px 0px 5px;">Time (hour)</th><th style="padding: 0px 5px 0px 5px;">Number of Chats</th></thead><HourlyChat></HourlyChat></table> -->
                                   <HourlyChat></HourlyChat>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>


        <br/>
        <br/>

                <h1>Historical Analytics</h1>
                <p>Chat Volume</p>
                <input type="date" id="start_date_input">
                <input type="date" id="end_date_input">
                <button @click="getDates">Submit</button>
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
</template>

<style scoped>
/* HACK: Temporary fix, remove when layout is okay */
.row > * {
    max-height: 90vh !important;
}

/* .attachment {
    max-width: 15rem;
} */
.content .messages {
    overflow-y: scroll;
    max-height: 320px;
    margin-bottom: 5px;
    /* background-color: #ffffff; */
    font-family: "Raleway", sans-serif;
}
.content .messages .message {
    display: flex;
    padding: 10px;
}
.content .messages .message > div {
    max-width: 70%;
    /* background-color: rgb(150, 145, 145); */
    background-color: rgb(226, 219, 219);
    font-weight: 500;
    box-shadow: 0px 0px 20px 5px rgba(0, 0, 0, 0.05);
    padding: 10px;
}
.content .messages .message.sent-message {
    justify-content: flex-end;
}
.content .messages .message.received-message {
    justify-content: flex-start;
}
.content .messages .message .name {
    font-size: 12px;
    font-weight: 450;
    color: #fa6121;
}
.content .messages .message .text {
    word-wrap: break-word;
}
.content .messages .update {
    text-align: center;
    padding: 10px;
    font-style: italic;
}

.chat-container {
    padding: 10px;
    width: 100%;
    height: auto;
    margin-bottom: 1cm;
}

#chat-container-table {
    width: 100%;
    margin-top: 1cm;
}

#chat-container-table thead {
    height: 40px;
    background-color: #F7F7F9;
}

#chat-container-table tbody tr {
    border-bottom: 1px solid;
}
</style>

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
