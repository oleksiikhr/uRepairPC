import { AUTODEPLOY as APP_AUTODEPLOY } from '../../enum/apps';
import IHandler from '../IHandler';
import Socket from '../index';

export default class AutodeployHandler implements IHandler {
  public socket: Socket;
  public message: any;
  public data: any;

  /**
   * @param {Socket} socket
   * @param {string} message
   * @throws
   */
  constructor (socket: Socket, message: any) {
    this.socket = socket;
    this.message = JSON.parse(message);
  }

  /**
   * @return {void}
   * @throws
   */
  public execute (): void {
    this.validate();
    this.emit();
  }

  /**
   * @return {void}
   * @throws
   */
  private validate (): void {
    if (!this.message.event) {
      throw new Error('Event is empty');
    }

    if (typeof this.message.data === 'undefined') {
      throw new Error('Data is undefined');
    }
  }

  /**
   * @return {void}
   */
  private emit (): void {
    this.socket.io.emit(`${APP_AUTODEPLOY}.${this.message.event}`, {
      app: APP_AUTODEPLOY,
      data: this.message.data,
    });
  }
}
