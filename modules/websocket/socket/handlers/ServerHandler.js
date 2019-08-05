"use strict";
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (Object.hasOwnProperty.call(mod, k)) result[k] = mod[k];
    result["default"] = mod;
    return result;
};
Object.defineProperty(exports, "__esModule", { value: true });
const apps_1 = require("../../enum/apps");
const serverTypes_1 = __importStar(require("../../enum/serverTypes")), type = serverTypes_1;
class ServerHandler {
    /**
     * @param {Socket} socket
     * @param {string} message
     * @throws
     */
    constructor(socket, message) {
        this.socket = socket;
        this.message = JSON.parse(message).data;
    }
    /**
     * @return {void}
     * @throws
     */
    execute() {
        this.validate();
        let socket;
        try {
            socket = this.socket.getConnectedSocketById(this.message.socketId);
        }
        catch (e) {
            return;
        }
        const rooms = this.getRooms();
        switch (this.message.type) {
            case type.JOIN:
                rooms.forEach((room) => socket.join(room));
                break;
            case type.SYNC:
                this.syncSocketRooms(socket, rooms);
                break;
            case type.CREATE:
                if (this.message.join) {
                    // Get all clients from input rooms
                    const sockets = this.socket.io.sockets;
                    rooms.forEach((room) => sockets.in(room));
                    // Who created - listens to the room
                    socket.join(this.message.join);
                    // Every client now listen the new room
                    sockets.clients((err, clients) => {
                        if (!err) {
                            for (const client of clients) {
                                try {
                                    this.socket.getConnectedSocketById(client).join(this.message.join);
                                }
                                catch {
                                    continue;
                                }
                            }
                        }
                    });
                }
            case type.UPDATE:
            case type.DELETE:
                this.broadcastRoomsEmit(socket, rooms);
                break;
            default:
                console.warn('Broadcast: message.type unknown');
        }
    }
    /**
     * @return {void}
     * @throws
     */
    validate() {
        if (!this.message.event) {
            throw new Error('Event is empty');
        }
        if (typeof this.message.data === 'undefined') {
            throw new Error('Data is undefined');
        }
        if (typeof this.message.rooms === 'undefined') {
            throw new Error('Rooms is empty');
        }
        if (!serverTypes_1.default.includes(this.message.type)) {
            throw new Error('Type is invalid');
        }
        if (!this.message.socketId) {
            throw new Error('SocketId is empty');
        }
    }
    /**
     * @return {array}
     */
    getRooms() {
        let rooms = [];
        if (Array.isArray(this.message.rooms)) {
            rooms = this.message.rooms;
        }
        else if (this.message.rooms) {
            rooms = [this.message.rooms];
        }
        return rooms;
    }
    /**
     * Leave the existing rooms that are not found in the array
     * and join the remaining rooms.
     * @param {SocketIO.Socket} socket
     * @param {array} rooms
     * @return {void}
     */
    syncSocketRooms(socket, rooms) {
        Object.keys(socket.rooms).forEach((room) => {
            if (socket.id !== room) {
                const findRoomIndex = rooms.indexOf(room);
                if (~findRoomIndex) {
                    rooms.splice(findRoomIndex, 1);
                }
                else {
                    socket.leave(room);
                }
            }
        });
        rooms.forEach((room) => socket.join(room));
    }
    /**
     * Broadcast event to selected rooms or to all.
     * @param {SocketIO.Socket} socket
     * @param {array} rooms
     * @return {void}
     */
    broadcastRoomsEmit(socket, rooms) {
        rooms.forEach((room) => socket.to(room));
        socket.broadcast.emit(`${apps_1.SERVER}.${this.message.event}`, {
            app: apps_1.SERVER,
            data: this.message.data,
            params: this.message.params,
            type: this.message.type,
        });
    }
}
exports.default = ServerHandler;
