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
      <div
        class="block incoming"
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
          incoming: chatroom.members.length === 1
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length === 1 && chatroom.conversationId"
        :key="chatroom._id">

        <div
          class="details"
          v-on:click="selectIncomingRoom(chatroom._id, chatroom.conversationId)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <!-- start here -->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>

          <!-- HIDE BEFORE COMMIT -->
          <!-- show chatroom ids in incoming -->
          <!-- <div class="listHead"> <p>room._id: {{ chatroom._id }}</p> </div> -->
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
      <!-- Display open conversations, which have a conversationId (no endAt property for that conversation) -->
      <div
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="chatroom.members.length > 1 && chatroom.conversationId"
        :key="chatroom._id">

        <div
          class="details"
          v-on:click="selectRoom(chatroom._id, chatroom.conversationId)"
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
          <!-- <div class="listHead"> <p>room._id: {{ chatroom._id }}</p> </div> -->
          <!-- HIDE BEFORE COMMIT -->

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
    <!--ACTIVE SESSIONS END-->

    <!--CLOSED SESSIONS START-->
    <p class="subtitle sidebartitle">Closed Chats</p>
    <div class="row inactivesessions" style="overflow-y: scroll">

      <!--CLOSED CHAT BLOCK START-->
      <!-- display ended conversations, which have no conversationId (with an endAt property for that conversation) -->
      <div
        :class="{
          block: true,
          active: chatroom._id == activeRoom,
        }"
        v-for="chatroom in chatrooms"
        :chatroom="chatroom"
        v-show="!chatroom.conversationId"
        :key="chatroom._id">
        <div
          class="details"
          v-on:click="selectClosedRoom(chatroom._id)"
          v-bind:id="chatroom._id">

          <!--room id/username section-->
          <div class="listHead">
            <p>{{ chatroom.members[0].clientName }}</p>
          </div>

          <!-- HIDE BEFORE COMMIT -->
          <!-- show chatroom ids in active sessions -->
          <!-- <div class="listHead">
            <p>room._id: {{ chatroom._id }}</p>
          </div> -->
          <!-- HIDE BEFORE COMMIT -->

          <!-- assigned room users (Admin/Agent) -->
          <div class="listHead">
            <p style="font-weight: 500; font-style: italic;">
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
  <div class="col-lg-7 col-sm-5 mainchat">

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

    <!--HISTORY BLOCK START-->
    <!-- <MessageHistoryComponent></MessageHistoryComponent> -->
    <!-- <button id="historyModalBtn" class="historyblock" title="Check Transcript" data-bs-toggle="modal" data-bs-target="#CheckTranscript" style="display:none">
      <div>
        <p>CHAT HISTORY</p>
      </div>
    </button> -->
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
        []"
    ></MessageHistoryComponent>

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

          <div>
            <h4>{{ chatroom.members[0].clientName }}</h4>
          </div>

          <div class="mb-3">
            <h5 class="assignedmembers">
              {{ chatroom.conversationId ? "Assigned" : "Previously Assigned" }}: {{ getAssignedToRoom(chatroom) ? getAssignedToRoom(chatroom) : "None" }}
            </h5>
            <h6 class="lastconversation">{{ chatroom.conversationId ? "" : "Last Conversation" }}</h6>
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
                        <p v-else><a :href="message.content">&nbsp;{{ message.content.slice(6, message.content.length) }}</a></p>
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
            v-if="chatroom.conversationId && chatroom.members.filter(member => member.clientId === currentUser._id).length === 0"
            v-on:click="joinRoom(chatroom._id, chatroom.conversationId)">
            <span class="joinroom">Click to join room</span>
          </button>

      </div>
    </div>
  </div>
  <!--MAIN CHAT WINDOW END-->

  <!--CHAT HISTORY-->
  <div class="col-lg-2 col-sm-3">
    <!--CHAT HISTORY-->
    <div
      class="mainchat2"
      v-for="chatroom in chatrooms"
      :chatroom="chatroom"
      v-show="chatroom._id == activeRoom"
      :key="chatroom._id">

            <div class="chathistoryfull">

              <div class="row mt-5">
                <div class="chathistory"> {{ chatroom.members[0].clientName }}</div>
                <div class="chathistory"> visitor location/country</div>
                <div class="chathistory"> visitor IP</div>
                <div class="chathistory"> visitor device details</div>
              </div>


              <div class="row" style="text-align:center">
                <div class="col chathistory">DUR</div>
                <div class="col chathistory">VLV</div>
                <div class="col chathistory">NOC</div>
              </div>

              <div class="row">
                <div class="chathistory mt-2">
                  <button
                    style="font-weight:600; font-size:16px"
                    v-on:click="populateMessageHistoryList(chatroom)"
                    v-bind:id="'msg-list-btn'+chatroom._id"
                  >
                    Chat History
                  </button>

                  <!--HISTORY LIST START-->
                  <div class="chathistorylist" style="overflow-y: scroll" >
                    <!--HISTORY BLOCK START-->
                    <!-- <MessageHistoryComponent></MessageHistoryComponent> -->
                    <div
                      v-for="conversation in chatroomsAPIData.find(room => room.id === chatroom._id) ?
                        chatroomsAPIData.find(room => room.id === chatroom._id).conversations :
                        []"
                      :key="conversation.id"
                      :conversation="conversation"
                    >
                      <button
                        class="historyblock"
                        title="Check Transcript"
                        data-bs-toggle="modal"
                        data-bs-target="#CheckTranscript"
                        v-on:click="() => { activeConversationHistoryId = conversation.id }"
                      >
                        <div>
                            <span>Convo Id: {{ conversation.id }}</span>
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
  props: ["user", "crm"],

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
      chtRoom: this.crm,
      activeConversation: "",
      activeConversationHistoryId: "",
      chatroomsUnreadNotif: {},
      totalNotifCount: 0,
      origTitle: document.title,
      audio: new Audio("http://soundjax.com/reddo/88877%5EDingLing.mp3"),
      titleNotificationInterval: undefined,
    };
  },

  created() {
    if(this.crm===null){

    }else{
      this.activeRoom = this.crm;
    };
    console.log("adrian",this.crm);

    window.addEventListener("focus", () => {
      console.log("document is in focus");

      // if (this.activeRoom) {
      //   console.log("1=>", this.chatroomsUnreadNotif[this.activeRoom])
      //   this.chatroomsUnreadNotif[this.activeRoom] = 0
      //   console.log("2=>", this.chatroomsUnreadNotif[this.activeRoom])
      // }
      this.totalNotifCount = 0;
      this.hideTitleNotifications();
    });

    socket.auth = {
      // // For visitors
      // clientId: client.getFingerprint(),
      // clientType: "visitor",
      // clientName: "bisita",
      // widgetId: "widget1",

      // For admin/agent
      clientId: this.currentUser._id,
      clientName: `${this.currentUser.name}`,
      clientType: "user",
    };

    socket.connect();

    socket.on("rooms", ({ rooms }) => {
      console.log("running socket.on rooms1")
      console.log("rooms full data=> ", rooms);
      this.chatrooms = _.unionBy(
        rooms,
        this.chatrooms,
        (room) => room._id,
      );
      console.log("this.chatrooms=>", this.chatrooms);

      // initialize chatroomsUnreadNotif and chatroomsAPIData
      rooms.map(room => {
        this.chatroomsUnreadNotif[`${room._id}`] = 0;
      });
      // rooms.map(room => {
      //   this.chatroomsAPIData[`${room._id}`] = {
      //     id: room._id,
      //     members: [],
      //     conversations: []
      //   };
      // });
      // console.log("chatroomsUnreadNotif", this.chatroomsUnreadNotif)
      // console.log("test", this.chatroomsUnreadNotif["46c3823aaa58ac14"])
      // console.log("test", this.chatroomsAPIData["c939991f5a528485"])
    });

    // get all rooms from mongodb
    // this.generateChatroomsList();

    socket.on("message", (message) => {
      console.log("message received", message);

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
        // let total = 0;
        // for (let key in this.chatroomsUnreadNotif) {
        //   if (this.chatroomsUnreadNotif.hasOwnProperty(key)){
        //     total += this.chatroomsUnreadNotif[key];
        //     console.log("here", total)
        //   }
        // }
        // console.log("totalNotifCount", total);
        // this.showTitleNotifications(total);
        this.showTitleNotifications(++this.totalNotifCount);

      }
    });

    socket.on("typing", (data) => {
      // Leaving this log for debugging:
      console.log("typing data => ", JSON.parse(JSON.stringify(data)));

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

    socket.on("end_chat", conversationId => {
      console.log("visitor ended conversation", conversationId);
      this.chatrooms.forEach(chatroom => {
        if (chatroom.conversationId === conversationId) {
          console.log("found the conversation");
          chatroom.conversationId = undefined;
        }
        console.log(this.chatrooms)
        return;
      });
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
    // async getAllRooms() {
    //   try {
    //     const response = await axios.get("/api/rooms");
    //     // console.log("response GET api/rooms", response.data.data.map(
    //     //   item => ({ "roomId": item.id, "members": item.members.map(i => `${i.name} clientId(${i.id})`), "messages": item.messages })));
    //     // console.log("look here", response.data.data)
    //     return response.data.data;
    //   } catch (err) {
    //     console.error(err);
    //   }
    // },

    async getRoom(roomId) {
      console.log("executing getRoom")
      try {
        const response = await axios.get(`/api/rooms/${roomId}`);
        console.log("getRoom", response.data.data);
        return response.data.data;
      } catch (err) {
        console.error(err);
      }
    },

    // UTILITY FUNCTIONS
    // async generateChatroomsList() {
    //   try {
    //     console.log("running generateChatroomsList");
    //     const results = await this.getAllRooms();
    //     console.log("api call response", results);

    //     console.log("this.chatrooms before api call", this.chatrooms)

    //     this.chatrooms = _.unionBy(
    //       this.convertSchemaOfAPIRoomsResponse(results),
    //       this.chatrooms,
    //       (room) => room._id,
    //     );
    //     console.log("this.chatrooms after api call", this.chatrooms)

    //   } catch (err) {
    //     console.error(err);
    //   }
    // },

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
        // console.log("chatroom", chatroom.messages.length);
        const lastMsg = chatroom.messages[chatroom.messages.length - 1];
        const msgSender = this.getMsgSender(lastMsg, chatroom)
        const msg = lastMsg.content;

        return `${msgSender}: ${msg}`
      }
    },

    getMsgSender(msg, chatroom) {
      return msg.clientId === this.currentUser._id ?
        "You" :
        chatroom.members.filter(member => member.clientId === msg.clientId)[0].clientName;
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
        conversationId: this.activeConversation,
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
        console.log("message", newMessage)
      }
      // console.log("newMessage2=> ", newMessage);

      // Add message to UI
      let found = this.getTargetRoomIndex(this.activeRoom);
      // console.log("this.chatrooms=> ", this.chatrooms[found].messages)
      this.chatrooms[found].messages.push({
        ...newMessage,
        clientId: this.currentUser._id,
        _id: "",
      });
      // console.log("this.chatrooms2=> ", this.chatrooms[found].messages)

      this.message = "";
    },

    // sendTypingEvent() {
    //     Echo.join("chat").whisper("typing", this.user);
    // },

    selectRoom: function(roomId, conversationId) {
      console.log("running selectRoom");
      this.activeRoom = roomId;
      this.activeConversation = conversationId;
      console.log("conversationId", conversationId)
      this.scrollToChatBottom();
      this.toggleMessageMainEl("show");
      this.toggleMsgHistoryListBtn(roomId, conversationId);

      // clear selected room's notification count
      this.chatroomsUnreadNotif[roomId] = 0;

      // adjust title notification
      this.hideTitleNotifications();

      // try {
      //   const results = [];
      //   results.push(await this.getRoom(roomId));
      //   console.log("start here", results)
        // this.chatroomsAPIData[`${roomId}`] = results;
        // console.log(this.chatroomsAPIData[`${roomId}`])
        // this.chatroomsAPIData = _.unionBy(
        //   this.chatroomsAPIData,
        //   results,
        //   (room) => room.id,
        // );
        // console.log("check", this.chatroomsAPIData);
        // document.getElementById("historyModalBtn").style.display = "";

      // } catch (err) {
      //   console.error(err);
      // }
    },

    selectIncomingRoom: function (roomId, conversationId) {
      console.log("running selectIncomingRoom");
      // console.log("incoming block div of chatroom ", document.getElementById(`${roomId}`).parentElement)
      event.preventDefault();
      this.selectRoom(roomId, conversationId);

      // join the selected room
      this.joinRoom(roomId, conversationId)
    },

    selectClosedRoom: async function(roomId) {
      console.log("running selectClosedRoom");
      event.preventDefault();
      try {
        const results = await this.getRoom(roomId);

        this.populateMessageHistoryList(results);

        this.selectRoom(roomId, undefined);

        // console.log("results", results);
        // console.log("this.chatrooms before=>", this.chatrooms);
        const foundRoom = this.chatrooms[this.getTargetRoomIndex(roomId)];
        const lastConversation = results.conversations[results.conversations.length - 1]

        foundRoom.messages = this.convertConvoMsgSchema(roomId, lastConversation);
        // console.log("this.chatrooms after=>", this.chatrooms);



      } catch (err) {
        console.error(err);
      }
      this.toggleMessageMainEl("hide");
    },

    joinRoom: function(roomId, conversationId) {

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
        console.log("found chatroom after joining", foundRoom);
        console.log("conversationId", conversationId)

        socket.emit("join", { roomId: roomId, conversationId: conversationId, name: this.currentUser.name });

        alert("Successfully joined this room");

        this.toggleMessageMainEl("show");
      }
    },

    toggleMsgHistoryListBtn(roomId, conversationId) {
      if (conversationId) {
        const msgHistoryListBtnEl = document.getElementById(`msg-list-btn${roomId}`);
        console.log(msgHistoryListBtnEl)
        msgHistoryListBtnEl.disabled = false;
      } else {
        const msgHistoryListBtnEl = document.getElementById(`msg-list-btn${roomId}`);
        console.log(msgHistoryListBtnEl);
        msgHistoryListBtnEl.disabled = true;
      }
    },

    populateMessageHistoryList: async function(chatroom) {
      console.log("running populateMessageHistoryList");
      console.log(chatroom)
      try {
        const results = [];
        if (chatroom.conversationId) {
          // if it is an active or incoming chat, get request to "api/room/{roomId}"
          console.log("chatroom.conversationId is truthy");
          results.push(await this.getRoom(chatroom._id));
        } else {
          // closed chat since no conversationId
          // if it is a closed chat, we already have result from get request to "api/room/{roomId}"
          //    passed as param when selectClosedRoom executes
          console.log("chatroom.conversationId is falsy", chatroom.conversationId);
          results.push(chatroom);

        }
        console.log("start here", results)
        this.chatroomsAPIData = _.unionBy(
          this.chatroomsAPIData,
          results,
          (room) => room.id,
        );
        console.log("check", this.chatroomsAPIData);
      } catch(err) {
        console.error(err);
      }
    },

    toggleMessageMainEl(action) {
      console.log("running toggleMessageMainEl")
      const msgMainEl= document.getElementById("message_main");
      if (action === "show") {
        msgMainEl.style.display = "flex";
      } else if (action === "hide") {
        msgMainEl.style.display = "none";
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
      console.log("running handleAttachmentUpload")

      const newMessage = {
        content: attachmentUrl,
        roomId: this.activeRoom,
        conversationId: this.activeConversation,
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

      this.audio.play().catch(err => console.log(err));

      // setInterval(() => {
      //   if (document.hasFocus()) {
      //     document.title = this.origTitle;
      //     this.totalNotifCount = 0;
      //   }
      // }, 100);
    }

  },
};

</script>
