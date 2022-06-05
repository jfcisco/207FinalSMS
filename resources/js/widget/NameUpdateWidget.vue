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
import cj from "clientjs";

export default {
    
    emits: ['update-success'],

    data() {
        return {
            errors: [],
            visitorName: '',
            visitorId: '',
            isModalVisible: false,
        }
    },
    created() {
        // Retrieve the current user's details so we can show the current values on the form
        const client = new cj.ClientJS();
        this.visitorId = `${client.getFingerprint()}`

        axios.get("<?php echo $baseUrl; ?>/api/visitors/" + this.visitorId)
            .then(response => {                    
                this.visitorName = response.data.data.name;
                console.log("Visitor name is " + this.visitorName +" and Visitor ID is " + this.visitorId);
        });

    },

    methods: {
        validate() {
            this.errors = [];

            if (this.visitorName.length === 0) {
                this.errors.push('Please enter a name.');
            };
        },
        
        // Send file to server
        onSubmit() {
            this.validate();

            // Don't send if there are errors
            if (this.errors.length > 0) {
                return;
            }

            this.isUpdating = true;
 
            // Send request to upload file to server
            axios.patch("<?php echo $baseUrl; ?>/api/visitors/" + this.visitorId, {
                name: this.visitorName
            })
                .then(response => {
                    window.localStorage.setItem(this.visitorId, this.visitorName);
                })
                .catch((err) => {
                    console.error(err);
                    this.errors.push('Unable to update name. Please try again later.');
                })
                .finally(() => {
                    this.isUpdating = false;
                })
            
            alert("Name change successful");
            this.closeModal();
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