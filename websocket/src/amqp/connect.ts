import amqp, { Connection } from 'amqplib';
import config from '../config';

export default (): Promise<Connection> => amqp.connect(config.amqp);
