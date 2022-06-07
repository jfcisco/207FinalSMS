<template>
<div class="row">

  <!--MAIN SIDE BAR START-->
  <div class="col-lg-1 col-sm-1 mainsidebar">
    <a class="activemenu" href="/home">
      <ion-icon class="main-menu-icon" name="mail-outline"></ion-icon>
      <span class="menutitle">Messaging</span>
    </a>
    <a href="/reports">
      <ion-icon class="main-menu-icon" name="bar-chart-outline"></ion-icon>
      <span class="menutitle">Reporting</span>
    </a>
    <a href="/widgets">
      <ion-icon class="main-menu-icon" name="copy-outline"></ion-icon>
      <span class="menutitle">Widget</span>
    </a>
  </div>
  <!--MAIN SIDE BAR END-->


  <!--ROOM LISTS START-->
  <div class="col-lg-2 col-sm-3 sidebar">

    <p class="subtitle">Incoming Chats</p>

    <!--INCOMING SESSIONS SECTION START-->
    <div class="row py-0 mt-0 incomingsessions" style="overflow-y: scroll">

      <!--INCOMING CHAT BLOCK START-->
      <!-- Display incoming chats, wherein a chatroom has a conversation property (with a startAt property for that chatroom's conversation) -->
      <div
        class="block incoming"
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
          incoming: chatroom.members.length === 1
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length === 1 && (chatroom.conversation ? chatroom.conversation.startAt : false)"
        :key="chatroom._id">

        <div
          class="details"
          v-on:click="selectIncomingRoom(chatroom._id, chatroom.conversation)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <!-- start here -->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>

          <!-- HIDE BEFORE COMMIT -->
          <!-- show chatroom ids in incoming -->
          <div class="listHead"><p>room._id: {{ chatroom._id }}</p></div>
          <!-- HIDE BEFORE COMMIT -->

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
    <p class="subtitle sidebartitle">Active Chats</p>
    <div class="row activesessions" style="overflow-y: scroll">

      <!--ACTIVE CHAT BLOCK START-->
      <!-- Display open chats, wherein each chatroom has a conversation property (with a startAt property for that conversation) -->
      <div
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length > 1 && (chatroom.conversation ? chatroom.conversation._id : false)"
        :key="chatroom._id">

        <div
          class="details"
          v-on:click="selectRoom(chatroom._id, chatroom.conversation)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
            <!-- UNREAD MESSAGES NOTIF-->
            <!-- <b class="unreadnotif">1</b> -->
            <b
              class="unreadnotif"
              v-if="chatroomsUnreadNotif[chatroom._id] > 0"
            >
              {{ chatroomsUnreadNotif[chatroom._id] }}
            </b>
          </div>

          <!-- HIDE BEFORE COMMIT -->
          <!-- show chatroom ids in active sessions -->
          <div class="listHead"><p>room._id: {{ chatroom._id }}</p></div>
          <!-- HIDE BEFORE COMMIT -->

          <!-- assigned room users (Admin/Agent) -->
          <div class="listHead">
            <p style="font-style: italic; font-size: 0.9em;  font-weight:100;">
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
    <!--ACTIVE SESSIONS END-->

    <!--CLOSED SESSIONS START-->
    <p class="subtitle sidebartitle">Closed Chats</p>
    <div class="row inactivesessions" style="overflow-y: scroll">

      <!--CLOSED CHAT BLOCK START-->
      <!-- Display ended chats, wherein the room has an undefined or no conversation property (with an endAt property for the last conversation of that room) -->
      <div
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="!chatroom.conversation || (chatroom.conversation ? !chatroom.conversation.startAt : false ) "
        :key="chatroom._id">
        <div
          class="details"
          v-on:click.prevent="selectClosedRoom(chatroom._id)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>

          <!-- HIDE BEFORE COMMIT -->
          <!-- show chatroom ids in active sessions -->
          <div class="listHead"><p>room._id: {{ chatroom._id }}</p></div>
          <!-- HIDE BEFORE COMMIT -->

          <!-- assigned room users (Admin/Agent) -->
          <div class="listHead">
            <p style="font-style: italic; font-size: 0.9em;  font-weight:100;">
              Previously Assigned: {{ getAssignedToRoom(chatroom) ? getAssignedToRoom(chatroom) : "None" }}
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
      <!--CLOSED CHAT BLOCK END-->
    </div>
    <!--CLOSED SESSIONS END-->
  </div>
  <!--ROOM LISTS END-->

  <!--MAIN CHAT WINDOW START-->
  <div class="col-lg-9 col-sm-8 mainchat">

    <!--MAIN INPUT MESSAGE BOX START-->

    <div class="chatbox_input" id="message_main" style="display:none">
      <span title="Click to send whisper message" id="headerToggle1" onclick="toggleheaderleft()">
        <ion-icon class="whisper" name="volume-high-outline"></ion-icon>
      </span>

      <FileUploadComponent v-on:upload-success="handleAttachmentUpload"></FileUploadComponent>

      <input
          @keyup.enter="sendMessage"
          v-model="message"
          type="text"
          name="message"
          placeholder="Enter your message..."
          class="form-control"/>
    </div>
    <!--MAIN INPUT MESSAGE BOX END-->

    <!--MAIN CHAT BUTTONS START-->
    <div class="openbtndiv"
      v-for="chatroom in chatrooms"
      :chatroom="chatroom"
      v-show="chatroom._id == activeRoom"
      :key="'chat-buttons-'+chatroom._id">

      <div class="d-flex justify-content-between">
        <!--FILE SHARING TOGGLE-->
        <div class="file-sharing-toggle">
          <label class="switch">
            <input type="checkbox" name="enable_file_sharing"
              v-model="chatroom.enable_file_sharing"
              @change="toggleFileSharing(chatroom, $event)">
              <span class="slider-file-sharing round"></span>
          </label>
          <div>
          <span class="file-sharing-label">Enable File Sharing</span>
          </div>
        </div>
        <!--FILE SHARING TOGGLE-->


        <!--CHAT HISTORY SLIDER BUTTON-->
        <button
          class="openbtn"
          onclick="openNav()"
          v-on:click="populateMessageHistoryList(chatroom)">
          <ion-icon name="information-circle-outline"></ion-icon>
        </button>
        <!--CHAT HISTORY SLIDER BUTTON-->
      </div>
    </div>
    <!--MAIN CHAT BUTTONS END-->


    <!--HISTORY BLOCK START-->
    <MessageHistoryComponent
      :conversation="chatroomsAPIData.find(room => room.id === activeRoom) ?
        chatroomsAPIData
          .find(room => room.id === activeRoom)
          .conversations
          .find(conversation => conversation.id === activeConversationHistoryId) || {} :
        {}"
      :chatroomMembers="chatroomsAPIData.find(room => room.id === activeRoom) ?
        chatroomsAPIData.find(room => room.id === activeRoom).members :
        []">
    </MessageHistoryComponent>

    <!--WHISPER INPUT MESSAGE BOX START-->
    <div class="chatbox_input" id="whisper" style="display:none">
      <span title="Click to send chat to visitor" id="headerToggle2" onclick="toggleheaderleft()">
        <ion-icon class="whisper2" name="volume-mute-outline"></ion-icon>
      </span>
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

          <div class="d-flex justify-content-start">
            <h4>{{ chatroom.members[0].clientName }}</h4>

            <!-- Spinner For Room Loading -->
            <div v-if="roomIsLoading" class="pt-1">
              <div class="ms-2 spinner-border spinner-border-sm" style="color: #FA6121;" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <h5 class="assignedmembers">
              {{ chatroom.conversation ? "Assigned" : "Previously Assigned" }}: {{ getAssignedToRoom(chatroom) ? getAssignedToRoom(chatroom) : "None" }}
            </h5>
            <h6 class="lastconversation">{{ chatroom.conversation ? "" : "Last Conversation" }}</h6>
          </div>

          <!--CHATBOX START-->
          <div class="chatboxfix" ref="chatWindow" v-chat-scroll style="overflow-y: scroll; overflow-x: hidden;">
            <ul class="list-unstyled" style="margin-bottom:0">

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
                      }" >

                        <b>
                          {{ getMsgSender(message, chatroom) }}:
                        </b>
                        <p v-if="!message.content.startsWith('files/')">&nbsp;{{ message.content }}</p>
                        <p v-else><a :href="message.content" target="_blank">&nbsp;{{ message.content.slice(6, message.content.length) }}</a></p>

                        <!-- Timestamp -->
                        <p class="ms-auto timestamp">{{formatTimestamp(message.created_at)}}</p>
                    </div>

                </li>
                <!-- CHAT MESSAGE LINE END -->
            </ul>

            <!-- DISPLAY VISITOR TYPING EVENT -->
            <ul class="list-unstyled" v-bind:id="'visitor-typing' + chatroom._id" style="display:none">
              <li><div class="receivedmessage"></div></li>
            </ul>
          </div>
          <!--CHATBOX END-->

          <!-- JOIN FEATURE AVAILABLE ONLY IF CONVERSATION IS OPEN AND CURRENT USER IS NOT ALREADY ASSIGNED -->
          <button
            class="joinbutton"
            id="join-btn"
            style="display: flex"
            v-if="chatroom.conversation && chatroom.members.filter(member => member.clientId === currentUser._id).length === 0 && chatroom.conversation.startAt"
            v-on:click="joinRoom(chatroom._id, chatroom.conversation)">
            <span class="joinroom">Click to join room</span>
          </button>

      </div>
    </div>
  </div>
  <!--MAIN CHAT WINDOW END-->

  <!--CHAT HISTORY-->
  <!--<div class="col-lg-2 col-sm-3 mt-5 chathistoryfull">-->
  <div id="mySidepanel" class="sidepanel" style="overflow-y: scroll" >

    <!--CHAT HISTORY-->
    <div
      v-for="chatroom in chatrooms"
      :chatroom="chatroom"
      v-show="chatroom._id == activeRoom"
      :key="chatroom._id">

            <div>
              <div class="row">
                <div class="mt-2">

                  <!--<button

                    class="chathistorybutton"
                    style="font-weight:600; font-size:16px"
                    v-on:click="populateMessageHistoryList(chatroom)"
                    v-bind:id="'msg-list-btn'+chatroom._id">

                    Chat History
                  </button>-->

                  <div class="chathistorybutton">
                    Chat History
                  </div>

                  <!-- Spinner For Room Loading -->
                  <div class="ms-2 d-inline-block">
                    <div v-if="roomIsLoading" class="spinner-border spinner-border-sm" role="status"  style="color: #FA6121;">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                  </div>

                  <!--HISTORY LIST START-->
                  <div class="chathistorylist">
                    <!--HISTORY BLOCK START-->
                    <div
                      v-for="conversation in chatroomsAPIData.find(room => room.id === chatroom._id) ?
                        chatroomsAPIData.find(room => room.id === chatroom._id).conversations :
                        []"
                      :key="conversation.id"
                      :conversation="conversation">

                      <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                      <button
                        class="historyblock"
                        title="Check Transcript"
                        data-bs-toggle="modal"
                        data-bs-target="#CheckTranscript"
                        v-on:click="() => { activeConversationHistoryId = conversation.id }">
                        <div>
                            <span>Conversation: {{ conversation.id }}</span>
                            <p>started: {{ conversation.startAt }}</p>
                            <p>ended: {{ conversation.endAt }} </p>
                        </div>
                      </button>
                    </div>
                    <!--HISTORY BLOCK START-->
                  </div>
                  <!--HISTORY LIST END-->

                </div>
              </div>
            </div>

    </div>
    <!--CHAT HISTORY-->
  </div>
  <!--CHAT HISTORY-->

