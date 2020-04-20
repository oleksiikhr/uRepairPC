import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';
import commonEvent from './common';

export default (socket: Socket, json: JsonEvent) => {
  // TODO Check and rewrite*
  if (json.join.length) {
    // Get all clients from input rooms
    // const sockets = socket.io.sockets;
    // json.rooms.forEach((room) => sockets.in(room));
    //
    // // Who created - listens to the room
    // socket.join(json.join);
    //
    // // Every client now listen the new room
    // sockets.clients((err: any, clients: string[]) => {
    //   if (!err) {
    //     for (const client of clients) {
    //       try {
    //         socket.getConnectedSocketById(client).join(json.join);
    //       } catch {/* No matter */}
    //     }
    //   }
    // });
  }

  commonEvent(socket, json);
};
