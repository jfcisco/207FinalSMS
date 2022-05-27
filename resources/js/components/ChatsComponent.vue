<template>
<div class="row">

  <!--MAIN SIDE BAR START-->
  <div class="col-lg-1 mainsidebar">
    <a class="activemenu" href="/home">
      <ion-icon name="mail-outline"></ion-icon>
      <span class="menutitle">Messaging</span>
    </a>
    <a href="/reports">
      <ion-icon name="bar-chart-outline"></ion-icon>
      <span class="menutitle">Reporting</span>
    </a>
    <a href="/widgets">
      <ion-icon name="copy-outline"></ion-icon>
      <span class="menutitle">Widget</span>
    </a>
  </div>
  <!--MAIN SIDE BAR END-->

  <!--ROOM LISTS START-->
  <div class="col-lg-2 sidebar" style="overflow-y: scroll">

    <!--INCOMING SESSIONS SECTION START-->

    <div class="row py-0 mt-0">
      <p class="subtitle">Incoming Sessions</p>

      <!--INCOMING CHAT BLOCK START-->
      <!-- <div
        class="block incoming"
        v-for="chatroom in chatrooms"
        :key="chatroom._id"
      > -->
      <div
        class="block incoming"
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
          incoming: chatroom.members.length === 1
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length === 1"
        :key="chatroom._id"
      >
        <div
          class="details"
          v-on:click="selectIncomingRoom(chatroom._id)"
          v-bind:id="chatroom._id"
        >
          <!--room id/username section-->
          <!-- start here -->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>
          <!-- <div class="listHead">
            <p>room._id: {{ chatroom._id }}</p>
          </div> -->
          <!--room id/username section-->
          <!-- The message last sent to the room -->
          <div class="message_p">
            <p>
              {{ getLastMsgAndSender(chatroom) }}
            </p>
          </div>
          <!-- The message last sent to the room -->

        </div>
      </div>
      <!--INCOMING CHAT BLOCK END-->

    </div>
    <!--INCOMING SESSIONS SECTION END-->

    <!--ACTIVE SESSIONS START-->
    <div class="row">

      <p class="subtitle sidebartitle">Active Sessions</p>

      <!--ACTIVE CHAT BLOCK START-->
      <div
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length > 1"
        :key="chatroom._id"
      >

        <div
          class="details"
          v-on:click="selectRoom(chatroom._id)"
          v-bind:id="chatroom._id"
        >

          <!--room id/username section-->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>
          <!-- delete in the future -->
          <!-- <div class="listHead">
            <p>room._id: {{ chatroom._id }}</p>
          </div> -->
          <!-- delete in the future -->

          <!-- assigned room users (Admin/Agent) -->
          <div class="listHead">
            <p style="font-weight: 500; font-style: italic;">
              Assigned: {{ getAssignedToRoom(chatroom) }}
            </p>
          </div>
          <!-- assigned room users (Admin/Agent) -->

          <!-- The message last sent to the room -->
          <div class="message_p">
            <p>
             {{ getLastMsgAndSender(chatroom) }}
            </p>
          </div>
        </div>

      </div>
      <!--ACTIVE CHAT BLOCK END-->

    </div>
  </div>
  <!--ROOM LISTS END-->

  <!--MAIN CHAT WINDOW START-->

  <div class="col-lg-9 mainchat">

    <!--MAIN INPUT MESSAGE BOX START-->

    <div class="chatbox_input" id="message_main" style="display:flex">
      <ion-icon class="whisper" name="volume-high-outline" id="headerToggle1" onclick="toggleheaderleft()"></ion-icon>
      <input
          @keyup.enter="sendMessage"
          v-model="message"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"/>
    </div>
    <!--MAIN INPUT MESSAGE BOX END-->

    <!--WHISPER INPUT MESSAGE BOX START-->
    <div class="chatbox_input" id="whisper" style="display:none">
      <ion-icon class="whisper2" name="volume-mute-outline" id="headerToggle2" onclick="toggleheaderleft()"></ion-icon>
      <input
          @keyup.enter="sendMessage"
          v-model="message"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"/>
    </div>
    <!--WHISPER INPUT MESSAGE BOX END-->

    <!-- CHAT WINDOW START -->
    <div
      class="mainchat2"
      v-for="chatroom in chatrooms"
      :chatroom="chatroom"
      v-show="chatroom._id == activeRoom"
      :key="chatroom._id">

      <div
        class="card-body chatmessages roomMessages"
        v-bind:id="'messages_room' + chatroom._id">

          <div>
            <h4>{{ chatroom.members[0].clientName }}</h4>
          </div>

          <div class="mb-3">
            <h5 style="font-weight: 500; font-style: italic;">
              Assigned: {{ getAssignedToRoom(chatroom) }}
            </h5>
          </div>

          <!--CHATBOX START-->
          <div class="chatboxfix" ref="chatWindow" v-chat-scroll style="overflow-y: scroll; overflow-x: hidden;">
            <ul class="list-unstyled">

                <!-- CHAT MESSAGE LINE START -->
                <li
                  v-for="(message, index) in chatroom.messages"
                  :message="message"
                  :key="index">
                    <div
                      v-if="message.content.length > 0"

                      :class="{
                        sentmessage: message.clientId == user._id,
                        receivedmessage: message.clientId !== user._id,
                        whisper_text: message.isWhisper
                      }"
                    >
                        <!-- <b>{{ message.clientId === user._id ? "You: " : `${chatroom.members[0].clientName}: ` }}</b> -->
                        <!-- <b>
                          {{
                            message.clientId === user._id ? "You: " :
                              `${chatroom.members
                              .filter(member => member.clientId === message.clientId)
                              [0].clientName}: `
                          }}
                        </b> -->
                        <b>
                          {{ getMsgSender(message, chatroom) }}:
                        </b>
                        <p>&nbsp;{{message.content }}</p>
                    </div>
                </li>
                <!-- CHAT MESSAGE LINE END -->
            </ul>
          </div>
          <!--CHATBOX END-->

          <!-- JOIN FEATURE AVAILABLE ONLY IF CURRENT USER IS NOT ALREADY ASSIGNED -->
          <button
            class="joinbutton"
            id="join-btn"
            style="display: flex"
            v-if="chatroom.members.filter(member => member.clientId === currentUser._id).length === 0"
            v-on:click="joinRoom(chatroom._id)">
            <span class="joinroom">Click to join room</span>
          </button>

      </div>
    </div>

  </div>
  <!--MAIN CHAT WINDOW END-->

