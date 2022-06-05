<template>
    <div>
        <!-- HEADER -->
        <h2>
            <ul>
                <li>Conversation</li>
                <!-- END CHAT BUTTON -->
                <li style="float:right" v-if="!chatEnded">
                    <div class="dropdown">
                        <button @click="toggleDropdown()"><i class="material-icons">menu</i></button>
                        <div id="dropdown-menu" class="dropdown-content hidden">
                            <NameUpdateWidget></NameUpdateWidget>
                            <a id=exit-chat @click="endConversation()">End Conversation</a>
                        </div>
                    </div>
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
                <div class="message-input">
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
                            @input="sendTypingEvent"
                        />
                    </form>
                </div>
                <div v-if="room.enable_file_sharing" class="file-sharing">
                    <FileUploadWidget
                    v-on:upload-success="handleAttachmentUpload"
                    ></FileUploadWidget>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* CSS rules can be added here */

</style>

<script>
import FileUploadWidget from "./FileUploadWidget.vue";
import NameUpdateWidget from "./NameUpdateWidget.vue";
import { socket } from "./socket";


export default {
    props: {
        visitorName: String,
        room: Object,
    },

    components: {
        FileUploadWidget,
        NameUpdateWidget
    },

    data() {
        return {
            chatEnded: false,
            message: "",
            isFileSharingEnabled: false,
            dropdownShown: false
        };
    },

    created() {
        // Start the conversation
        socket.emit("start_convo", {
            conversationId: this.room.conversationId
        }, (error, startedConvo) => { 
            if (!error) {
                this.room.conversation = startedConvo;
            }
            else {
               console.error(error);
            }
        });

        // Listen for file-sharing events
        socket.on("file_sharing", ({ allowed }) =>  {
            this.room.enable_file_sharing = allowed;
        });
        
        // Log any connect_errors
        socket.on("connect_error", (err) => {
            console.error(err);
        });

        this.focusOnMessageInput();
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
            // console.log("room._id=> ", this.room._id);

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
            // console.log("room._id=> ", this.room._id);

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
            }
            // console.log("Send typing data: ", typingData);
            socket.emit("typing", typingData);
        },

        toggleDropdown() {
            this.dropdownShown = !this.dropdownShown;
            
            if (!this.dropdownShown) {
                document.getElementById("dropdown-menu").classList.add("hidden");
            } else {
                document.getElementById("dropdown-menu").classList.remove("hidden");
            }
        },
    },

};
</script>
