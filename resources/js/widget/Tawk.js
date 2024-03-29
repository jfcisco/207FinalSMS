import Vue from 'vue';
import VueChatScroll from "vue-chat-scroll";
import cj from "clientjs";
import axios from 'axios';
import { socket } from './socket';
import { computeCssFilters } from "./css-color-filter-generator";

// Options used for converting date/time to string using .toLocaleTimeString()
const localeTimeFormat = {
    hour: '2-digit',
    minute: '2-digit'
}

export class Tawk {
    constructor({ position = 'bottom-right',
        baseUrl,
        widgetId,
        hasScheduledAvailability,
        availabilityStartTime,
        availabilityEndTime,
        schedulerEnabledForToday,
        afterHoursMessage,
        color,
        icon,
    } = {}
    ) {
        this.baseUrl = baseUrl;
        this.widgetId = widgetId;
        this.position = this.getPosition(position);
        this.open = false;
        this.sessionStarted = false;
        this.client = new cj.ClientJS();

        // Variables for availability schedule
        this.hasScheduledAvailability = hasScheduledAvailability;
        this.availabilityStartTime = availabilityStartTime;
        this.availabilityEndTime = availabilityEndTime;
        this.schedulerEnabledForToday = schedulerEnabledForToday;
        this.afterHoursMessage = afterHoursMessage
            ? afterHoursMessage.trim()
            : "";

        // this.color (string): 7-character hexadecimal color code (e.g., "#fdee3f")
        this.color = color; 

        // this.icon(string): URL to the chosen icon (e.g., "/assets/new-icon.svg")
        this.icon = icon;
        
        this.room = null;

        this.initialise();
        this.createStyles();
    }

    getPosition(position) {
        const [vertical, horizontal] = position.split('-');
        return {
            [vertical]: '30px',
            [horizontal]: '30px'
        };
    }

    initialise() {
        const container = document.createElement('div');
        container.style.position = 'fixed';
        Object.keys(this.position)
            .forEach(key => container.style[key] = this.position[key]);
        document.body.appendChild(container);

        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container')

        const chatIcon = document.createElement('img');
        chatIcon.src = `${this.baseUrl}`+this.icon;
        chatIcon.classList.add('icon');
        this.chatIcon = chatIcon;

        const closeIcon = document.createElement('img');
        closeIcon.src = `${this.baseUrl}/assets/cross.svg`;
        closeIcon.classList.add('icon', 'hidden');
        this.closeIcon = closeIcon;

        buttonContainer.appendChild(this.chatIcon);
        buttonContainer.appendChild(this.closeIcon);
        buttonContainer.addEventListener('click', this.toggleOpen.bind(this));

        this.messageContainer = document.createElement('div');
        this.messageContainer.classList.add('hidden', 'message-container');

        this.checkTime();

        container.appendChild(this.messageContainer);
        container.appendChild(buttonContainer);

        // Setup focus listener
        window.addEventListener("focus", () => {
            // console.log("document is in focus");
            this.notifCount = 0;
            this.hideNotifications();
        });

        this.audio = new Audio("https://soundjax.com/reddo/88877%5EDingLing.mp3");
        this.origTitle = document.title;

        this.initialiseSocket();
    }

    initialiseSocket() {
        // Setup client.js for device fingerprinting
        const client = new cj.ClientJS();
        const visitorId = `${this.client.getFingerprint()}`
        const visitorName = window.localStorage.getItem(visitorId);
        
        // Setup auth for connection
        socket.auth = {
            clientId: `${client.getFingerprint()}`,
            clientType: "visitor",
            clientName: visitorName,
            widgetId: this.widgetId,
            currentPage: {
                title: document.title,
                url: window.location.href, 
            }
        };

        socket.connect();

        // Get rooms data
        socket.on("rooms", ({ rooms }) => {
            // Get the room and its accompanying information
            let room = rooms[0];

            // Process the room's messages, attaching additional properties we need
            room.messages = room.messages
                .filter(msg => !msg.isWhisper)
                .map(message => ({
                    ...message,
                    senderName: room.members.find(member => message.clientId === member.clientId).clientName,
                    isUpdate: false,
                    fromSelf: message.clientId === socket.auth.clientId,
                }));

            this.room = room;
            // console.log("Got room ", this.room);
        });

        // Received notification that an admin/agent has joined the room
        socket.on("join", (notification) => {
            const update = {
                isUpdate: true,
                content: notification,
            };
            this.room.messages.push(update);
        });

        // Received information about user that joined
        socket.on("user_joined", (user) => {
            if (user.roomId === this.room._id) {
                this.room.members.push(user);
            }
        });

        socket.on("convo_started", (conversation) => {
            if (!this.open) {
                this.toggleOpen();
            }

            if (!this.conversationStarted) {
                this.startConversation();
            }
        })

        // Listen to any sent messages
        let previousChatMessage = "";
        socket.on("message", (message) => {
            const chatMessage = {
                ...message,
                senderName: this.room.members.find(member => message.clientId === member.clientId).clientName,
                isUpdate: false,
                fromSelf: message.clientId === socket.auth.clientId,
            };

            this.room.messages.push(chatMessage);

            // Show notifications in the document's title tag
            if (!document.hasFocus()) {
                if (chatMessage != previousChatMessage) {
                    this.notifCount++;
                    this.showNotifications(this.notifCount);
                }
            }
            previousChatMessage = chatMessage;
        });
    }

