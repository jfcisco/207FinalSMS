<template>
    <div class="UploadButton-container">
        <button id=upload title="Upload Image" type="button" class="UploadButton" data-bs-toggle="modal" data-bs-target="#FileUploadModal" style="float:right">
            <i class="material-icons" style="font-size:22px;">attach_file</i>
        </button>

        <!-- File Dialog Modal -->
        <div id="FileUploadModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">Upload a File</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form class="container" ref="uploadForm">
                            <div class="form-group">
                                <label for="attachment">Attachment: </label>
                                <input class="form-control UploadField" type="file" v-bind:disabled="isUploading" accept="image/*" name="attachment" id="attachment" v-on:change="onFileChange($event)">
                            </div>

                            <div class="text-danger" v-for="error in errors" v-bind:key="error">
                                {{error}}
                            </div>

                            <div class="FileUploadModal-actions gap-2">
                                <button type="button" v-bind:disabled="isUploading" class="btn btn-primary UploadAndSendBtn" v-on:click="uploadFile()">
                                    <!-- Spinner -->
                                    <span class="spinner-border text-light spinner-border-sm" role="status" v-if="isUploading">
                                        <span class="visually-hidden">Uploading...</span>
                                    </span>
                                    <!-- Spinner -->

                                    <span>Upload &amp; Send</span>
                                </button>
                                <button type="button" class="btn btn-secondary" style="height: 50%;" data-bs-dismiss="modal" v-bind:disabled="isUploading">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

    .UploadField {
        padding: 0.375rem 0.75rem;
        margin: 0;
    }

    .UploadAndSendBtn {
        background-color: #e06822;
        color: whitesmoke;
        border: none;
        padding: 5px;
        width: 50%;
    }

    .UploadAndSendBtn:hover{
        background-color:#1a9988;
        color: whitesmoke;
        border: none;
        padding: 5px;
        width: 50%;
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }
    .modal-content {
        position: relative;
        background-color: #ffffff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid #999999;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
        outline: 0;
    }
    @media (min-width: 768px) {
        .modal-dialog {
            width: 600px;
            margin: 30px auto;
        }
        .modal-content {
            -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        .modal-sm {
            width: 300px;
        }
        }
        @media (min-width: 992px) {
        .modal-lg {
            width: 900px;
        }
    }
    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #e5e5e5;
    }
    .modal-header .close {
        margin-top: -2px;
    }
    .modal-title {
        margin: 0;
        line-height: 1.42857143;
    }
    .modal-body {
        position: relative;
        padding: 15px;
    }
    .modal-footer {
        padding: 15px;
        text-align: right;
        border-top: 1px solid #e5e5e5;
    }


</style>

<script>
export default {
    props: ['active-room'],

    emits: ['upload-success'],

    data() {
        return {
            errors: [],
            isUploading: false,
            attachmentFile: null,
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
                errors.push("Please upload a file.");
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
            formData.append('attachment', this.attachmentFile, this.attachmentFile.name);
            formData.append('room_id', this.$props.activeRoom);
            this.isUploading = true;

            // Send request to upload file to server
            axios.post('messages', formData)
                .then(response => {
                    const imageUrl = response.data.imageUrl;

                    // Fire an event to the parent component
                    this.$emit('upload-success', imageUrl);

                    // Reset the form
                    this.$refs.uploadForm.reset();

                    // Close the modal
                    var modalElement = document.getElementById("FileUploadModal");
                    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.hide();
                })
                .catch((err) => {
                    console.error(err);
                    this.errors.push('Unable to upload file. Please try again later.');
                })
                .finally(() => {
                    this.isUploading = false;
                });
        }
    }
}
</script>