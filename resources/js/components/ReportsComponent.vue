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
                <p class="subtitle">Chat Volume</p>



                <div class="block">
                    <div class="details">
                        <div class="listHead">
                            <p>Live Visitors</p>
                        </div>
                        <div class="listHead">
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
                        <div class="listHead">
                            <p>Chat Volume</p>
                        </div>
                        <div class="listHead">
                            <p>Missed Chats</p>
                        </div>                        
                        <div class="listHead">
                            <p>Offline Messages</p>
                        </div>
                        <div class="listHead">
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

        </div>
    </div>
</template>

<style scoped>
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
            currentUser: this.user,
            socketReports: [],
        };
    },

    computed: {
        //
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
            console.log("report => ", report);
            // this.chatRooms = rooms;
            // console.log("chatRooms => ", this.chatRooms);
            this.socketReports.push(report);
            console.log("look here", this.socketReports);
        });
    },

    methods: {

        // selectRoom: function (roomId) {
        //     this.activeRoom = roomId;
        //     // this.scrollToChatBottom();
        // },
    },
};
</script>
