import Socket from './index';

export default interface IHandler {
  message: any;
  socket: Socket;
  execute (): void;
}
