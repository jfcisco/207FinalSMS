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
        <template v-for="(message, index) in messages">
          <!-- Check if the message is just an update -->
          <div class="update" v-if="message.isUpdate" :key="index">
            {{ message.content }}
          </div>

          <!-- If it isn't an update, it's a chat message -->
          <div class="message" v-else 
            :class="{ 'sent-message': message.fromSelf, 'received-message': !message.fromSelf }"
            :key="index"
          >
            <div class="name">
              {{ message.fromSelf ? 'You' : message.clientId }}
              <!-- TODO: Ask Raymond if the sender/client's name can also be passed instead of just the clientId -->
            </div>
            <div class="text">{{ message.content }}</div>
          </div>
        </template>
      </div>

      <!--INPUT MESSAGE BOX-->
      <form class="chatbox-input" @submit.prevent="sendMessage()">
        <input
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
  props: [
    'visitorName'
  ],

  data() {
    return {
      isVisible: true,
      message: '',
      rooms: [],
      messages: [],
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
      this.rooms = rooms;
    });

    // Listen to any sent messages
    socket.on("message", (message) => {
      this.messages.push({
        ...message,
        fromSelf: message.clientId === socket.auth.clientId,
        isUpdate: false,
      });
    });

    // An admin/agent has joined the room
    socket.on("join", (notification) => {
      this.messages.push({
        isUpdate: true,
        content: notification
      });
    });

    // An admin/agent/visitor left the room
    socket.on("user_disconnect", (notification) => {
      this.messages.push({
        isUpdate: true,
        content: notification
      });
    });

    // Log any connect_errors
    socket.on('connect_error', err => {
      console.error(err);
    })
  },

  methods: {
    sendMessage() {
      // Check if message is not blank
      if (this.message === "") return;

      const newMessage = {
        content: this.message,
        roomId: this.rooms[0]._id,
      };

      socket.emit("message", newMessage);

      // Attach some properties we need later
      this.messages.push({
        ...newMessage,
        isUpdate: false,
        fromSelf: true,
      });

      // Clear message input
      this.message = "";
    },
  },
};
</script>