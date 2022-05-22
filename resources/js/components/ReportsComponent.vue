hello

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


        <!--MESSAGE LISTS-->

        <div class="col py-4 px-5" style="background-color: #627894; color: whitesmoke;">
            <!--INCOMING SESSIONS-->


            <!--ACTIVE SESSIONS-->

            <div class="row">
                <p class="menutitle">Active Sessions</p>

                <!--ACTIVE CHAT BLOCK-->
                <div
                    :class="{
                        block: true,
                        active: chatroom._id == activeRoom,
                    }"
                    v-for="chatroom in chatrooms"
                    :key="chatroom._id"
                >
                    <div
                        class="details"
                        v-on:click="selectRoom(chatroom._id)"
                        v-bind:id="chatroom._id"
                    >
                        <div class="listHead">
                            <p>{{ chatroom._id }}</p>

                        </div>


                    </div>
                </div>
                <!--ACTIVE CHAT BLOCK-->
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
            newMessage: "",
            users: [],
            chatrooms: [],
            activeRoom: "",
        };
    },

    computed: {
        activeRoomDetails: function () {
            const activeRoomResult = this.chatrooms.filter(
                (room) => room._id == this.activeRoom
            );

            if (activeRoomResult.length === 0) {
                return null;
            } else {
                return activeRoomResult[0];
            }
        },
    },

    created() {

        socket.auth = {
            // For admin/agent
            clientId: this.user._id,
            clientName: "agentako",
            clientType: "user",
        };

        socket.connect();

        socket.on("rooms", ({ rooms }) => {
            console.log("rooms => ", rooms);
            // this.chatRooms = rooms;
            // console.log("chatRooms => ", this.chatRooms);
            this.chatrooms = _.unionBy(
                rooms,
                this.chatrooms,
                (room) => room._id
            );
            this.chatrooms.forEach((room) => {
                socket.emit("join", { roomId: room._id, name: this.user.name });
                // console.log("room", room._id, "members", room.members);
            });
            console.log("look here", this.chatrooms);
        });

        socket.on("message", (message) => {
            // console.log("received new message", message);
            let found = this.getTargetRoomIndex(message.roomId);

            this.chatrooms[found].messages.push(message);

            console.log("chatrooms", this.chatrooms);
            // console.log(
            //     this.chatrooms.forEach((chatroom) =>
            //         chatroom.messages.forEach((msg) =>
            //             console.log(
            //                 "msg content: ",
            //                 msg.content,
            //                 "clientId",
            //                 msg.clientId,
            //                 "clientType",
            //                 msg.clientType,
            //                 "roomId",
            //                 msg.roomId
            //             )
            //         )
            //     )
            // );
        });
    },

    methods: {

        sendMessage() {
            const message = {
                roomId: this.activeRoom,
                content: this.newMessage,
            };

            // Send to socket server
            socket.emit("message", message);

            // Add to UI
            let found = this.getTargetRoomIndex(this.activeRoom);
            this.chatrooms[found].messages.push({
                ...message,
                clientId: socket.auth.clientId,
                _id: "",
            });

            this.newMessage = "";
        },

        selectRoom: function (roomId) {
            this.activeRoom = roomId;
            // this.scrollToChatBottom();
        },
 
        getTargetRoomIndex(targetRoom) {
            let found = null;
            for (const indx in this.chatrooms) {
                if (this.chatrooms[indx]._id == targetRoom) {
                    found = indx;
                }
            }
            return found;
        },

        // Given a message object, converts it to a string resembling a chat message
        // This is used for the chat rooms list message preview
        convertMessageObjectToString(message) {
            if (!message) return "";

            if (message.attachment_path) {
                return `${message.user.first_name || ""} ${
                    message.user.last_name || ""
                } sent an attachment.`;
            }

            return `${message.user.first_name || ""} ${
                message.user.last_name || ""
            }: ${message.message}`;
        },
    },
};
</script>
