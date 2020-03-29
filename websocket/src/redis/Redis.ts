import IoRedis from 'ioredis';
import {
  AUTODEPLOY as APP_AUTODEPLOY,
  SERVER as APP_SERVER,
} from '../enum/apps';
import Socket from '../socket';
import AutodeployHandler from '../socket/handlers/AutodeployHandler';
import ServerHandler from '../socket/handlers/ServerHandler';

export default class Redis {
  public redis: IoRedis.Redis;
  public socket: Socket;

  constructor (socket: Socket) {
    this.redis = new IoRedis();
    this.socket = socket;
  }

  /**
   * @return {void}
   */
  public init (): void {
    this.psubscribe();
    this.pmessage();
  }

  /**
   * @return {void}
   * @see https://redis.io/topics/pubsub
   */
  private psubscribe (): void {
    Object.keys(Redis.handlers).forEach((subscribeName) => {
      this.redis.psubscribe(subscribeName);
    });
  }

  /**
   * @return {void}
   * @see https://redis.io/topics/pubsub
   */
  private pmessage (): void {
    this.redis.on('pmessage', (channel, pattern, message) => {
      const Handler = Redis.handlers[channel];

      if (Handler) {
        try {
          new Handler(this.socket, message).execute();
        } catch (e) {
          console.warn(e);
        }
      } else {
        console.warn('[Redis.pmessage] channel is unknown:', channel);
      }
    });
  }

  static get handlers () {
    return {
      [`${APP_SERVER}.*`]: ServerHandler,
      [`${APP_AUTODEPLOY}.*`]: AutodeployHandler,
    };
  }
}
