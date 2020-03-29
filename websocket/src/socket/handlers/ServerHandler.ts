import SocketIO from 'socket.io';
import { SERVER as APP_SERVER } from '../../enum/apps';
import types, * as type from '../../enum/serverTypes';
import IHandler from '../IHandler';
import Socket from '../index';

export default class ServerHandler implements IHandler {
  public socket: Socket;
  public message: any;

  /**
   * @param {Socket} socket
   * @param {string} message
   * @throws
   */
  constructor (socket: Socket, message: any) {
    this.socket = socket;
    this.message = JSON.parse(message).data;
  }

  /**
   * @return {void}
   * @throws
   */
  public execute (): void {
    this.validate();
    let socket: SocketIO.Socket;

    try {
      socket = this.socket.getConnectedSocketById(this.message.socketId);
    } catch (e) {
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
          sockets.clients((err: any, clients: string[]) => {
            if (!err) {
              for (const client of clients) {
                try {
                  this.socket.getConnectedSocketById(client).join(this.message.join);
                } catch {
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
  private validate (): void {
    if (!this.message.event) {
      throw new Error('Event is empty');
    }

    if (typeof this.message.data === 'undefined') {
      throw new Error('Data is undefined');
    }

    if (typeof this.message.rooms === 'undefined') {
      throw new Error('Rooms is empty');
    }

    if (!types.includes(this.message.type)) {
      throw new Error('Type is invalid');
    }

    if (!this.message.socketId) {
      throw new Error('SocketId is empty');
    }
  }

  /**
   * @return {array}
   */
  private getRooms (): string[] {
    let rooms: string[] = [];

    if (Array.isArray(this.message.rooms)) {
      rooms = this.message.rooms;
    } else if (this.message.rooms) {
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
  private syncSocketRooms (socket: SocketIO.Socket, rooms: string[]): void {
    Object.keys(socket.rooms).forEach((room) => {
      if (socket.id !== room) {
        const findRoomIndex = rooms.indexOf(room);
        if (~findRoomIndex) {
          rooms.splice(findRoomIndex, 1);
        } else {
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
  private broadcastRoomsEmit (socket: SocketIO.Socket, rooms: string[]): void {
    rooms.forEach((room) => socket.to(room));
    socket.broadcast.emit(`${APP_SERVER}.${this.message.event}`, {
      app: APP_SERVER,
      data: this.message.data,
      params: this.message.params,
      type: this.message.type,
    });
  }
}
