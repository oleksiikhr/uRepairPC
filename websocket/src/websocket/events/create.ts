import { Socket } from 'socket.io';
import JsonEvent from 'JsonEvent';
import commonEvent from './common';
import io from '../io';

export default (socket: Socket|undefined, json: JsonEvent) => {
  if (json.join.length) {
    if (socket) {
      socket.join(json.join);
    }

    json.rooms.forEach((room: string) => io.sockets.in(room));

    // Every client now listen the new room
    io.sockets.clients((err: any, clients: string[]) => {
      if (err) {
        console.error(err);
        return;
      }

      clients.forEach((client) => {
        try {
          io.sockets.connected[client].join(json.join);
        } catch (e) {
          console.warn(e);
        }
      });
    });
  }

  commonEvent(socket, json);
};
