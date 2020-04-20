import { Socket } from 'socket.io';
import { JsonEvent } from 'JsonEvent';
import * as types from '../enum/types';
import joinEvent from './events/join';
import syncEvent from './events/sync';
import commonEvent from './events/common';
import createEvent from './events/create';
import findWebsocketId from './findWebsocketId';

export default (json: JsonEvent) => {
  let socket: Socket;

  try {
    socket = findWebsocketId(json);
  } catch (e) {
    console.error(e);
    return;
  }

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
