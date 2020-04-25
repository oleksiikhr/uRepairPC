export interface JsonEvent {
  event: string;
  type: string;
  socketId: string;
  rooms: [string];
  params: [string];
  data: any;
  join: [string];
}
