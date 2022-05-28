import Vue from 'vue';
import VueChatScroll from "vue-chat-scroll";
import cj from "clientjs";
import axios from 'axios';

// Options used for converting date/time to string using .toLocaleTimeString()
const localeTimeFormat = {
    hour: '2-digit',
    minute: '2-digit'
}

export class Tawk {
    constructor({ position = 'bottom-right',
        baseUrl,
        hasScheduledAvailability,
        availabilityStartTime,
        availabilityEndTime,
        schedulerEnabledForToday
    } = {}
    ) {
        this.baseUrl = baseUrl;
        this.position = this.getPosition(position);
        this.open = false;
        this.sessionStarted = false;
        this.client = new cj.ClientJS();

        // Variables for availability schedule
        this.hasScheduledAvailability = hasScheduledAvailability;
        this.availabilityStartTime = availabilityStartTime;
        this.availabilityEndTime = availabilityEndTime;
        this.schedulerEnabledForToday = schedulerEnabledForToday;

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
        chatIcon.src = `${this.baseUrl}/assets/chat.svg`;
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

    }

    createStyles() {
        const styleTag = document.createElement('style');
        styleTag.innerHTML = `
            @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@200;400&display=swap');

            .message-container * {
                font-family: 'Raleway', sans-serif;
            }
            .icon {
                filter: invert(47%) sepia(88%) saturate(2944%) hue-rotate(348deg) brightness(101%) contrast(96%);
                cursor: pointer;
                width: 70%;
                position: absolute;
                top: 9px;
                left: 9px;
                transition: transform .3s ease;
            }
            .hidden {
                transform: scale(0);
            }
            .button-container {
                background-color: none;
                width: 60px;
                height: 60px;
                border-radius: 50%;
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
                background-color: #466289;
                border-top-right-radius: 5px;
                border-top-left-radius: 5px;
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
                height: 330px;
                margin-bottom: 5px;
                background-color: #ffffff;
                font-family: 'Raleway', sans-serif;
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
            .message-container form button {
                font-family: 'Raleway', sans-serif;
                font-weight: bold;
                cursor: pointer;
                background-color: #FA6121;
                color: #fff;
                border: 0;
                border-radius: 4px;
                padding: 10px;
                position: relative;
            }
            .message-container form button:hover {
                background-color: #FFB739;
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

        console.log(formSubmission);
    }

    startConversation(formSubmission) {
        // Dependency to add the End Chat button
        const gIconsHeader = document.createElement('link');
        gIconsHeader.innerHTML = '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">'
        document.head.appendChild(gIconsHeader);

        // Set up Vue component
        this.messageContainer.innerHTML = `<chat-widget visitor-name="${formSubmission.name}"></chat-widget>`;

        Vue.use(VueChatScroll);
        this.vue = new Vue({
            components: {
                'chat-widget': require('./ChatWidget.vue').default
            },
            propsData: {
                visitorName: formSubmission.name
            }
        });

        // Mount it to message-container
        this.vue.$mount(this.messageContainer);
        this.messageContainer = this.vue.$el;
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
}