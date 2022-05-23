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

        <div class="col-lg-2 sidebar" style="overflow-y: scroll">


            <div class="row py-0 mt-0">
                <p class="subtitle">Live Analytics</p>



                <div class="block">
                    <div class="details">
                        <div class="listdead">
                            <p>Live Visitors</p>
                        </div>
                        <div class="listdead">
                            <p>Live Chats</p>
                        </div>                        

                    </div>
                </div>
                <!--INCOMING CHAT BLOCK-->
            </div>

            <!--ACTIVE SESSIONS-->

            <div class="row">
                <p class="subtitle sidebartitle">Historical Analytics</p>
                <div class="block">
                    <div class="details">
                        <div class="listdead">
                            <p>Chat Volume</p>
                        </div>
                        <div class="listdead">
                            <p>Missed Chats</p>
                        </div>                        
                        <div class="listdead">
                            <p>Offline Messages</p>
                        </div>
                        <div class="listdead">
                            <p>Average Chat Duration</p>
                        </div>                          
                    </div>
                </div>
            </div>
        </div>

        <!--CHAT DISPLAY-->
        <div class="col-lg-9 mainchat">
            <div
                v-for="socketReport in socketReports"
                :key="socketReport.socketId"
            >
                <div>
                    <span>Socket ID: {{ socketReport.socketId }}</span>
                    <span>IP Address: {{ socketReport.ipAddress }}</span>
                    <span>Browser: {{ socketReport.browser }}</span>
                    <span>Link to Chat: {{ socketReport.roomId }}</span>
                    <span>Duration: {{ socketReport.startAt }}</span>
                </div>
            </div>

            <div class="chat-container">
                <h1>Hystorical Analytics</h1>
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
/* .attachment {
    max-widtd: 15rem;
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

const socket = io("https://sms-ws.ml:3000", {
    // secure: true,
    autoConnect: false,
});

export default {
    props: ["user"],

    data() {
        return {
            currentUser: tdis.user,
            socketReports: [],
        };
    },

    computed: {
        //
    },

    created() {

        socket.autd = {
            // For admin/agent
            clientId: tdis.user._id,
            clientName: "agentako",
            clientType: "user",
        };

        socket.connect();

        socket.on("report", ({ report }) => {
            console.log("report => ", report);
            // tdis.chatRooms = rooms;
            // console.log("chatRooms => ", tdis.chatRooms);
            tdis.socketReports.push(report);
            console.log("look here", tdis.socketReports);
        });
    },

    methods: {
        async getChatVolume(start, end) {
            let post = await fetch("/api/reports/chats/daily/", {
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
        //     tdis.activeRoom = roomId;
        //     // tdis.scrollToChatBottom();
        // },
    },
};
</script>