</div>
<!--ROW END-->
</template>

<script>
import FileUploadComponent from "./FileUploadComponent.vue";
import ProfileUpdateComponent from "./ProfileUpdateComponent.vue";
import MessageHistoryComponent from "./MessageHistoryComponent.vue";
import axios from 'axios';

const client = new cj.ClientJS();

const socket = io(process.env.MIX_SOCKET_SERVER, {
    secure: true,
    autoConnect: false,
});

export default {
  props: ["user", "crm", "strt"],

  components: {
    FileUploadComponent,
    ProfileUpdateComponent,
    MessageHistoryComponent,
  },

  data() {
    return {
      currentUser: this.user,
      message: "",
      users: [],
      chatrooms: [],
      chatroomsAPIData: [],
      activeRoom: "",
      activeConversation: "",
      activeConversationHistoryId: "",
      chatroomsUnreadNotif: {},
      totalNotifCount: 0,
      origTitle: document.title,
      audio: new Audio("https://soundjax.com/reddo/88877%5EDingLing.mp3"),
      titleNotificationInterval: undefined,

      roomIsLoading: false, // States if a GET room API call is running
    };
  },

  created() {

    window.addEventListener("focus", () => {
      // console.log("window is in focus");
      this.totalNotifCount = 0;
      this.hideTitleNotifications();
    });

    socket.auth = {
      // For admin/agent
      clientId: this.currentUser._id,
      clientName: `${this.currentUser.name}`,
      clientType: "user",
    };

    socket.connect();

    socket.on("rooms", ({ rooms }) => {
      // console.log("running socket.on rooms1")
      // console.log("rooms full data=> ", rooms);
      this.chatrooms = _.unionBy(
        rooms,
        this.chatrooms,
        (room) => room._id,
      );
      // console.log("this.chatrooms=>", this.chatrooms);

      // initialize chatroomsUnreadNotif
      rooms.map(room => {
        this.chatroomsUnreadNotif[`${room._id}`] = 0;
      });
    });

    socket.on("convo_started", (conversation) => {
      // console.log("visitor started a convo", conversation);
      if (this.chatrooms.length === 0) {
        // chatrooms is an empty array so keep checking until it is populated
        let searchChatroomInterval = setInterval(() => {
          // console.log("running set interval")
          let foundRoom = this.chatrooms[this.getTargetRoomIndex(conversation.roomId)];
          if (foundRoom) {
            foundRoom.conversation = conversation;
            clearInterval(searchChatroomInterval);
            // console.log("running clear interval")
          }
        }, 500);
      } else {
        // chatrooms is not an empty array so a match will definitely be found
        let foundRoom = this.chatrooms[this.getTargetRoomIndex(conversation.roomId)];
        foundRoom.conversation = conversation;
      }
    });

    socket.on("message", (message) => {
      // console.log("message received", message);

      // clear visitor's typing event element and hide from display
      const visitorTypingContainerEl = document.getElementById(`visitor-typing${message.roomId}`);

      if (visitorTypingContainerEl) {
        visitorTypingContainerEl.querySelector("li div").innerHTML = ""
        visitorTypingContainerEl.style.display = "none";
      }

      let foundRoom = this.chatrooms[this.getTargetRoomIndex(message.roomId)];
      if (foundRoom) {
        foundRoom.messages.push(message);
      }

      // modify room's unread notif count everytime a message is received only when that
      // message's room is not currently selected in Active Chats

      // check if selected room is not the same as message's room
      if (this.activeRoom !== message.roomId) {
        // increment notif count by 1 before assignment
        ++this.chatroomsUnreadNotif[message.roomId]
      }

      // increment totalNotifCount everytime a messages is received and dashboard is not in focus
      if (!document.hasFocus()) {
        this.showTitleNotifications(++this.totalNotifCount);

      }
    });

    socket.on("typing", (data) => {
      // Leaving this log for debugging:
      // console.log("typing data => ", JSON.parse(JSON.stringify(data)));

      const { clientId, conversationId, roomId, content } = data;

      // "typing" event fires whenever the visitor presses a keyboard button

      // clientId - Client ID of visitor typing
      // conversationId - ID of current conversation
      // roomId - ID of current room
      // content - Exact text being typed by the visitor
      // Similar properties as "message" event. I hope these are enough - jfcisco

      if (this.activeRoom === roomId) {

        // show visitor's typing event
        const foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];
        const visitorTypingContainerEl = document.getElementById(`visitor-typing${roomId}`);

        if (visitorTypingContainerEl) {
          // display msg sender and content
          visitorTypingContainerEl.style.display = "block";
          visitorTypingContainerEl.querySelector("li div").innerHTML = `
            <b>${this.getMsgSender(data, foundRoom)}</b><p>&nbsp;is typing...</p><b>:</b>
            <p>&nbsp;${content}</p>
          `;
        }
      }
    });

    socket.on("file_sharing", payload => {
      // console.log("file_sharing event fired");
      // console.log(payload);
      const { roomId, allowed } = payload;
      // `file_sharing` event fires whenever file sharing is enabled/disabled for a room
      this.chatrooms.find(room => room._id === roomId).enable_file_sharing = allowed;
    });

    socket.on("end_chat", conversationId => {
      // console.log("visitor ended conversation", conversationId);
      this.chatrooms.forEach(chatroom => {
        if (chatroom.conversation._id === conversationId) {
          chatroom.conversation = undefined;
          this.unselectRoom(chatroom._id);
          return;
        }
      });
    });

    //try to join a room and start a conversation
    //using this.crm and this.strt is from $_GET variables from the url
    if(this.crm===""){
        
    }else{

      this.activeRoom = this.crm;
  
      if(this.strt===""){
        
      }else{
        
        //i can't get the conversation id because this.chatrooms is undefined at this point in the code

        //let convo = this.chatrooms[this.getTargetRoomIndex(roomId)].conversation._id;
        //this.selectIncomingRoom(this.crm, convo);

      }
    };
  },

  methods: {
    async getRoom(roomId) {
      // console.log("executing getRoom")
      try {
        const response = await axios.get(`/api/rooms/${roomId}`);
        // console.log("getRoom", response.data.data);
        return response.data.data;
      } catch (err) {
        console.error(err);
      }
    },

    convertConvoMsgSchema(roomId, conversation) {
      // will convert conversation's messages schema of response from getRoom to match data from socket.on("rooms")
      const {id: convoId, messages} = conversation;
      return messages.map(msg => {
        return {
          _id: msg.id,
          clientId: msg.client_id,
          clientType: msg.client_type,
          content: msg.content,
          created_at: msg.created_at,
          isWhisper: msg.is_whisper,
          roomId: roomId,
          conversationId: convoId
        }
      });
    },

    getLastMsgAndSender(chatroom) {
      // ensure chatroom.messages is not undefined and is not an empty array
      if (chatroom.messages && chatroom.messages.length > 0) {
        const lastMsg = chatroom.messages[chatroom.messages.length - 1];
        const msgSender = this.getMsgSender(lastMsg, chatroom)
        const msg = lastMsg.content;

        return `${msgSender}: ${msg}`
      }
    },

    getMsgSender(msg, chatroom) {
      return msg.clientId === this.currentUser._id ?
        "You" :
        (
          chatroom.members.filter(member => member.clientId === msg.clientId).length > 0 ?
          chatroom.members.filter(member => member.clientId === msg.clientId)[0].clientName :
          "Error"
        );
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
      });
    },

    sendMessage() {
      if (this.message === "") return;

      const newMessage = {
        content: this.message,
        roomId: this.activeRoom,
        conversationId: this.activeConversation,
        created_at: new Date().toISOString()
      };

      // if message is whisper
      if (event.target.parentElement.id === "whisper") {
        // console.log("this is a whisper")
        newMessage['isWhisper'] = true;
        socket.emit("whisper", newMessage);
      } else {
        socket.emit("message", newMessage);
        // console.log("message", newMessage)
      }

      // Add message to UI
      let found = this.getTargetRoomIndex(this.activeRoom);
      this.chatrooms[found].messages.push({
        ...newMessage,
        clientId: this.currentUser._id,
        _id: "",
      });

      this.message = "";
    },

    selectRoom: function(roomId, conversation) {
      // console.log("running selectRoom");
      this.activeRoom = roomId;
      // console.log("conversation._id", conversation._id)
      if (conversation) {
        this.activeConversation = conversation._id;
        this.toggleMsgHistoryListBtn(roomId, conversation._id);
      } else {
        this.activeConversation = undefined;
        this.toggleMsgHistoryListBtn(roomId, undefined);
      }
      this.scrollToChatBottom();
      this.toggleMessageMainEl("show");
      this.toggleSidepanel("show");

      // clear selected room's notification count
      this.chatroomsUnreadNotif[roomId] = 0;

      // adjust title notification
      this.hideTitleNotifications();
    },

    selectIncomingRoom: function (roomId, conversation) {
      // console.log("running selectIncomingRoom");
      event.preventDefault();
      this.selectRoom(roomId, conversation);

      this.joinRoom(roomId, conversation)
    },

    selectClosedRoom: function(roomId) {
      // console.log("running selectClosedRoom");
      try {
        this.roomIsLoading = true;

        this.getRoom(roomId).then(results => {
          this.populateMessageHistoryList(results);
          const foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];
          const lastConversation = results.conversations[results.conversations.length - 1]
          foundRoom.messages = this.convertConvoMsgSchema(roomId, lastConversation);
        })
        .finally(() => {
          this.roomIsLoading = false;
        });

        this.selectRoom(roomId, undefined);
        this.toggleMessageMainEl("hide");
        this.toggleSidepanel("hide");
      } catch(err) {
        console.error(err);
      }
    },

    unselectRoom(roomId) {
      if (this.activeRoom === roomId) {
        this.toggleMessageMainEl("hide");
        this.activeRoom = "";
      }
    },

    joinRoom: function(roomId, conversation) {

      let confirmAction = confirm("Are you sure you want to join this room?");

      if (confirmAction) {

        let foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];

        // check if currentUser is already a member of foundRoom
        if (foundRoom.members.filter(member => member.clientId === this.currentUser._id).length > 0 ) {
          // console.log("currently a member");
          return;
        }
        // console.log("currently not a member");

        foundRoom.members.push(
            {clientId: this.currentUser._id, clientName: this.currentUser.name, clientType: "user"}
        )

        socket.emit("join", { roomId: roomId, conversationId: conversation._id, name: this.currentUser.name });

        alert("Successfully joined this room");

        this.toggleMessageMainEl("show");
        this.toggleSidepanel("show");
      }
    },

    toggleMsgHistoryListBtn(roomId, conversationId) {
      const msgHistoryListBtnEl = document.getElementById(`msg-list-btn${roomId}`);
      if (msgHistoryListBtnEl) {
        if (conversationId) {
        msgHistoryListBtnEl.disabled = false;
        } else {
          msgHistoryListBtnEl.disabled = true;
        }
      }
    },

    populateMessageHistoryList: async function(chatroom) {
      // console.log("running populateMessageHistoryList");
      try {
        const results = [];
        if (chatroom.conversation) {
          // if it is an active or incoming chat, get request to "api/room/{roomId}"
          // console.log("chatroom.conversation is truthy");
          this.roomIsLoading = true;
          results.push(await this.getRoom(chatroom._id));
        } else {
          // closed chat since no conversation
          // if it is a closed chat, we already have result from get request to "api/room/{roomId}"
          //    passed as param when selectClosedRoom executes
          // console.log("chatroom.conversation is falsy", chatroom.conversation);
          results.push(chatroom);
        }
        // console.log("check 1: results=>", results)
        this.chatroomsAPIData = _.unionBy(
          this.chatroomsAPIData,
          results,
          (room) => room.id,
        );
        // console.log("check 2: chatroomsAPIData=>", this.chatroomsAPIData);
      } catch(err) {
        console.error(err);
      } finally {
        this.roomIsLoading = false;
      }
    },

    toggleMessageMainEl(action) {
      const msgMainEl= document.getElementById("message_main");
      if (action === "show") {
        msgMainEl.style.display = "flex";
      } else if (action === "hide") {
        msgMainEl.style.display = "none";
      }
    },

    toggleSidepanel(action) {
      const sidepanel= document.getElementById("sidepanelbutton");
      if (sidepanel) {
        if (action === "show") {
        sidepanel.style.display = "flex";
      } else if (action === "hide") {
        sidepanel.style.display = "none";
      }
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

    handleAttachmentUpload(attachmentUrl) {
      // console.log("running handleAttachmentUpload")

      const newMessage = {
        content: attachmentUrl,
        roomId: this.activeRoom,
        conversationId: this.activeConversation,
        created_at: new Date().toISOString()
      };

      socket.emit("message", newMessage);

      const foundRoom = this.chatrooms[this.getTargetRoomIndex(this.activeRoom)];

      foundRoom.messages.push({
        ...newMessage,
        clientId: this.currentUser._id,
        _id: "",
      });

    },

    hideTitleNotifications() {
      clearInterval(this.titleNotificationInterval);

      document.title = this.origTitle;
    },

    showTitleNotifications(count) {
      // console.log("running notifications")
      // console.log("totalNotifCount to be used", count)

      this.hideTitleNotifications();

      this.titleNotificationInterval = setInterval(() => {
        const pattern = /^\(\d+\)/;
        if (document.title === this.origTitle) {
          if (count === 0 || pattern.test(document.title)) {
            document.title = document.title.replace(pattern, count === 0 ? "" : "(" + count + ") ");
          } else {
            document.title = "(" + count + ") New message(s) received!";
          }
        } else {
          document.title = this.origTitle;
        }
      }, 1500);

      this.audio.play().catch(err => console.error(err));

      // setInterval(() => {
      //   if (document.hasFocus()) {
      //     document.title = this.origTitle;
      //     this.totalNotifCount = 0;
      //   }
      // }, 100);
    },

    formatTimestamp(dateIsoString) {
      const localeOptions = {
        dateStyle: 'medium',
        timeStyle: 'short'
      };

      return new Date(dateIsoString).toLocaleString([], localeOptions);
    },

    toggleFileSharing(chatroom, event) {
      if (!chatroom) return;
      // console.log('toggleFileSharing invoked');
      const allowed = event.target.checked;

      socket.emit('file_sharing_command', {
        roomId: chatroom._id,
        conversationId: chatroom.conversationId,
        allowOrDeny: allowed ? "allow" : "deny"
      });

      chatroom.enable_file_sharing = allowed;
    }
  },
};

</script>
