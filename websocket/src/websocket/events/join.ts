import { JsonEvent } from 'JsonEvent';
import { Socket } from 'socket.io';

export default (socket: Socket|undefined, json: JsonEvent) => {
  if (socket) {
    json.rooms.forEach((room: string) => socket.join(room));
  }
};
