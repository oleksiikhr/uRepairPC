import socketIO from 'socket.io';
import config from '../config';
import server from '../server';

const io = socketIO(server, {
  path: config.socket.path,
});

export default io;
