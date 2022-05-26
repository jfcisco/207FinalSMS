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

  <!--MESSAGE LISTS START-->
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
          <div class="listHead">
            <p>Incoming user: {{ chatroom._id }}</p>
          </div>
          <!--room id/username section-->
          <!-- The message last sent to the room -->
          <div class="message_p">
            <p>last message: {{ chatroom.messages[chatroom.messages.length - 1].content }}</p>
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
        :key="chatroom._id"
      >

        <div
          class="details"
          v-on:click="selectRoom(chatroom._id)"
          v-if="chatroom.members.length > 1"
          v-bind:id="chatroom._id"
        >

          <!--room id/username section-->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>
          <!-- delete in the future -->
          <div class="listHead">
            <p>room._id: {{ chatroom._id }}</p>
          </div>
          <!-- delete in the future -->

          <!-- assigned room users (Admin/Agent) -->
          <div class="listHead">
            <p style="font-weight: 500; font-style: italic;">
              Assigned:
              {{
                chatroom.members
                .filter(member => member.clientType === "user")
                .reduce((result, member) => result + `${member.clientName} `,""
                )
              }}
            </p>
          </div>
          <!-- assigned room users (Admin/Agent) -->

          <!-- The message last sent to the room -->
          <div class="message_p">
            <p>last message: {{ chatroom.messages[chatroom.messages.length - 1].content }}</p>
          </div>
        </div>

      </div>
      <!--ACTIVE CHAT BLOCK END-->

    </div>
  </div>
  <!--MESSAGE LISTS END-->

  <!--MAIN CHAT WINDOW START-->
  <div class="col-lg-9 mainchat">
    <div
      class="mainchat2"
      v-for="chatroom in chatrooms"
      v-show="chatroom._id == activeRoom"
      :key="chatroom._id">

      <div
        class="card-body chatmessages roomMessages"
        v-bind:id="'messages_room' + chatroom._id">

          <div><h4>{{ chatroom.members[0].clientName }}</h4></div>
          <div><h5 style="font-weight: 500; font-style: italic;">
            Assigned:
            {{
              chatroom.members
              .filter(member => member.clientType === "user")
              .reduce((result, member) => result + `${member.clientName} `,""
              )
            }}
          </h5></div>

          <!--CHATBOX START-->
          <div class="chatboxfix" ref="chatWindow" v-chat-scroll style="overflow-y: scroll; overflow-x: hidden;">
            <ul class="list-unstyled">

                <!-- CHAT MESSAGE LINE START -->
                <li
                  class="py-2"
                  v-for="(message, index) in chatroom.messages"
                  :key="index">

                    <!-- <div
                      v-if="message.content.length > 0"
                      :class="{
                        sentmessage: message.clientId == user._id,
                        receivedmessage: message.clientId !== user._id,
                      }"
                    > -->
                    <div
                      v-if="message.content.length > 0"

                      :class="{
                        sentmessage: message.clientId == user._id,
                        receivedmessage: message.clientId !== user._id,
                        whisper_text: message.isWhisper
                      }"
                    >
                        <b>{{ message.clientId === user._id ? "You: " : `${chatroom.members[0].clientName}: ` }}</b>
                        <p>&nbsp;{{message.content }}</p>
                    </div>
                </li>
                <!-- CHAT MESSAGE LINE END -->
            </ul>
          </div>
          <!--CHATBOX END-->
      </div>

      <!--MAIN INPUT MESSAGE BOX START-->
      <div class="chatbox_input" id="message_main" style="display:flex">

        <ion-icon class="whisper" name="volume-high-outline" id="headerToggle1" onclick="toggleheaderleft()"></ion-icon>

        <input
            @keyup.enter="sendMessage"
            v-model="message"
            type="text"
            name="message"
            placeholder="Enter your message..."
            class="form-control"
        />
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
            class="form-control"
        />

      </div>
      <!--WHISPER INPUT MESSAGE BOX END-->

    </div>
  </div>
    <!--MAIN CHAT WINDOW END-->

