export interface JsonEvent {
  event: string;
  type: string;
  socketId: string;
  rooms: Array<string>;
  params: Array<string>;
  data: any;
  join: Array<string>;
}
