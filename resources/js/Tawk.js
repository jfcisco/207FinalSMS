export class Tawk {
    constructor({ position = 'bottom-right'} = {}) {
        this.position = this.getPosition(position);
        this.open = false;
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
        chatIcon.src = 'assets/chat.svg';
        chatIcon.classList.add('icon');
        this.chatIcon = chatIcon;

        const closeIcon = document.createElement('img');
        closeIcon.src = 'assets/cross.svg';
        closeIcon.classList.add('icon', 'hidden');
        this.closeIcon = closeIcon;

        buttonContainer.appendChild(this.chatIcon);
        buttonContainer.appendChild(this.closeIcon);
        buttonContainer.addEventListener('click', this.toggleOpen.bind(this));

        this.messageContainer = document.createElement('div');
        this.messageContainer.classList.add('hidden', 'message-container');
        
        this.createMessageContainerContent();

        container.appendChild(this.messageContainer);
        container.appendChild(buttonContainer);
    }

    createMessageContainerContent() {
        this.messageContainer.innerHTML = '';
        const title = document.createElement('h2');
        // TO DO: Edit the text content based on schedule / timezone
        title.textContent = `SMS`;

        const form = document.createElement('form');
        form.classList.add('content');
        
        const welcome = document.createElement('div');
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

    createStyles() {
        const styleTag = document.createElement('style');
        styleTag.innerHTML = `
            @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@200;400&display=swap');
            
            * {
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
                right: -25px;
                bottom: 75px;
                max-height: 400px;
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
                border-radius-top-right: 5px;
                border-radius-top-left: 5px;
            }
            .message-container .content {
                margin: 20px 10px ;
                padding: 10px;
                display: flex;
                background-color: #E9EDEE;
                flex-direction: column;
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
            }
            .message-container form textarea {
                resize: none;
                height: 100px;
                padding: 10px;
                border-color: #627894;
                border: 2px;
            }
            .message-container form textarea::placeholder {
                font-family: 'Raleway', sans-serif;;
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
            }
            .message-container form button:hover {
                background-color: #FFB739;

            .chatbox-input
                padding: 10px;
                border-color: #627894;
                border: 2px;
                margin: 10px;
            }
        `.replace(/^\s+|\n/gm, '');
        document.head.appendChild(styleTag);
    }

    toggleOpen() {
        this.open = !this.open;
        if (this.open) {
            this.chatIcon.classList.add('hidden');
            this.closeIcon.classList.remove('hidden');
            this.messageContainer.classList.remove('hidden');
        } else {
            this.createMessageContainerContent();
            this.chatIcon.classList.remove('hidden');
            this.closeIcon.classList.add('hidden');
            this.messageContainer.classList.add('hidden');
        }
    }

    submit(event) {
        // TO DO:  Edit this part to connect to API
        event.preventDefault();
        const formSubmission = {
            name: event.srcElement.querySelector('#name').value, 
        };

        this.messageContainer.innerHTML =
            `<h2>Conversation </h2>
            <section id="messages" class="content">
            <div class="card-body chatboxfix p-0 roomMessages"><strong>Agent X: </strong> Hello user! I'm agent X. How may I help you?</div>
            <!--INPUT MESSAGE BOX-->
                <div class="chatbox-input">
                        <input
                        @keydown="sendTypingEvent"
                        @keyup.enter="sendMessage"
                        v-model="newMessage"
                        type="text"
                        name="message"
                        placeholder="Enter your message..."
                        class="form-control"
                        />
                </div>
            </section>`;
        
        console.log(formSubmission);
    }
}