    createMessageContainerContent() {
        this.messageContainer.innerHTML = '';
        const title = document.createElement('h2');
        title.textContent = `SMS`;

        const form = document.createElement('form');
        form.classList.add('content');

        const welcome = document.createElement('div');
        welcome.classList.add('welcome');
        welcome.textContent = `Enter your name and start chatting with us.`;

        const name = document.createElement('input');
        name.required = true;
        name.id = 'name';
        name.type = 'name';
        name.placeholder = 'Your name';

        const space = document.createElement('br');

        const btn = document.createElement('button');
        btn.textContent = 'Start a Conversation';

        form.appendChild(welcome);
        form.appendChild(name);
        form.appendChild(space);
        form.appendChild(btn);
        form.addEventListener('submit', this.submit.bind(this));

        this.messageContainer.appendChild(title);
        this.messageContainer.appendChild(form);
    }

    createMessageContainerContentAfterHours() {
        this.messageContainer.innerHTML = '';
        const title = document.createElement('h2');
        title.textContent = `We're not here...`;

        const afterhoursmessage = document.createElement('div');
        afterhoursmessage.classList.add('content');
        afterhoursmessage.textContent = `Our agents are available from ${this.availabilityStartTime.toLocaleTimeString([], localeTimeFormat)} to ${this.availabilityEndTime.toLocaleTimeString([], localeTimeFormat)}.  Let's chat again during those hours!`;

        this.messageContainer.appendChild(title);
        this.messageContainer.appendChild(afterhoursmessage);
        
        if (this.afterHoursMessage) {
            const customMessage = document.createElement('div');
            customMessage.classList.add('content');
            customMessage.textContent = this.afterHoursMessage;
            this.messageContainer.appendChild(customMessage);
        }
    }

    createMessageContainerContentUnavailableAllDay() {
        this.messageContainer.innerHTML = '';
        const title = document.createElement('h2');
        title.textContent = `We're not here today...`;

        const afterhoursmessage = document.createElement('div');
        afterhoursmessage.classList.add('content');
        afterhoursmessage.textContent = `Our agents are unavailable today. Let's chat on another day!`;

        this.messageContainer.appendChild(title);
        this.messageContainer.appendChild(afterhoursmessage);
        
        if (this.afterHoursMessage) {
            const customMessage = document.createElement('div');
            customMessage.classList.add('content');
            customMessage.textContent = this.afterHoursMessage;
            this.messageContainer.appendChild(customMessage);
        }
    }

