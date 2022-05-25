<template>
    <div>
        <!-- HEADER -->
        <h2>
            <ul>
                <li>Conversation</li>
                <!-- END CHAT BUTTON -->
                <!-- TO DO: Edit behavior of the button to End Chat -->
                <li style="float:right"><button id=exit-chat onClick="endConversation()"><i class="material-icons" style="font-size:20px;">logout</i><span class="tooltiptext">End chat</span></button>
                </li>
            </ul>
        </h2>

        <!--CHAT MESSAGES BOX-->
        <div id="messages" class="content">
            <div class="messages" v-chat-scroll>
                <!--CHAT MESSAGE/CHAT UPDATE-->
                <template v-for="(message, index) in room.messages">
                    <!-- Check if the message is just an update -->
                    <div class="update" v-if="message.isUpdate" :key="index">
                        {{ message.content }}
                    </div>

                    <!-- If it isn't an update, it's a chat message -->
                    <div
                        class="message"
                        v-else
                        :class="{
                            'sent-message': message.fromSelf,
                            'received-message': !message.fromSelf,
                        }"
                        :key="index"
                    >
                        <div class="name">
                            {{ message.fromSelf ? "You" : message.senderName }}
                            <div class="text">{{ message.content }}</div>
                        </div>
                    </div>
                </template>
            </div>

            <!--INPUT MESSAGE BOX-->
            <form @submit.prevent="sendMessage()">
                <input
                    ref="messageInput"
                    :disabled="!room._id"
                    v-model="message"
                    type="text"
                    name="message"
                    placeholder="Enter your message..."
                    class="form-control"
                    autocomplete="off"
                />
            </form>
        </div>
    </div>
</template>

<style scoped>
/* CSS rules can be added here */

h2 ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
h2 ul li{
  display: inline;
}
h2 ul li button{
  background-color: #466289;
  border: none;
  color: white;
}
h2 ul li button:hover{
  background-color: lightgray;
  cursor: pointer;
  color:#FA6121;
}
h2 ul li button .tooltiptext{
  visibility: hidden;
  width: 65px;
  background-color: lightgrey;
  color: black;
  text-align: center;
  border-radius: 2px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
  top: 10%;
  left: 80%;
}
h2 ul li button:hover .tooltiptext{
  visibility: visible;
}
.content .messages {
    overflow-y: scroll;
    max-height: 320px;
    margin-bottom: 5px;
    background-color: #ffffff;
    font-family: "Raleway", sans-serif;
}
.content .messages .message {
    display: flex;
    padding: 10px;
}
.content .messages .message > div {
    max-width: 70%;
    background: #ffffff;
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
    font-size: 10px;
    padding-bottom: 5px;
    color: #fa6121;
    font-weight: bold;
}
.content .messages .message .text {
    word-wrap: break-word;
    font-size: 14px;
    color: black;
    font-weight: normal;
}
.content .messages .update {
    text-align: center;
    padding: 10px;
    font-style: italic;
}
</style>

<script>
import io from "socket.io-client";
import cj from "clientjs";

// Setup client.js for device fingerprinting
const client = new cj.ClientJS();

// Setup Socket.IO connection
const socket = io("https://sms-ws.ml:3000", {
    secure: true,
    autoConnect: false,
});

export default {
    props: ["visitorName"],

    data() {
        return {
            isVisible: true,
            message: "",
            room: {
                // Room default values
                _id: null,
                messages: [],
                members: []
            },
        };
    },

    created() {
        // Setup auth for connection
        socket.auth = {
            clientId: `${client.getFingerprint()}`,
            clientType: "visitor",
            clientName: this.visitorName,
            widgetId: "widget1",
        };

        socket.connect();

        socket.on("rooms", ({ rooms }) => {
            console.log("socket.on rooms")
            console.log("rooms=>", rooms)
            if (!this.room._id) {
                // Get the room and its accompanying information
                let room = rooms[0];

                // Process the room's messages, attaching additional properties we need
                room.messages = room.messages.map((msg) =>
                    this.attachMessageProperties(room, msg)
                );

                this.room = room;

                this.focusOnMessageInput();
                console.log("room messages", room.messages);
            }
        });

        // Listen to any sent messages
        socket.on("message", (message) => {
            const chatMessage = this.attachMessageProperties(this.room, message);
            this.room.messages.push(chatMessage);
        });

        // Received notification that an admin/agent has joined the room
        socket.on("join", (notification) => {
            const update = this.attachUpdateProperties(notification);
            this.room.messages.push(update);
        });

        // Received information about user that joined
        socket.on("user_joined", (user) => {
            if (user.roomId === this.room._id) {
                this.room.members.push(user);
            }
        });

        // // An admin/agent/visitor left the room
        // socket.on("user_disconnect", (notification) => {
        //     const update = this.attachUpdateProperties(notification);
        //     this.room.messages.push(update);
        // });

        // Log any connect_errors
        socket.on("connect_error", (err) => {
            console.error(err);
        });
    },

    methods: {
        sendMessage() {
            // Check if message is not blank
            if (this.message === "") return;

            const newMessage = {
                content: this.message,
                roomId: this.room._id,
            };
            console.log("room._id=> ", this.room._id);

            socket.emit("message", newMessage);

            // Attach some properties we need later
            this.room.messages.push({
                ...newMessage,
                isUpdate: false,
                fromSelf: true,
            });

            // Clear message input
            this.message = "";
        },

        // Helper method to attach properties to a `message` instance so that we can display it properly
        attachMessageProperties(room, message) {
            return {
                ...message,
                senderName: this.findMemberNameByClientId(room, message.clientId),
                isUpdate: false,
                fromSelf: message.clientId === socket.auth.clientId,
            };
        },

        // Helper method to attach properties to an `update` notification so that we can display it properly
        attachUpdateProperties(notification) {
            return {
                isUpdate: true,
                content: notification,
            };
        },

        findMemberNameByClientId(room, clientId) {
            for (let member of room.members) {
                if (member.clientId === clientId) {
                    return member.clientName;
                }
            }

            return "Unknown";
        },

        focusOnMessageInput() {
            this.$nextTick(() => {
                const messageInputRef = this.$refs.messageInput;
                messageInputRef.focus();
            });
        },
        endConversation() {
            //TO DO: Add code to end conversation
        },
    },
};
</script>