</div> <!--ROW END-->
</template>

<script>
import FileUploadComponent from "./FileUploadComponent.vue";
import ProfileUpdateComponent from "./ProfileUpdateComponent.vue";
import axios from 'axios';

const client = new cj.ClientJS();

const socket = io("https://sms-ws.ml:3000", {
    secure: true,
    autoConnect: false,
});

export default {
  props: ["user"],

  components: {
    FileUploadComponent,
    ProfileUpdateComponent,
  },

  data() {
    return {
      currentUser: this.user,
      message: "",
      users: [],
      chatrooms: [],
      activeRoom: "",
    };
  },

  created() {

    socket.auth = {
      // // For visitors
      // clientId: client.getFingerprint(),
      // clientType: "visitor",
      // clientName: "bisita",
      // widgetId: "widget1",

      // For admin/agent
      clientId: this.user._id,
      clientName: `${this.currentUser.name}`,
      clientType: "user",
    };

    socket.connect();

    // get all rooms from mongodb
    this.generateChatroomsList();

    socket.on("rooms", ({ rooms }) => {
      // console.log("running socket.on rooms")
      // console.log("socket.on rooms");
      // console.log("rooms full data=> ", rooms);
      // console.log("rooms mod data=> ", rooms.map(room => ({ 'room._id': room._id, 'room.members.length': room.members.length, 'room.messages': room.messages })));
      this.chatrooms = _.unionBy(
        rooms,
        this.chatrooms,
        (room) => room._id,
      );
      console.log("this.chatrooms on socket.on 'rooms'", this.chatrooms);
    });

    socket.on("message", (message) => {
      // console.log("message received", message);
      let found = this.getTargetRoomIndex(message.roomId);
      this.chatrooms[found].messages.push(message);
    });

    // add test data for incoming room | only 1 member of room w/ clientType: visitor
    // this.chatrooms.push({
    //   _id: "54321",
    //   members: [
    //     {clientId: "1234", clientName: "test-incoming-visitor", clientType: "visitor"}
    //   ],
    //   messages: [
    //     {_id: "123", clientId: "1234", clientType: "visitor", content: "test-msg", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //     {_id: "1234", clientId: "1234", clientType: "visitor", content: "test-msg2", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //     {_id: "12345", clientId: "1234", clientType: "visitor", content: "test-msg3", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //     {_id: "123456", clientId: "1234", clientType: "visitor", content: "test-msg4", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //     {_id: "1234567", clientId: "1234", clientType: "visitor", content: "test-msg5", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //     {_id: "12345678", clientId: "1234", clientType: "visitor", content: "test-msg6", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
    //   ],
    // })

    // console.log("chatRooms => ", this.chatrooms.map(room => ({ 'chatroom._id': room._id, 'chatroom.members.length': room.members.length, 'chatroom.messages': room.messages })));
    // console.log("currentUser", this.currentUser)
  },

  methods: {
    // API CALLS TO MONGODB
    async getAllRooms() {
      try {
        const response = await axios.get("/api/rooms");
        // console.log("response GET api/rooms", response.data.data.map(
        //   item => ({ "roomId": item.id, "members": item.members.map(i => `${i.name} clientId(${i.id})`), "messages": item.messages })));
        // console.log("look here", response.data.data)
        return response.data.data;
      } catch (err) {
        console.error(err);
      }
    },
    // UTILITY FUNCTIONS
    async generateChatroomsList() {
      try {
        console.log("running generateChatroomsList");
        const results = await this.getAllRooms();
        // console.log("api call response", results);
        // console.log("convertedResults", convertedResults);

        this.chatrooms = _.unionBy(
          this.chatrooms,
          this.convertSchemaOfRoomsResponse(results),
          (room) => room._id,
        );
        console.log("this.chatrooms after api call", this.chatrooms)
      } catch (err) {
        console.error(err);
      }
    },
    convertSchemaOfRoomsResponse(rooms) {
      // this converts the schema of response data from "get" request on "/api/rooms" route
      // to match the schema of data returned by socket.on("rooms")
      return rooms.map(room => {
        return {
          _id: room.id,
          members: room.members.map(member => {
            return {
              clientId: member.id,
              clientName: member.name,
              clientType: (member.role ? "user" : "visitor"),
            }
          }),
          messages: room.messages.map(msg => {
            return {
              _id: msg.id,
              clientId: msg.client_id,
              clientType: msg.client_type,
              content: msg.content,
              created_at: msg.created_at,
              isWhisper: msg.is_whisper,
              roomId: msg.room_id,
            }
          }),
        }
      });
    },
    getLastMsgAndSender(chatroom) {
      // ensure chatroom.messages is not undefined and is not an empty array
      if (chatroom.messages && chatroom.messages.length > 0) {
        // console.log("chatroom", chatroom.messages.length);
        const lastMsg = chatroom.messages[chatroom.messages.length - 1];
        const msgSender = this.getMsgSender(lastMsg, chatroom)
        const msg = lastMsg.content;

        return `${msgSender}: ${msg}`
      }
    },

    getMsgSender(msg, chatroom) {
      return msg.clientId === this.currentUser._id ? "You" : chatroom.members
        .filter(member => member.clientId === msg.clientId)
        [0].clientName
    },

    getAssignedToRoom(chatroom) {
      const assignedUsers =
        chatroom.members
          .filter(member => member.clientType === "user")
          .reduce((result, member) => result + `${member.clientName}, `,"");
      const formatttedAssignedUsers = assignedUsers.slice(0, -2);
      return formatttedAssignedUsers;
    },

    scrollToChatBottom() {
      // console.log("scrolling to bottom");
      const chatWindows = this.$refs.chatWindow;
      // console.log("chatWindows", chatWindows);
      chatWindows.forEach((window) => {
          window.scrollTop = window.scrollHeight;
          // console.log("scrollTop", window.scrollTop);
          // console.log("scrollHeight", window.scrollHeight);
      });
    },

    sendMessage() {

      if (this.message === "") return;

      const newMessage = {
        content: this.message,
        roomId: this.activeRoom,
      };

      // console.log("newMessage1=> ", {...newMessage});

      // if message is whisper
      // console.log("event=> ", event.target.parentElement.id);
      if (event.target.parentElement.id === "whisper") {
        // console.log("this is a whisper")
        newMessage['isWhisper'] = true;
        socket.emit("whisper", newMessage);
      } else {
        socket.emit("message", newMessage);
      }
      // console.log("newMessage2=> ", newMessage);

      // Add message to UI
      let found = this.getTargetRoomIndex(this.activeRoom);
      // console.log("this.chatrooms=> ", this.chatrooms[found].messages)
      this.chatrooms[found].messages.push({
        ...newMessage,
        clientId: socket.auth.clientId,
        _id: "",
      });
      // console.log("this.chatrooms2=> ", this.chatrooms[found].messages)

      this.newMessage = "";
    },

    // sendTypingEvent() {
    //     Echo.join("chat").whisper("typing", this.user);
    // },

    selectRoom: function (roomId) {
      this.activeRoom = roomId;
      this.scrollToChatBottom();
    },

    selectIncomingRoom: function (roomId) {
      // console.log("incoming block div of chatroom ", document.getElementById(`${roomId}`).parentElement)
      event.preventDefault();
      this.selectRoom(roomId);

      // join the selected room
      this.joinRoom(roomId)
    },

    joinRoom: function(roomId) {

      let confirmAction = confirm("Are you sure you want to join this room?");

      if (confirmAction) {

        let foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];
        // console.log("found chatroom before joining", ({"_id": foundRoom["_id"], "members": foundRoom["members"], "messages": foundRoom["messages"]}))


        // check if currentUser is already a member of foundRoom
        if (foundRoom.members.filter(member => member.clientId === this.currentUser._id).length > 0 ) {
          console.log("currently a member");
          return;
        }
        console.log("currently not a member");

        foundRoom.members.push(
            {clientId: this.currentUser._id, clientName: this.currentUser.name, clientType: "user"}
        )
        // console.log("found chatroom after joining", foundRoom);

        socket.emit("join", { roomId: roomId, name: this.currentUser.name });

        alert("Successfully joined this room");
      }
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

  },
};

</script>
