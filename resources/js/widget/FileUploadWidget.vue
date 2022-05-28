<template>
    <div class="UploadButton-container">
        <button id=upload title="Upload Image" type="button" class="UploadButton" @click="showModal" style="float:right">
            <i class="material-icons" style="font-size:22px;">attach_file</i>
        </button>

        <!-- File Dialog Modal -->
        <transition name="modal-fade">
            <div id="FileUploadModal" class="modal-backdrop" v-show="isModalVisible" @close="closeModal">
                <div class="modal"
                    role="dialog"
                    aria-labelledby="modalTitle"
                    aria-describedby="modalDescription"
                >
                    <header
                        class="modal-header"
                        id="modalTitle"
                        >
                        <slot name="header">Upload a File</slot>
                        <button type="button" class="closeModalBtn" @click="closeModal" aria-label="Close modal">X</button>
                    </header>
                    <section
                        class="modal-body"
                        id="modalDescription"
                    >
                        <slot name="body">
                            <form class="container" ref="uploadForm">
                                <div class="form-group">
                                    <label for="attachment">Attachment: </label>
                                    <input class="form-control UploadField" type="file" v-bind:disabled="isUploading" accept="file/*" name="attachment" id="attachment" v-on:change="onFileChange($event)">
                                </div>

                                <div class="text-danger" v-for="error in errors" v-bind:key="error">
                                    {{error}}
                                </div>

                                <div class="FileUploadModal-actions gap-2">
                                    <button type="button" v-bind:disabled="isUploading" class="UploadAndSendBtn" v-on:click="uploadFile()">
                                        <!-- Spinner -->
                                        <span class="spinnerButton" role="status" v-if="isUploading"></span>
                                        <!-- Spinner -->
                                        <span v-else>Upload &amp; Send</span>
                                    </button>
                                    <button type="button" class="cancelButton" style="height: 50%;" @click="closeModal" v-bind:disabled="isUploading">Cancel</button>
                                </div>
                            </form>
                        </slot>
                    </section>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
    .UploadButton {
        max-height: min-content;
        padding: 10px;
        color:#466289;
        background: none;
        border: none;
        justify-content: center;
        align-items: center;
        float: right;
    }

    .UploadButton:hover {
        color:#FA6121;
        cursor: pointer;
    }

    .closeModalBtn {
        background-color: #466289;
        border: none;
        color: white;
    }

    .spinnerButton {
        transition: all 0.2s;
    }

    .closeModalBtn:hover {
        color: #FA6121;
        cursor: pointer;
    }

    .UploadField {
        padding: 0.375rem 0.75rem;
        margin: 0;
    }

    .UploadAndSendBtn {
        width: 40%;
    }

    .UploadAndSendBtn--loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        border: 4px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: button-loading-spinner 1s ease infinite;
    }

    @keyframes button-loading-spinner {
        from {
            transform: rotate(0turn);
        }

        to {
            transform: rotate(1turn);
        }
    }

    .cancelButton {
        background-color: lightgrey;
        width: 40%;
    }

    .cancelButton:hover {
        background-color: grey;
    }

    .FileUploadModal-actions {
        display: flex;
        flex-flow: row nowrap;
        justify-content: space-evenly;
        margin-top: 0.75rem;
    }

    .FileUploadModal-actions > .btn {
       flex: 1; 
    }

    /* MODAL */

    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        border-radius: 5px;
    }

    .modal-header,
    .modal-footer {
        padding: 15px;
        display: flex;
    }

    .modal-header {
        position: relative;
        border-bottom: 1px solid #eeeeee;
        color: white;
        background-color: #466289;
        justify-content: space-between;
    }

    .modal-footer {
        border-top: 1px solid #eeeeee;
        flex-direction: column;
    }

    .modal-body {
        position: relative;
        padding: 20px 10px;
    }

    .modal-fade-enter,
    .modal-fade-leave-to {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity .5s ease;
    }
    

</style>

<script>
import axios from 'axios';

export default {
    
    name: 'Modal',
    
    emits: ['upload-success'],

    data() {
        return {
            errors: [],
            isUploading: false,
            attachmentFile: null,
            isModalVisible: false,
        }
    },

    methods: {
        // Handle changes in file input
        onFileChange(event) {
            const files = event.target.files;
            if (files.length > 0) {
                // Just get the first file uploaded
                this.attachmentFile = files[0];
            }
            else {
                this.attachmentFile = null;
            }
        },

        validateInput() {
            let errors = [];

            if (!this.attachmentFile) {
                errors.push("Please choose a file.");
            }

            return errors;
        },
        
        // Send file to server
        uploadFile() {
            this.errors = this.validateInput();

            // Don't send if there are errors
            if (this.errors.length > 0) {
                return;
            }

            // Prepare to send the file to the server
            let formData = new FormData();
            formData.append('file', this.attachmentFile, this.attachmentFile.name);
            this.isUploading = true;
 
            // Send request to upload file to server
            axios.post("<?php echo $baseUrl; ?>/api/upload-file", formData)
                .then(response => {

                    const fileUrl = response.data.data;

                    // Fire an event to the parent component
                    this.$emit('upload-success', fileUrl);

                    // Reset the form
                    this.$refs.uploadForm.reset();

                    // Close the modal
                    this.closeModal();
                    this.attachmentFile = null;
                })
                .catch((err) => {
                    console.error(err);
                    this.errors.push('Unable to upload file. Please try again later.');
                })
                .finally(() => {
                    this.isUploading = false;
                });
        },
        close() {
            this.$emit('close');
        },
        showModal() {
            this.isModalVisible = true;
            const theButton = document.querySelector(".UploadAndSendBtn");
            theButton.addEventListener("click", () => {
                theButton.classList.add("UploadAndSendBtn--loading");
            });
        },
        closeModal() {
            this.isModalVisible = false;
        }
    }
}
</script>