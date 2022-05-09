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

        //TO DO:  Edit the SVG icons so they work.
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
        title.textContent = `We're not here, drop us an email...`;

        const form = document.createElement('form');
        form.classList.add('content');
        const email = document.createElement('input');
        email.required = true;
        email.id = 'email';
        email.type = 'email';
        email.placeholder = 'Enter your email address';

        const message = document.createElement('textarea');
        message.required = true;
        message.id = 'message';
        message.placeholder = 'Your message';
 
        const btn = document.createElement('button');
        btn.textContent = 'Submit';
        form.appendChild(email);
        form.appendChild(message);
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
            }
            .message-container.hidden {
                max-height: 0px;
            }
            .message-container h2 {
                margin: 0;
                padding: 20px 20px;
                color: #fff;
                background-color: #466289;
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
            email: event.srcElement.querySelector('#email').value, 
            message: event.srcElement.querySelector('#message').value,
        };

        this.messageContainer.innerHTML = '<h2>Thanks for your submission.</h2><p class="content">Someone will be in touch with your shortly regarding your enquiry';
        
        console.log(formSubmission);
    }
}