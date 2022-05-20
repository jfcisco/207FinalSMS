window._ = require("lodash");
window.io = require("socket.io-client");
window.cj = require("clientjs");

try {
    window.bootstrap = require("bootstrap");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

//  import Echo from 'laravel-echo';

//  window.Pusher = require('pusher-js');

//  window.Echo = new Echo({
//      broadcaster: 'pusher',
//      key: process.env.MIX_PUSHER_APP_KEY,
//      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//      wsHost: process.env.MIX_WS_HOST || window.location.hostname,

//      // Enable WSS only on the production server
//      forceTLS: process.env.MIX_APP_ENV === 'production',
//      wsPort: process.env.MIX_WS_PORT || 6001,
//      wssPort: process.env.MIX_WS_PORT || 6001,
//      disableStats: true,
//  });

// const client = new cj.ClientJS();
// const socket = io("https://sms-ws.ml:3000", {
//     secure: true,
//     autoConnect: false,
// });

// // The rest are for testing only
// socket.auth = {
//     // For visitors
//     clientId: client.getFingerprint(),
//     clientType: "visitor",
//     clientName: "bisita",
//     widgetId: "widget1",

//     // For admin/agent
//     // clientId: userId,
//     // clientName: "agentako",
//     // clientType: "user",
// };
// socket.connect();

// let chatRooms = [];
// socket.on("rooms", ({ rooms }) => {
//     console.log("rooms => ", rooms);
//     chatRooms = rooms;
// });

// setTimeout(() => {
//     // Message sent by admin/agent/visitor
//     socket.emit("message", {
//         content: "This is a message from visitor to room",
//         roomId: chatRooms[0]._id,
//     });

//     // Whisper sent only by admin/agent
//     socket.emit("whisper", {
//         content: "This is a whisper from admin/agent to another admin/agent",
//         roomId: chatRooms[0]._id,
//     });
// }, 1000);

// // admin/agent/visitor should listen to message event
// socket.on("message", (message) => {
//     console.log("received new message", message);
// });

// // only admin/agent should listen to whisper event
// socket.on("whisper", (whisper) => {
//     console.log("received new whisper", whisper);
// });

// // Join a room
// // socket.emit("join", { roomId: roomId, name: username })

// // An admin/agent has joined the room
// socket.on("join", (notification) => {
//     console.log("new joiner", notification);
// });

// // An admin/agent/visitor left the room
// socket.on("user_disconnect", (notification) => {
//     console.log("left", notification);
// });