    createStyles() {
        const styleTag = document.createElement('style');
        styleTag.innerHTML = `
            @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@200;400&display=swap');

            .message-container * {
                font-family: 'Raleway', sans-serif;
            }

            .button-container {
                background-color: none;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                font-color: ${this.color};
            }

            .icon {
                filter: ${computeCssFilters(this.color).result.filterRaw};
                cursor: pointer;
                width: 70%;
                position: absolute;
                top: 9px;
                left: 9px;
                transition: transform .3s ease;
                fill: ${this.color};
                stroke: ${this.color};
                font-color: ${this.color};
            }

            .hidden {
                transform: scale(0);
            }
            
            .message-container {
                box-shadow: 0 0 18px 8px rgba(0, 0, 0, 0.1), 0 0 32px 32px rgba(0, 0, 0, 0.08);
                background-color: #E9EDEE;
                width: 400px;
                right: -5px;
                bottom: 75px;
                max-height: 500px;
                position: absolute;
                transition: max-height .2s ease;
                border-radius: 5px;
            }

            .message-container.hidden {
                max-height: 0px;
            }

            .message-container h2 {
                margin: 0;
                padding: 20px 20px;
                color: #fff;
                background-color: ${this.color};
                border-top-right-radius: 5px;
                border-top-left-radius: 5px;
            }
            
            .message-container h2 ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }

            .message-container h2 ul li{
                display: inline;
            }

            .message-container h2 ul li button{
                background-color: none;
                font-size: 12px;
                border: none;
                color: white;
                padding: none;
            }

            .message-container h2 ul li button:hover{
                background-color: lightgray;
                cursor: pointer;
                color: ${this.color};
            }

            .message-container .content {
                margin: 20px 10px ;
                padding: 10px;
                display: flex;
                height: 80%;
                background-color: #E9EDEE;
                flex-direction: column;
            }

            .message-container .content .welcome {
                vertical-align: middle;
            }

            .message-container .content .messages {
                overflow-y: scroll;
                height: 340px;
                margin-bottom: 5px;
                background-color: #ffffff;
                font-family: 'Raleway', sans-serif;
                border-radius: 5px;
            }

            .content .messages .form {
                margin: 5px 0;
                font-family: 'Raleway', sans-serif;
                border-radius: 4px;
                border-color: #627894;
                border: 2px;
            }

            .content .messages .form input {
                padding: 10px;
                border-color: #627894;
                border: 2px;
            }

            .content .messages .message {
                display: flex;
                padding: 10px;
            }

            .content .messages .message > div {
                max-width: 70%;
                background: #ffffff;
                box-shadow: 0px 0px 20px 5px rgba(0,0,0,0.05);
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
                color: #FA6121;
            }

            .content .messages .message .text {
                word-wrap: break-word;
            }

            .content .messages .update {
                text-align: center;
                padding: 10px;
                font-style: italic;
            }

            .message-container form * {
                margin: 5px 0;
                font-family: 'Raleway', sans-serif;
                border-radius: 4px;
                border-color: #627894;
                border: 2px;
            }

            .message-container form input {
                padding: 10px;
                border-color: #627894;
                border: 2px;
                width: 95%;
            }

            .message-container button {
                font-family: 'Raleway', sans-serif;
                font-weight: bold;
                cursor: pointer;
                background-color: ${this.color};
                color: #fff;
                border: 0;
                border-radius: 4px;
                padding: 10px;
                position: relative;
                opacity: 0.8;
            }

            .message-container .dropdown button {
                padding: 5px;
            }

            .message-container button:hover {
                opacity: 1.0;
            }

            .content .messages {
                overflow-y: scroll;
                max-height: 320px;
                margin-bottom: 5px;
                background-color: #ffffff;
                font-family: "Raleway", sans-serif;
            }

            .content .messages .message {
                display: flex;
                padding: 10px;
            }

            .content .messages .message > div {
                max-width: 70%;
                background: #ffffff;
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
                font-size: 10px;
                padding-bottom: 5px;
                color: ${this.color};
                font-weight: bold;
            }

            .content .messages .message .text {
                word-wrap: break-word;
                font-size: 14px;
                color: black;
                font-weight: normal;
            }

            .content .messages .update {
                text-align: center;
                padding: 10px;
                font-style: italic;
            }

            .inputs {
                display: flex;
                margin-bottom: 5px;
                align-content: space-around;
            }
            
            .inputs > .message-input {
                flex: 1 0 90%;
            }

            .inputs > .file-sharing {
                flex: 1 0 10%;
            }

            .end-chat {
                text-align: center;
            }

            .UploadButton {
                max-height: min-content;
                padding: 10px;
                color: ${this.color};
                background: none;
                border: none;
                justify-content: center;
                align-items: center;
                float: right;
            }
        
            .UploadButton:hover {
                cursor: pointer;
            }
        
            .closeModalBtn {
                background-color:  ${this.color};
                border: none;
                color: white;
            }
        
            .spinnerButton {
                transition: all 0.2s;
            }
        
            .closeModalBtn:hover {
                font-weight: bold;
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
                background: #E9EDEE;
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
                background-color:  ${this.color};
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
            
            .modal-body form {
                width: 95%;
            }
      
            .modal-fade-enter,
            .modal-fade-leave-to {
                opacity: 0;
            }
        
            .modal-fade-enter-active,
            .modal-fade-leave-active {
                transition: opacity .5s ease;
            }

            .modal .cancelButton {
                background-color: lightgrey;
                width: 40%;
            }

            .modal .cancelButton:hover {
                background-color: grey;
            }
            
            .text-danger {
                color: red;
                font-size: 12px;
            }

            /* Dropdown Content for Change Name and End Conversation */
            .dropdown-content {
                right: 0;
                position: absolute;
                background-color: #f1f1f1;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
                border-radius: 5px;
            }

            /* Links inside the dropdown */
            .dropdown-content a {
                color: black;
                padding: 10px 10px;
                margin: 5px;
                font-size: 12px;
                text-decoration: none;
                display: block;
                border-radius: 5px;
            }

            /* Change color of dropdown links on hover */
            .dropdown-content a:hover {
                background-color: #ddd;
                color: ${this.color};
                cursor: pointer;
            }
        `.replace(/^\s+|\n/gm, '');
        document.head.appendChild(styleTag);
    }

