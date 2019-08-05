"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const dotenv_1 = __importDefault(require("dotenv"));
dotenv_1.default.config();
/**
 * Convert string to boolean
 * @param {string|undefined} val
 * @return {boolean}
 */
const toBool = (val) => {
    if (!val) {
        return false;
    }
    return ['true', 'yes', 'on', '1'].includes(val);
};
/*
 * General
 */
exports.hostName = process.env.APP_HOSTNAME;
exports.port = typeof process.env.APP_PORT === 'undefined' ? 3000 : +process.env.APP_PORT;
/*
 * SSL - HTTPS
 */
exports.ssl = toBool(process.env.APP_SSL);
exports.sslCrt = process.env.SSL_CRT || '';
exports.sslKey = process.env.SSL_KEY || '';
