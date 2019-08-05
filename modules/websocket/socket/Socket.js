"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const socket_io_1 = __importDefault(require("socket.io"));
class Socket {
    constructor(server) {
        this.io = socket_io_1.default(server);
    }
    init() {
        this.connectionEvent();
    }
    /**
     * @param {string} socketId
     * @return {socketIO.Socket}
     * @throws
     */
    getConnectedSocketById(socketId) {
        const socket = this.io.sockets.connected[socketId];
        if (typeof socket === 'undefined') {
            throw new Error(`Socket is undefined: ${socketId}`);
        }
        return socket;
    }
    connectionEvent() {
        this.io.on('connection', (socket) => {
            socket.on('leave', (rooms) => {
                if (Array.isArray(rooms)) {
                    rooms.forEach((room) => socket.leave(room));
                }
                else {
                    socket.leave(rooms);
                }
            });
            // Leave from all rooms
            socket.on('logout', () => {
                Object.keys(socket.rooms).forEach((room) => {
                    if (socket.id !== room) {
                        socket.leave(room);
                    }
                });
            });
        });
    }
}
exports.default = Socket;
