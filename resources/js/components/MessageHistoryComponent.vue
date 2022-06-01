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

                        <h5 class="modal-title">clientName</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>


                    <div class="modal-body">

                        <!-- CONVERSATION DETAILS START -->
                        <div class="historydetails">
                            <p>ID:&nbsp;conversationId&nbsp;|&nbsp;</p>
                            <p>Start At:&nbsp;conversationStartAt&nbsp;|&nbsp;</p>
                            <p>End At:&nbsp;conversationEndAt</p>
                        </div>
                        <!-- CONVERSATION DETAILS END-->


                        <div class="transcriptheight" style="overflow-y: scroll">
                            <!-- TRANSCRIPT START -->
                            <ul class="list-unstyled" style="margin-bottom:0">

                                <!-- CHAT MESSAGE LINE START -->
                                <li>
                                    <div class="messageline">
                                        <b> insert message-sender-name: </b>
                                        <p>&nbsp; message.content </p>
                                    </div>
                                </li>
                                <!-- CHAT MESSAGE LINE END -->

                            </ul>
                            <!-- TRANSCRIPT START -->

                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</template>
