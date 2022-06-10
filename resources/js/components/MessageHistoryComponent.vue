<template>
    <div class="UploadButton-container">
        <!--HISTORY BLOCK START-->
        <!-- <button class="historyblock" title="Check Transcript" data-bs-toggle="modal" data-bs-target="#CheckTranscript">
            <div>
                <p>Date : <span>History ID</span></p>
            </div>
        </button> -->
        <!--HISTORY BLOCK END-->

        <!-- Check Transcript Modal -->
        <div class="modal" id="CheckTranscript" role="dialog" tabindex="-1">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header chatmodalheader">

                        
                        <h5 class="modal-title chatmodaltitle">Visitor: {{chatroomMembers[0] ? chatroomMembers[0].name : ""}} | Assigned User: {{ chatroomMembers.length === 1 ? "None" : chatroomMembers.slice(1, chatroomMembers.length).reduce((result, member) => result + `${member.name}, `,"").slice(0, -2) }}</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>


                    <div class="modal-body">
                        <div class="historymodaldetails">
                            <span>Conversation: {{ conversation.id }}</span>
                            <p>started: {{ conversation.startAt }}</p>
                            <p>ended: {{ conversation.endAt }} </p>
                        </div>
                        
                            <ul
                                class="list-unstyled"
                                v-for="message in conversation.messages"
                                    :message="message"
                                    :key="message.id">
                                    <li class="d-flex">
                                        <!-- api call returns null for all message.client_name -->
                                        <!-- <p>{{ message.client_type === "user" ? "user" : "visitor" }} <i>{{ message.is_whisper ? "(whisper)" : "" }}</i>: {{ message.content }}</p> -->

                                        <p class="messageline">{{ chatroomMembers.find(member => member.id === message.client_id) ? chatroomMembers.find(member => member.id === message.client_id).name : "" }} <i>{{ message.is_whisper ? "(whisper)" : "" }}</i>: {{ message.content }}</p>

                                        <!-- Timestamp -->
                                        <p class="ms-auto timestamp timestamp--history">{{formatTimestamp(message.created_at)}}</p>

                                    </li>
                                </ul>
                    
                    </div>

                </div>
            </div>
        </div>


    </div>
</template>

<script>
export default {
    props: ['conversation', 'chatroomMembers'],
    // data() {
    //     return {
    //         room: this.chatroom,
    //     };
    // },
    methods: {
        formatTimestamp(dateIsoString) {
            const localeOptions = {
                dateStyle: 'medium',
                timeStyle: 'short'
            };

            return new Date(dateIsoString).toLocaleString([], localeOptions);
        }
    }
}

</script>

<style scoped>



.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: 80vh;
    overflow-y: auto;
}

.modal-body::-webkit-scrollbar-track {
  border: 1px solid white;
  padding: 2px 0;
  background-color: white;
}

.modal-body::-webkit-scrollbar {
  width: 3px;
}

.modal-body::-webkit-scrollbar-thumb {
  border-radius: 10px;
  
  background-color: #fa6121;
  
}


.chatmodalheader{
  background: #627894;

}


.chatmodaltitle{
  color: white;
  font-weight:600;
  font-size: 1em;
  }

.historymodaldetails p{
    color:#627894;
    margin-bottom:0px;
    font-size:0.9em;
}
  
.historymodaldetails span{
    font-weight:600;
    color:#466289;
    font-size:0.9em;
  }

.list-unstyled{
    margin-top:20px;
}

.messageline{
  font-size: .9em;
  color: #3D3E3E;
  margin:0;
  padding: 0;
}


</style>
