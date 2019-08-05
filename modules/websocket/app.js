"use strict";
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (Object.hasOwnProperty.call(mod, k)) result[k] = mod[k];
    result["default"] = mod;
    return result;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
/* | -----------------------------------------------------------------------------------
 * | - import -
 * | -----------------------------------------------------------------------------------
 */
const env = __importStar(require("./env"));
const redis_1 = __importDefault(require("./redis"));
const server_1 = __importDefault(require("./server"));
const socket_1 = __importDefault(require("./socket"));
/* | -----------------------------------------------------------------------------------
 * | - Initialization -
 * | -----------------------------------------------------------------------------------
 */
const socket = new socket_1.default(server_1.default);
socket.init();
const redis = new redis_1.default(socket);
redis.init();
/* | -----------------------------------------------------------------------------------
 * | - Run the server -
 * | -----------------------------------------------------------------------------------
 */
server_1.default.listen(env.port, env.hostName, () => {
    console.log(`Listening on ${env.hostName || '*'}:${env.port}`);
});
