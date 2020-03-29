import http from 'http';
import https from 'https';
import socketIO from 'socket.io';

export default class Socket {
  public io: socketIO.Server;

  constructor (server: http.Server|https.Server) {
    this.io = socketIO(server);
  }

  public init (): void {
    this.connectionEvent();
  }

  /**
   * @param {string} socketId
   * @return {socketIO.Socket}
   * @throws
   */
  public getConnectedSocketById (socketId: string): socketIO.Socket {
    const socket = this.io.sockets.connected[socketId];

    if (typeof socket === 'undefined') {
      throw new Error(`Socket is undefined: ${socketId}`);
    }

    return socket;
  }

  private connectionEvent (): void {
    this.io.on('connection', (socket) => {
      socket.on('leave', (rooms) => {
        if (Array.isArray(rooms)) {
          rooms.forEach((room) => socket.leave(room));
        } else {
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
