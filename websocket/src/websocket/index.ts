import { Socket } from 'socket.io';
import io from './io';

io.on('connection', (socket: Socket) => {
  socket.on('leave', (rooms: any) => {
    if (Array.isArray(rooms)) {
      rooms.forEach((room: any) => socket.leave(room));
    } else {
      socket.leave(rooms);
    }
  });

  socket.on('logout', () => {
    Object.keys(socket.rooms).forEach((room: string) => {
      if (socket.id !== room) {
        socket.leave(room);
      }
    });
  });
});

export default io;
