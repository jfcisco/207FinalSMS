<template>
    <div>
        <!-- TO DO: Styling for the End Conversation button. -->
        <h2>
            Conversation
            <!--<button id=exit-chat onClick="endConversation()"><i class="material-icons" style="font-size:20px;">logout</i></button>-->
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
                            {{ message.fromSelf ? "You" : message.clientId }}
                            <!-- TODO: Ask Raymond if the sender/client's name can also be passed instead of just the clientId -->
                        </div>
                        <div class="text">{{ message.content }}</div>
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
    font-size: 12px;
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
import io from "socket.io-client";
import cj from "clientjs";

// Setup client.js for device fingerprinting
const client = new cj.ClientJS();

// Setup Socket.IO connection
const socket = io("http://sms.pastebook.social:3000", {
    // secure: true,
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
            },
        };
    },

    created() {
        // Setup auth for connection
        socket.auth = {
            clientId: client.getFingerprint(),
            clientType: "visitor",
            clientName: this.$props.visitorName,
            widgetId: "widget1",
        };

        socket.connect();

        socket.on("rooms", ({ rooms }) => {
            if (!this.room._id) {
                // Get the room and its accompanying information
                let room = rooms[0];

                // Process the room's messages, attaching additional properties we need
                room.messages = room.messages.map((msg) =>
                    this.attachMessageProperties(msg)
                );
                this.room = room;

                this.focusOnMessageInput();
            }
        });

        // Listen to any sent messages
        socket.on("message", (message) => {
            const chatMessage = this.attachMessageProperties(message);
            this.room.messages.push(chatMessage);
        });

        // An admin/agent has joined the room
        socket.on("join", (notification) => {
            const update = this.attachUpdateProperties(notification);
            this.room.messages.push(update);
        });

        // An admin/agent/visitor left the room
        socket.on("user_disconnect", (notification) => {
            const update = this.attachUpdateProperties(notification);
            this.room.messages.push(update);
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
            };

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
        attachMessageProperties(message) {
            return {
                ...message,
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

        focusOnMessageInput() {
            this.$nextTick(() => {
                const messageInputRef = this.$refs.messageInput;
                messageInputRef.focus();
            });
        },
    },
};
</script>
