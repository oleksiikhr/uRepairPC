import io from 'socket.io-client';
import Redis from 'ioredis';
import * as types from '../src/enum/serverTypes';
import { port } from '../src/env';
import { SERVER as APP_SERVER } from '../src/enum/apps';

const WAIT_TIMEOUT = 500;

describe('Check connections and events', () => {
  let sockets: SocketIOClient.Socket[];
  let socket1: SocketIOClient.Socket; // first user
  let socket2: SocketIOClient.Socket; // second user
  const redis = new Redis();

  /*
   * Server (laravel broadcast event to the socket server
   */
  const redisEvent = (event: string, type: string, socketId: string, data?: any, rooms?: Array<string> | string): Promise<number> => {
    return redis.publish(`${APP_SERVER}.test`, JSON.stringify({
      data: {
        event, data, rooms, type, socketId
      }
    }));
  };

  /*
   * Make 2 connections to the socket
   */
  beforeEach((done) => {
    socket1 = io.connect('http://localhost:' + port);
    socket2 = io.connect('http://localhost:' + port);
    sockets = [socket1, socket2];

    sockets.forEach((socket) => {
      socket.on('connect', () => {
        if (sockets.every(s => s.connected)) {
          done();
        }
      })
    });
  });

  afterEach((done) => {
    sockets.forEach((socket) => {
      if (socket.connected) {
        socket.disconnect();
      }
    });
    done();
  });

  afterAll(async (done) => {
    await redis.quit();
    done();
  });

  describe('Listener events from the Server', () => {
    test('Socket1 joins the room, and Socket2 makes an event', async (done) => {
      socket1.on(`${APP_SERVER}.test`, (data: any) => {
        expect(data).toHaveProperty('data.name');
        expect(data).toHaveProperty('type');
        done();
      });

      // Socket1 join to room: test-1
      await redisEvent('test', types.JOIN, socket1.id, null, ['test-1']);

      // Socket2 create something
      await redisEvent('test', types.CREATE, socket2.id, {name: 'Test'}, ['test-1', 'test-2']);
    });

    test('Socket1 doesn\'t joins the room, and Socket2 makes an event', async (done) => {
      socket1.on(`${APP_SERVER}.test`, () => {
        done.fail('Socket1 received event');
      });

      // Socket2 create something
      await redisEvent('test', types.CREATE, socket2.id, {name: 'Test'}, ['test-1', 'test2']);

      setTimeout(done, WAIT_TIMEOUT);
    });

    test('Socket1 joins the room then leave, and Socket2 makes an event', async (done) => {
      socket1.on(`${APP_SERVER}.test`, () => {
        done.fail('Socket1 received event');
      });

      // Socket1 join to room: test-1
      await redisEvent('test', types.JOIN, socket1.id, null, ['test-1']);

      // Socket1 leave from the room: test-1
      socket1.emit('leave', ['test-1']);

      // Socket2 create something
      setTimeout(async () => {
        await redisEvent('test', types.CREATE, socket2.id, {name: 'Test'}, ['test-1', 'test-2']);
      }, WAIT_TIMEOUT);

      setTimeout(done, WAIT_TIMEOUT * 2);
    });
  });
});
