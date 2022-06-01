<template>
    <div class="UploadButton-container">
        <!--HISTORY BLOCK START-->
        <button class="historyblock" title="Check Transcript" data-bs-toggle="modal" data-bs-target="#CheckTranscript">
            <div>
                <p>Date : <span>History ID</span></p>
            </div>
        </button>
        <!--HISTORY BLOCK END-->

        <!-- Check Transcript Modal -->
        <div class="modal" id="CheckTranscript" role="dialog" tabindex="-1">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Transcript</h5>

                        <!-- CONVERSATION DETAILS -->
                        <p>chatroom.conversationId</p>
                        <p>chatroom.conversationStartAt</p>

                        <!-- CONVERSATION DETAILS -->

                    </div>


                    <div class="modal-body">
                        <!-- TRANSCRIPT START -->
                        <ul class="list-unstyled" style="margin-bottom:0">

                            <!-- CHAT MESSAGE LINE START -->
                            <li>
                                <b> insert message-sender-name: </b>
                                <p>&nbsp; message.content </p>
                            </li>
                            <!-- CHAT MESSAGE LINE END -->
                        </ul>
                        <!-- TRANSCRIPT START -->

                    </div>

                </div>
            </div>
        </div>


    </div>
</template>