    async getCurrentVisitor() {
        console.log("running api call")
        try {
            const visitorId = `${this.client.getFingerprint()}`;
            // const response = await axios.get(`${this.baseUrl}/api/visitors/${visitorId}`);
            const response = await axios.get(`${this.baseUrl}/api/visitors/${this.client.getFingerprint().toString()}`);
            console.log("getCurrentVisitor response=> ", response.data)
        } catch (err) {
          console.error(err);
        }
    }

    toggleOpen() {
        // alternative solution: api call to check if current visitor already has a name
        // this.getCurrentVisitor()

        this.open = !this.open;

        if (this.open) {
            this.chatIcon.classList.add('hidden');
            this.closeIcon.classList.remove('hidden');
            this.messageContainer.classList.remove('hidden');

            const nameField = document.querySelector("input#name");

            // if visitor already has a name in localStorage
            this.visitorHasName();

            if (nameField) nameField.focus();

        } else {
            if (!this.sessionStarted) {
                this.checkTime();
            }

            // TO DO: Need to insert a check if the visitor has already started a conversation.
            this.chatIcon.classList.remove('hidden');
            this.closeIcon.classList.add('hidden');
            this.messageContainer.classList.add('hidden');
        }
    }

    visitorHasName() {
        const visitorId = `${this.client.getFingerprint()}`
        const visitorName = window.localStorage.getItem(visitorId);

        if (visitorName) {
            const nameField = document.querySelector("input#name");
            const welcomeDiv = document.querySelector("div.welcome")


            if (nameField) {
                // hide element
                nameField.style.display = "none";
                // set name input field value to previously entered value by visitor
                nameField.value = visitorName;
            }
            if (welcomeDiv) {
                // modify welcomeDiv to show visitor's name
                welcomeDiv.textContent = `Welcome back, ${visitorName}!`;
            }
        }
    }

    submit(event) {
        // TO DO:  Add here code to start the chat.
        event.preventDefault();
        const formSubmission = {
            name: event.srcElement.querySelector('#name').value,
        };

        // save visitor's inputted name in localStorage
        window.localStorage.setItem(`${this.client.getFingerprint()}`, formSubmission.name);

        this.sessionStarted = true;
        this.startConversation(formSubmission);
        
    }

    startConversation(formSubmission) {
        // Dependency to add the End Chat button
        const gIconsHeader = document.createElement('link');
        gIconsHeader.innerHTML = '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">'
        document.head.appendChild(gIconsHeader);

        // Set up Vue component
        this.messageContainer.innerHTML = `
            <chat-widget
                :visitor-name="visitorName"
                :room="room"
                ></chat-widget>`;

        Vue.use(VueChatScroll);

        this.vue = new Vue({
            components: {
                'chat-widget': require('./ChatWidget.vue').default
            },
            data: {
                visitorName: formSubmission ? formSubmission.name : null,
                room: this.room
            }
        });

        
        if (formSubmission) {
            const client = new cj.ClientJS();
            this.visitorId = `${client.getFingerprint()}`;
    
            axios.patch("<?php echo $baseUrl; ?>/api/visitors/" + this.visitorId, {
                name: formSubmission.name
            })
                .then(response => {
                    window.localStorage.setItem(this.visitorId, formSubmission.name);
                })
                .catch((err) => {
                    console.error(err);
                    console.log('Unable to update name. Please try again later.');
                })   
        }

        // Mount it to message-container
        this.vue.$mount(this.messageContainer);
        this.messageContainer = this.vue.$el;

        this.conversationStarted = true;
    }

    checkTime() {
        // LOGIC FOR AFTER HOURS MESSAGE
        if (!this.hasScheduledAvailability) {
            this.createMessageContainerContent();
        }
        else {
            var now = new Date().getTime();
            var startTime = this.availabilityStartTime.getTime();
            var endTime = this.availabilityEndTime.getTime();

            // Widget is active for a specified time today
            if (this.schedulerEnabledForToday) {
                if (now > startTime && now < endTime) {
                    this.createMessageContainerContent();
                } else {
                    this.createMessageContainerContentAfterHours();
                }
            }

            // Widget is inactive the whole day today
            else {
                this.createMessageContainerContentUnavailableAllDay();
            }
        }
    }

    showNotifications(count) {
        this.hideNotifications();
        this.notifInterval = setInterval(() => {
            const pattern = /^\(\d+\)/;
            if (document.title === this.origTitle) {
            if (count === 0 || pattern.test(document.title)) {
                document.title = document.title.replace(pattern, count === 0 ? "" : "(" + count + ") ");
            } else {
                document.title = "(" + count + ") New message(s) received!";
            }
            } else {
            document.title = this.origTitle;
            }
        }, 1500);

        this.audio.play().catch(err => console.log(err));
    }
    
    hideNotifications() {
        clearInterval(this.notifInterval);
        document.title = this.origTitle;
    }
}