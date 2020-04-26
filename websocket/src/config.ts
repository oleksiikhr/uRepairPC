import dotenv from 'dotenv';
import { toBool, toNumber } from './helper/transform';

dotenv.config();

export default {
  server: {
    hostname: process.env.APP_HOSTNAME,
    port: toNumber(process.env.APP_PORT, 3000),
    ssl: {
      enable: toBool(process.env.SSL_ENABLE),
      crt: process.env.SSL_CRT || '',
      key: process.env.SSL_KEY || '',
    },
  },
  amqp: {
    hostname: process.env.AMQP_HOSTNAME || 'rabbitmq',
    port: toNumber(process.env.AMQP_PORT, 5672),
    username: process.env.AMQP_USERNAME || 'guest',
    password: process.env.AMQP_PASSWORD || 'guest',
    vhost: process.env.AMQP_VHOST || '/',
    queueName: 'server.changes',
  },
  socket: {
    path: process.env.SOCKET_PATH || '/ws/server',
  },
};
