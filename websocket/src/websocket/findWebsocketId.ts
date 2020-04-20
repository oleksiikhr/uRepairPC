import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';
import websocket from './websocket';

export default (json: JsonEvent): Socket => {
  const socket = websocket.sockets.connected[json.socketId];

  if (typeof socket === 'undefined') {
    throw new Error('Socket ID is undefined');
  }

  return socket;
};
