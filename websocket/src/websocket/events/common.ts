import { Socket } from 'socket.io';
import JsonEvent from 'JsonEvent';
import io from '../io';

export default (socket: Socket|undefined, json: JsonEvent) => {
  if (socket) {
    json.rooms.forEach((room: string) => socket.to(room));

    socket.broadcast.emit(json.event, {
      data: json.data,
      params: json.params,
      type: json.type,
    });
  } else {
    json.rooms.forEach((room: string) => io.to(room));

    io.emit(json.event, {
      data: json.data,
      params: json.params,
      type: json.type,
    });
  }
};
