import { Socket } from 'socket.io';
import websocket from './websocket';

websocket.on('connection', (socket: Socket) => {
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

export default websocket;
