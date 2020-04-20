import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';

export default (socket: Socket, json: JsonEvent): void => {
  json.rooms.forEach((room) => socket.join(room));
};
