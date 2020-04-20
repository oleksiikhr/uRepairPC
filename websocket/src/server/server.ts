import http from 'http';
import https from 'https';
import fs from 'fs';
import config from '../config';

const server: http.Server|https.Server = (() => {
  if (config.server.ssl.enable) {
    return https.createServer({
      cert: fs.readFileSync(config.server.ssl.crt),
      key: fs.readFileSync(config.server.ssl.key),
    });
  }

  return http.createServer();
})();

export default server;
