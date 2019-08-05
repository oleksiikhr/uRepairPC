"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const ioredis_1 = __importDefault(require("ioredis"));
const apps_1 = require("../enum/apps");
const AutodeployHandler_1 = __importDefault(require("../socket/handlers/AutodeployHandler"));
const ServerHandler_1 = __importDefault(require("../socket/handlers/ServerHandler"));
class Redis {
    constructor(socket) {
        this.redis = new ioredis_1.default();
        this.socket = socket;
    }
    /**
     * @return {void}
     */
    init() {
        this.psubscribe();
        this.pmessage();
    }
    /**
     * @return {void}
     * @see https://redis.io/topics/pubsub
     */
    psubscribe() {
        Object.keys(Redis.handlers).forEach((subscribeName) => {
            this.redis.psubscribe(subscribeName);
        });
    }
    /**
     * @return {void}
     * @see https://redis.io/topics/pubsub
     */
    pmessage() {
        this.redis.on('pmessage', (channel, pattern, message) => {
            const Handler = Redis.handlers[channel];
            if (Handler) {
                try {
                    new Handler(this.socket, message).execute();
                }
                catch (e) {
                    console.warn(e);
                }
            }
            else {
                console.warn('[Redis.pmessage] channel is unknown:', channel);
            }
        });
    }
    static get handlers() {
        return {
            [`${apps_1.SERVER}.*`]: ServerHandler_1.default,
            [`${apps_1.AUTODEPLOY}.*`]: AutodeployHandler_1.default,
        };
    }
}
exports.default = Redis;