</div> <!--ROW END-->
</template>




<script>
import FileUploadComponent from "./FileUploadComponent.vue";
import ProfileUpdateComponent from "./ProfileUpdateComponent.vue";

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

  // computed: {
  //   activeRoomDetails: function () {
  //     const activeRoomResult = this.chatrooms.filter(
  //       (room) => room._id == this.activeRoom
  //     );

  //     if (activeRoomResult.length === 0) {
  //       return null;
  //     } else {
  //       return activeRoomResult[0];
  //     }
  //   },
  // },

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

    socket.on("rooms", ({ rooms }) => {
      console.log("socket.on rooms")
      console.log("rooms full data=> ", rooms)
      console.log("rooms mod data=> ", rooms.map(room => ({ 'room._id': room._id, 'room.members.length': room.members.length, 'room.messages': room.messages })));

      this.chatrooms = _.unionBy(
        rooms,
        this.chatrooms,
        (room) => room._id,
      );

      // add test data for incoming room | only 1 member of room w/ clientType: visitor
      this.chatrooms.push({
        _id: "54321",
        members: [
          {clientId: "1234", clientName: "test-incoming-visitor", clientType: "visitor"}
        ],
        messages: [
          {_id: "123", clientId: "1234", clientType: "visitor", content: "test-msg", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
          {_id: "1234", clientId: "1234", clientType: "visitor", content: "test-msg2", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
          {_id: "12345", clientId: "1234", clientType: "visitor", content: "test-msg3", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
          {_id: "123456", clientId: "1234", clientType: "visitor", content: "test-msg4", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
          {_id: "1234567", clientId: "1234", clientType: "visitor", content: "test-msg5", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},
          {_id: "12345678", clientId: "1234", clientType: "visitor", content: "test-msg6", created_at: "2022-05-24T13:38:34.584Z", isWhisper: false, roomdId: "54321"},

        ],
      })

      console.log("chatRooms => ", this.chatrooms.map(room => ({ 'chatroom._id': room._id, 'chatroom.members.length': room.members.length, 'chatroom.messages': room.messages })));

      // join all rooms in chatrooms
      // this.chatrooms.forEach((room) => {
        // socket.emit("join", { roomId: room._id, name: this.currentUser.name });
        // console.log("roomId", room._id, "name", this.currentUser.name, "members", room.members);
      // });
    });

    socket.on("message", (message) => {
      console.log("message received", message);
      let found = this.getTargetRoomIndex(message.roomId);
      this.chatrooms[found].messages.push(message);
    });
  },

  methods: {
    scrollToChatBottom() {
      console.log("scrolling to bottom");
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

      console.log("newMessage1=> ", {...newMessage});

      // if message is whisper
      console.log("event=> ", event.target.parentElement.id);
      if (event.target.parentElement.id === "whisper") {
        console.log("this is a whisper")
        newMessage['isWhisper'] = true;
        // newMessage['clientId'] = "1659239814";
        // reference is message 27, content: ww, clientId: "1659239814", clientType: visitor | clientName: Jasper
        // reference is message 2, content: "hi!", clientId: "628ccfce68a859a97208ccfe", clientType: "user"
        // whisper message 51 and 52, clientType: "user"
        socket.emit("whisper", newMessage);
      } else {
        socket.emit("message", newMessage);
      }

      console.log("newMessage2=> ", newMessage);



      // Add message to UI
      let found = this.getTargetRoomIndex(this.activeRoom);
      console.log("this.chatrooms=> ", this.chatrooms[found].messages)
      this.chatrooms[found].messages.push({
        ...newMessage,
        clientId: socket.auth.clientId,
        _id: "",
      });
      console.log("this.chatrooms2=> ", this.chatrooms[found].messages)

      this.newMessage = "";
    },
    // sendTypingEvent() {
    //     Echo.join("chat").whisper("typing", this.user);
    // },
    // fetchChatrooms() {
    //     console.log("fetchChatrooms");
    //     this.loadingChatrooms = true;
    //     axios
    //         .get("rooms")
    //         .then((response) => {
    //             console.log("rooms", response);
    //             if (response.data.length > 0) {
    //                 this.chatrooms = response.data;
    //                 this.activeRoom = this.chatrooms[0].room_id;
    //             }
    //         })
    //         .finally(() => {
    //             this.loadingChatrooms = false;
    //         });
    // },
    selectRoom: function (roomId) {
      this.activeRoom = roomId;
      this.scrollToChatBottom();
    },

    selectIncomingRoom: function (roomId) {
      // console.log("incoming block div of chatroom ", document.getElementById(`${roomId}`).parentElement)
      event.preventDefault();
      this.selectRoom(roomId);

      // delete this
      console.log("currentUser", this.currentUser)
      let foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];
      console.log("found chatroom before joining", ({"_id": foundRoom["_id"], "members": foundRoom["members"], "messages": foundRoom["messages"]}))
      // delete this

      let confirmAction = confirm("Are you sure you want to join this room?");

      // join the selected room
      if (confirmAction) {
        this.joinRoom(roomId)
        alert("Successfully joined this room")
      }
    },

    joinRoom: function(roomId) {
      let found = this.getTargetRoomIndex(roomId);

      this.chatrooms[found].members.push(
          {clientId: this.currentUser._id, clientName: this.currentUser.name, clientType: "user"}
      )
      console.log("found chatroom after joining", this.chatrooms[found])

      // insert socket.io code for joining a room
      socket.emit("join", { roomId: roomId, name: this.currentUser.name });
    },

    // Function to query the database for a certain user
    // findUser(query) {
    //     if (query === "") {
    //         // Only send request if there is a search query
    //         this.userDropdownOptions = [];
    //         return;
    //     }

    //     // Show the loading bar
    //     this.isSearchLoading = true;
    //     axios
    //         .get("/users", {
    //             params: {
    //                 name: query,
    //             },
    //         })
    //         .then((response) => {
    //             // Set the result of the database query as the options
    //             this.userDropdownOptions = response.data;
    //         })
    //         .catch((error) => {
    //             // Unexpected error occurred, for now log to console
    //             console.error(error);
    //         })
    //         .finally(() => {
    //             this.isSearchLoading = false;
    //         });
    // },

    // findUserNotInRoom(nameQuery, roomId) {
    //     if (nameQuery === "") {
    //         // Only send request if there is a search query
    //         this.newMemberDropdownOptions = [];
    //         return;
    //     }

    //     // Show the loading spinner
    //     this.isMemberSearchLoading = true;
    //     axios
    //         .get("/users", {
    //             params: {
    //                 name: nameQuery,
    //                 notInRoom: roomId,
    //             },
    //         })
    //         .then((response) => {
    //             // Set the result of the database query as the options
    //             this.newMemberDropdownOptions = response.data;
    //         })
    //         .catch((error) => {
    //             // Unexpected error occurred, for now log to console
    //             console.error(error);
    //         })
    //         .finally(() => {
    //             this.isMemberSearchLoading = false;
    //         });
    // },

    // addMember(newMember) {
    //     axios.post("addMember", {
    //         email: newMember.email,
    //         room_id: this.activeRoom,
    //     });

    //     // Inform the chatroom that a new member has been added
    //     let found = this.getTargetRoomIndex(this.activeRoom);
    //     this.chatrooms[found].messages.push({
    //         user: "",
    //         message:
    //             newMember.first_name +
    //             " " +
    //             newMember.last_name +
    //             " has been added",
    //     });

    //     this.newMemberDropdownOptions = [];
    //     this.addingMember = false;
    // },

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
