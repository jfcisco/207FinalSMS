<template>
    <div class="UploadButton-container">
        <button id=upload title="Attach File" type="button" class="UploadButton" @click="showModal" style="float:right">
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