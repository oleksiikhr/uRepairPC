import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';

export default (socket: Socket, json: JsonEvent): void => {
  Object.keys(socket.rooms).forEach((room: string) => {
    if (socket.id !== room) {
      const findRoomIndex = json.rooms.indexOf(room);

      if (findRoomIndex !== -1) {
        json.rooms.splice(findRoomIndex, 1);
      } else {
        socket.leave(room);
      }
    }
  });

  json.rooms.forEach((room: string) => socket.join(room));
};
