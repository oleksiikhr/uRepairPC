import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';

export default (socket: Socket, json: JsonEvent) => {
  json.rooms.forEach((room: string) => socket.to(room));

  socket.broadcast.emit(json.event, {
    data: json.data,
    params: json.params,
    type: json.type,
  });
};
