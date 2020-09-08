import JsonEvent from 'JsonEvent';
import * as types from '../enum/types';
import joinEvent from './events/join';
import syncEvent from './events/sync';
import commonEvent from './events/common';
import createEvent from './events/create';
import io from './io';

export default (json: JsonEvent) => {
  const socket = io.sockets.connected[json.socketId];

  switch (json.type) {
    case types.JOIN:
      joinEvent(socket, json);
      break;
    case types.SYNC:
      syncEvent(socket, json);
      break;
    case types.CREATE:
      createEvent(socket, json);
      break;
    case types.UPDATE:
    case types.DELETE:
      commonEvent(socket, json);
      break;
    default:
      console.error('Broadcast: message.type is unknown');
  }
};
