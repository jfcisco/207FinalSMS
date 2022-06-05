<template>
    <div>
        <a id=change-name @click="showModal">Update Name</a>

        <!-- Name Update Modal -->
        <transition name="modal-fade">
            <div id="ChangeNameModal" class="modal-backdrop" v-show="isModalVisible" @close="closeModal">
                <div class="modal"
                    role="dialog"
                    aria-labelledby="modalTitle"
                    aria-describedby="modalDescription"
                >
                    <header
                        class="modal-header"
                        id="modalTitle"
                        >
                        <slot name="header">Update Name</slot>
                        <button type="button" class="closeModalBtn" @click="closeModal" aria-label="Close modal">X</button>
                    </header>
                    <section
                        class="modal-body"
                        id="modalDescription"
                    >
                        <form class="container" ref="nameUpdateForm" @submit.prevent="onSubmit()">
                            <div>
                                <label for="visitorName" style="font-size: 14px; color: black;">New Name:</label>
                                <input type="text" name="visitorName" id="visitorName" v-model="visitorName">
                            </div>

                            <div class="text-danger" v-for="error in errors" v-bind:key="error">
                                {{error}}
                            </div>
                            
                            <div class="FileUploadModal-actions gap-2">
                                <button type="submit" class="UploadAndSendBtn" data-bs-dismiss="modal">Save</button>
                            </div>
                        </form>
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
    
    Name: 'Modal',
    emits: ['update-success'],

    data() {
        return {
            errors: [],
            visitorName: '',
            isModalVisible: false,
        }
    },
    // created() {
    //     // Retrieve the current user's details so we can show the current values on the form
    //     axios.get('<?php echo $baseUrl; ?>/api/visitors', {
    //         params: {conversationId: this.room.conversationId}
    //     })
    //         .then(response => {                    
    //             this.visitorName = response.data.visitorId;
    //         });
    // },

    methods: {
        validateInput() {
            let errors = [];

            if (this.visitorName.length === 0) {
                this.errors.push('Please enter a name.');
            }

            return errors;
        },
        
        // Send file to server
        onSubmit() {
            this.errors = this.validateInput();

            // Don't send if there are errors
            if (this.errors.length > 0) {
                return;
            }

            // Prepare to send the file to the server
            let formData = new FormData();
            formData.append('text', this.visitorName);
            this.isUpdating = true;
 
            // Send request to upload file to server
            axios({
                method: 'patch',
                url: "<?php echo $baseUrl; ?>/api/visitors/{visitorId}",
                data: {
                    name: this.visitorName
                }
            });
            // patch("<?php echo $baseUrl; ?>/api/visitors/{visitorId}", formData)
            //     .then(response => {

            //         this.$emit('update-success', fileUrl);

            //         this.closeModal();
            //     })
            //     .catch((err) => {
            //         console.error(err);
            //         this.errors.push('Unable to update name. Please try again later.');
            //     })
            //     .finally(() => {
            //         this.isUpdating = false;
            //     });
            //     window.location.reload();

        },
        close() {
            this.$emit('close');
        },
        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        }
    }
}
</script>