import connect from './connect';
import config from '../config';

export default connect(config.server.port, config.server.hostname);
