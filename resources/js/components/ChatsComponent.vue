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
        <div class="block incoming">
          <div class="details">

            <!--room id/username section-->
            <div class="listHead">
              <p>Incoming user</p>
            </div>
            <!--room id/username section-->
            <!-- The message last sent to the room -->
            <div class="message_p">
              <p>last message sent</p>
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
        :key="chatroom._id">

        <div
          class="details"
          v-on:click="selectRoom(chatroom._id)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <div class="listHead">
            <p><strong>{{ chatroom.members[0].clientName }}</strong></p>
            <br>
            <p>{{ chatroom._id }}</p>
          </div>
          <!--room id/username section-->
          
          <!-- The message last sent to the room -->
          <div class="message_p">
            <p>last message sent</p>
          </div>
          <!-- The message last sent to the room -->
        
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

          <div><h4>{{ chatroom._id }}</h4></div>

          <!--CHATBOX START-->
          <div class="chatboxfix" style="overflow-y: scroll; overflow-x: hidden;">
            <ul class="list-unstyled">

                <!-- CHAT MESSAGE LINE START -->
                <li
                  class="py-2"
                  v-for="(message, index) in chatroom.messages"
                  :key="index">

                  <div
                    v-if="message.content.length > 0"
                    :class="{
                      sentmessage: message.clientId == user._id,
                      receivedmessage: message.clientId !== user._id
                    }">
                        <b>{{ message.clientId === user._id ? "You" : message.clientId}} :</b>
                        {{ message.content }}

                  </div>
                </li>
                <!-- CHAT MESSAGE LINE END -->
            </ul>
          </div>
          <!--CHATBOX END-->
      </div>

      <!--MAIN INPUT MESSAGE BOX START-->
      <div class="chatbox_input" id="message_main" style="display:flex">

        <ion-icon class="whisper" name="volume-mute-outline" id="headerToggle" onclick="toggleheaderleft()"></ion-icon>

        <input
            @keyup.enter="sendMessage"
            v-model="newMessage"
            type="text"
            name="message"
            placeholder="Enter your message..."
            class="form-control"
        />
      </div>
      <!--MAIN INPUT MESSAGE BOX END-->

      <!--WHISPER INPUT MESSAGE BOX START-->
      <div class="chatbox_input" id="whisper" style="display:none">

        <ion-icon class="whisper2" name="volume-high-outline" id="headerToggle" onclick="toggleheaderleft()"></ion-icon>

        <input
            @keyup.enter="sendMessage"
            v-model="newMessage"
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
/*.content .messages .message.sent-message {
    justify-content: flex-end;
}
.content .messages .message.received-message {
    justify-content: flex-start;
}*/
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
      // this.fetchChatrooms();
      // Echo.join("chat")
      //   .here((user) => {
      //     this.users = user;
      //     this.fetchMessages();
      //   })
      //   .joining((user) => {
      //     this.users.push(user);
      //   })
      //   .leaving((user) => {
      //     this.users = this.users.filter((u) => u.id != user.id);
      //   })
      //   .listen(".chatroom.created", (event) => {
      //     // Add the chatroom created to the user's list of chatrooms

      //     // Only if they're a member of the chatroom
      //     if (
      //       event.chatRoom.members.some(
      //         (member) => member.id === this.$props.user.id
      //       )
      //     ) {
      //       this.chatrooms.unshift({
      //         room_id: event.chatRoom.room_id,
      //         room_name: event.chatRoom.room_name,
      //       });

      //       this.roomMsgs.unshift({
      //         room_id: event.chatRoom.room_id,
      //         room_name: event.chatRoom.room_name,
      //         messages: [],
      //       });
      //     }
      //   })
      //   .listen(".message", (event) => {
      //     //check which room the message goes
      //     let found = this.getTargetRoomIndex(event.room_id);
      //     if (
      //       event.room_name == "" &&
      //       found == null &&
      //       event.new_member != this.user.id
      //     ) {
      //       //no one is being added and user doesn't have the room
      //       //message is not for the user
      //       return;
      //     } else if (
      //       event.room_name != "" &&
      //       found == null &&
      //       event.new_member == this.user.id
      //     ) {
      //       //user is being added to the room
      //       this.chatrooms.unshift({
      //         room_id: event.room_id,
      //         room_name: event.room_name,
      //       });
      //       this.roomMsgs.unshift({
      //         room_id: event.room_id,
      //         room_name: event.room_name,
      //         messages: [],
      //       });
      //       //this.activeRoom = event.room_id;
      //       found = this.getTargetRoomIndex(event.room_id);
      //     }

      //     if (found != null) {
      //       //put the new message received in the right room
      //       let newMessage = {
      //         user: event.user,
      //         message: event.message,
      //       };
      //       console.log("Event");
      //       console.log(event);
      //       console.log("New Message");
      //       console.log(newMessage);

      //       // Check if incoming has an attachment
      //       if (event.attachment_path) {
      //         newMessage.attachment_path = event.attachment_path;
      //       }

      //       this.roomMsgs[found].messages.push(newMessage);
      //       //move up the room with the new message
      //       let found2 = null;
      //       for (const indx in this.chatrooms) {
      //         if (this.chatrooms[indx].room_id == event.room_id) {
      //           found2 = indx;
      //         }
      //       }
      //       let room = this.chatrooms[found2];
      //       this.chatrooms.splice(found2, 1);
      //       this.chatrooms.unshift(room);
      //     }
      //   })
      //   .listenForWhisper("typing", (user) => {
      //     this.activeUser = user;

      //     if (this.typingTimer) {
      //       clearTimeout(this.typingTimer);
      //     }

      //     this.typingTimer = setTimeout(() => {
      //       this.activeUser = false;
      //     }, 3000);
      //   });

      socket.auth = {
        // // For visitors
        // clientId: client.getFingerprint(),
        // clientType: "visitor",
        // clientName: "bisita",
        // widgetId: "widget1",

        // For admin/agent
        clientId: this.user._id,
        clientName: "agentako",
        clientType: "user",
      };

      socket.connect();

      socket.on("rooms", ({ rooms }) => {
        console.log("socket.on rooms")
        console.log("rooms => ", rooms);

        this.chatrooms = _.unionBy(
          rooms,
          this.chatrooms,
          // room is undefined | not needed | why did jasper place this here?
          (room) => room._id
        );
        console.log("chatRooms => ", this.chatrooms);

        // join all rooms in chatrooms
        this.chatrooms.forEach((room) => {
          socket.emit("join", { roomId: room._id, name: this.currentUser.name });
          console.log("roomId", room._id, "name", this.currentUser.name, "members", room.members);
        });
      });

      socket.on("message", (message) => {
        console.log("message received", message);
        let found = this.getTargetRoomIndex(message.roomId);
        this.chatrooms[found].messages.push(message);
      });
    },

    methods: {
        // scrollToChatBottom() {
        //     console.log("scrolling to bottom");
        //     const chatWindows = this.$refs.chatWindow;
        //     console.log("chatWindows", chatWindows);
        //     chatWindows.forEach((window) => {
        //         window.scrollTop = window.scrollHeight;
        //         console.log("scrollTop", window.scrollTop);
        //         console.log("scrollHeight", window.scrollHeight);
        //     });
        // },
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
            // this.scrollToChatBottom();
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
