import io from "socket.io-client";

// Setup Socket.IO connection
const socket = io(process.env.MIX_SOCKET_SERVER, {
    secure: true,
    autoConnect: false,
});

// Uncomment for debugging
// socket.onAny((event, ...args) => console.log(event, args));

export {
    socket
}