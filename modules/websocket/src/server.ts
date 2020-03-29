import fs from 'fs';
import http from 'http';
import https from 'https';
import * as env from './env';

let server: http.Server|https.Server;

if (env.ssl) {
  server = https.createServer({
    cert: fs.readFileSync(env.sslCrt),
    key: fs.readFileSync(env.sslKey),
  });
} else {
  server = http.createServer();
}

export default server;
