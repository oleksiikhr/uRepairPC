"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const apps_1 = require("../../enum/apps");
class AutodeployHandler {
    /**
     * @param {Socket} socket
     * @param {string} message
     * @throws
     */
    constructor(socket, message) {
        this.socket = socket;
        this.message = JSON.parse(message);
    }
    /**
     * @return {void}
     * @throws
     */
    execute() {
        this.validate();
        this.emit();
    }
    /**
     * @return {void}
     * @throws
     */
    validate() {
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
    emit() {
        this.socket.io.emit(`${apps_1.AUTODEPLOY}.${this.message.event}`, {
            app: apps_1.AUTODEPLOY,
            data: this.message.data,
        });
    }
}
exports.default = AutodeployHandler;
