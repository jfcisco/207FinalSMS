<template>
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-1 mainsidebar">
            <a class="activemenu" href="/home"
                ><ion-icon name="mail-outline"></ion-icon
                ><span class="menutitle">Messaging</span></a
            >
            <a href="#"
                ><ion-icon name="bar-chart-outline"></ion-icon
                ><span class="menutitle">Reporting</span></a
            >
            <a href="/widgets"
                ><ion-icon name="copy-outline"></ion-icon
                ><span class="menutitle">Widget</span></a
            >
        </div>

        <!--MESSAGE LISTS-->

        <div class="col-lg-2 sidebar" style="overflow-y: scroll">
            <!--INCOMING SESSIONS-->

            <div class="row py-0 mt-0">
                <p class="subtitle">Incoming Sessions</p>

                <!--INCOMING CHAT BLOCK-->
                <!--TO DO: VUE FOR INCOMING MESSAGE TAGGING-->

                <div class="block incoming">
                    <div class="details">
                        <div class="listHead">
                            <p>Incoming user</p>
                            <!-- UNREAD MESSAGES NOTIF
                <b>1</b> -->
                        </div>

                        <!-- The message last sent to the room -->
                        <div class="message_p">
                            <p>last message sent</p>
                        </div>
                    </div>
                </div>
                <!--INCOMING CHAT BLOCK-->
            </div>

            <!--ACTIVE SESSIONS-->

            <div class="row">
                <p class="subtitle sidebartitle">Active Sessions</p>

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
                            <!-- <b>1</b> -->
                        </div>

                        <!-- The message last sent to the room -->
                        <!-- <div class="message_p">
                            <p
                                v-if="
                                    roomMsgs.length > 0 &&
                                    roomMsgs[
                                        getTargetRoomIndex(chatroom.room_id)
                                    ].messages.length > 0
                                "
                            >
                                {{
                                    // Get the last message and convert to string
                                    convertMessageObjectToString(
                                        roomMsgs
                                            .find(
                                                (room) =>
                                                    room.room_id ===
                                                    chatroom.room_id
                                            )
                                            .messages.slice(-1)[0]
                                    )
                                }}
                            </p>
                        </div> -->
                    </div>
                </div>
                <!--ACTIVE CHAT BLOCK-->
            </div>
        </div>

        <!--CHAT DISPLAY-->
        <div class="col-lg-9 mainchat">
            <div
                class="mainchat2"
                v-for="chatroom in chatrooms"
                v-show="chatroom._id == activeRoom"
                :key="chatroom._id"
            >
                <!-- Chat messages-->
                <!-- <div
                    class="card-body chatmessages roomMessages"
                    v-bind:id="'messages_room' + chatroom._id"
                    v-if="chatroom._id == activeRoom"
                > -->
                <div
                    class="card-body chatmessages roomMessages"
                    v-bind:id="'messages_room' + chatroom._id"
                >
                    <div class="list-unstyled">
                        <div class="card card-default">
                            <!-- Chat messages and 'is typing...' -->
                            <div
                                class="card-body chatboxfix p-0 content"
                                :style="{
                                    'background-image':
                                        'url(background_trans.png)',
                                }"
                                v-bind:id="'messages_room' + chatroom._id"
                            >
                                <ul
                                    ref="chatWindow"
                                    class="list-unstyled messages"
                                    style="
                                        height: 560px;
                                        overflow-y: scroll;
                                        overflow-x: hidden;
                                        padding: 0 1.5rem;
                                    "
                                    v-chat-scroll
                                >
                                    <!-- Chatroom Messages -->
                                    <li
                                        class="py-2"
                                        v-for="(
                                            message, index
                                        ) in chatroom.messages"
                                        :key="index"
                                    >
                                        <!-- Only show the text part if it isn't empty  -->
                                        <div
                                            v-if="message.content.length > 0"
                                            class="message"
                                            :class="{
                                                'sent-message':
                                                    message.clientId ===
                                                    user._id,
                                                'received-message':
                                                    !message.clientId ===
                                                    user._id,
                                            }"
                                        >
                                            <!-- <span class="p">
                                                    <strong>
                                                        {{ message._id }}
                                                        {{ message.clientId }}
                                                        {{ message.roomId }}
                                                    </strong>
                                                    <br />
                                                    {{ message.content }}
                                                </span> -->
                                            <div class="name">
                                                {{
                                                    message.clientId ===
                                                    user._id
                                                        ? "You"
                                                        : message.clientId
                                                }}
                                                <!-- TODO: Ask Raymond if the sender/client's name can also be passed instead of just the clientId -->
                                            </div>
                                            <div class="text">
                                                {{ message.content }}
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- CHAT MESSAGE BLOCK -->
                        </div>
                    </div>
                </div>
            </div>

            <!--INPUT MESSAGE BOX-->
            <div class="chatbox_input">
                <input
                    @keyup.enter="sendMessage"
                    v-model="newMessage"
                    type="text"
                    name="message"
                    placeholder="Enter your message..."
                    class="form-control"
                />
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
import FileUploadComponent from "./FileUploadComponent.vue";
import ProfileUpdateComponent from "./ProfileUpdateComponent.vue";

const client = new cj.ClientJS();

const socket = io("https://sms-ws.ml:3000", {
    // secure: true,
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
