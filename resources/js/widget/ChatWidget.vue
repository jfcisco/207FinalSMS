<template>
    <div>
        <!-- HEADER -->
        <h2>
            <ul>
                <li>Conversation</li>
                <!-- END CHAT BUTTON -->
                <!-- TO DO: Edit behavior of the button to End Chat -->
                <li style="float:right" v-if="!chatEnded">
                    <button id=exit-chat @click="endConversation()"><i class="material-icons" style="font-size:20px;">logout</i><span class="tooltiptext">End chat</span></button>
                </li>
            </ul>
        </h2>

        <!-- END CHAT SCREEN -->
        <div class="end-chat content" v-if="chatEnded">
            <p>You have ended the chat conversation.</p>
        </div>

        <!--CHAT MESSAGES BOX-->
        <div v-else id="messages" class="content">
            <div class="messages" v-chat-scroll>
                <!--CHAT MESSAGE/CHAT UPDATE-->
                <template v-for="(message, index) in room.messages">
                    <!-- Check if the message is just an update -->
                    <div class="update" v-if="message.isUpdate"
                    v-show="!message.isWhisper"
                    :key="index">
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
                        v-show="!message.isWhisper"
                        :key="index"
                    >
                        <div class="name">
                            {{ message.fromSelf ? "You" : message.senderName }}
                            <div class="text" v-if="!message.content.startsWith('files/')">
                                {{ message.content }}
                            </div>
                            <div class ="text" v-else><a :href="'<?php echo $baseUrl; ?>/'+message.content" target="_blank">{{ message.content.substring(6) }}</a></div>
                        </div>
                    </div>
                </template>
            </div>
            <!--INPUT MESSAGE BOX-->
            <div class="inputs">
                <div>
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
                                @keydown="sendTypingEvent"
                            />
                        </form>
                </div>
                <FileUploadWidget
                v-on:upload-success="handleAttachmentUpload"
                ></FileUploadWidget>
            </div>
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
    .inputs {
        display: grid;
        grid-template-columns: 90% 10%;
        margin-bottom: 5px;
        align-content: space-around;
    }
    .end-chat {
        text-align: center;
    }

</style>

<script>
import io from "socket.io-client";
import cj from "clientjs";
import FileUploadWidget from "./FileUploadWidget.vue";

// Setup client.js for device fingerprinting
const client = new cj.ClientJS();
var notifCount = 0;
var origTitle = document.title;


// Setup Socket.IO connection
const socket = io(process.env.MIX_SOCKET_SERVER, {
    secure: true,
    autoConnect: false,
});

export default {
    props: ["visitorName"],

        components: {
            FileUploadWidget,
        },

    data() {
        return {
            chatEnded: false,
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
                room.messages = room.messages
                    .filter(msg => !msg.isWhisper)
                    .map((msg) =>
                        this.attachMessageProperties(room, msg)
                    );

                this.room = room;

                this.focusOnMessageInput();
                console.log("room messages", room.messages);
            }
        });

        // Listen to any sent messages
        socket.on("message", (message) => {
            var previousChatMessage;
            const chatMessage = this.attachMessageProperties(this.room, message);
            this.room.messages.push(chatMessage);
            
            // Show notifications in the document's title tag
            if (!document.hasFocus()) {
                if (chatMessage != previousChatMessage) {
                    notifCount++;
                    this.showNotifications(notifCount);
                }
            }
            previousChatMessage = chatMessage;
            
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
                conversationId: this.room.conversationId,
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
            socket.emit("end_chat", this.room.conversationId);
            socket.disconnect();
            this.chatEnded = true;
        },

        // Gets code when we successfully upload a file
        handleAttachmentUpload(attachmentUrl) {

            // axios post to laravel app - to upload the file (in the file upload widget)
            const newMessage = {
                content: attachmentUrl,
                roomId: this.room._id,
                conversationId: this.room.conversationId,
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

        sendTypingEvent() {
            const typingData = {
                content: this.message,
                roomId: this.room._id,
                conversationId: this.room.conversationId,
            };

            // console.log("Send typing data: ", typingData);
            socket.emit("typing", typingData);
        },

        showNotifications(count) {
        const pattern = /^\(\d+\)/;

        if (count === 0 || pattern.test(document.title)) {
            document.title = document.title.replace(pattern, count === 0 ? "" : "(" + count + ") ");
        } else {
            document.title = "(" + count + ") New message(s) received!";
        }

        setInterval(checkFocus, 100);
        function checkFocus() {
            if (document.hasFocus()) {
            document.title = origTitle;
            notifCount = 0;
            }
        };
        return;
        }

    },
};
</script>
