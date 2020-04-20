import server from './server';
import config from '../config';

server.listen(config.server.port, config.server.hostname, () => {
  console.log(`Listening on ${config.server.hostname || '*'}:${config.server.port}`);
});

export default server;
