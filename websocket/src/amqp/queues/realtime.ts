import { ConsumeMessage } from 'amqplib';
import connect from '../connect';
import retry from '../../helper/retry';
import websocketHandler from '../../websocket/handler';
import config from '../../config';

export default retry(async () => {
  const conn = await connect(config.amqp);

  const ch = await conn.createChannel();
  await ch.assertQueue(config.amqp.queueName, {
    durable: false,
  });

  await ch.consume(config.amqp.queueName, (msg: ConsumeMessage | null) => {
    if (msg === null) {
      return;
    }

    try {
      const json = JSON.parse(msg.content.toString());

      websocketHandler(json);
    } catch (e) {
      console.error(e);
    } finally {
      ch.ack(msg);
    }
  }, { noAck: false });
}, 3000);
