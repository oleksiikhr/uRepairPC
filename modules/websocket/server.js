"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (Object.hasOwnProperty.call(mod, k)) result[k] = mod[k];
    result["default"] = mod;
    return result;
};
Object.defineProperty(exports, "__esModule", { value: true });
const fs_1 = __importDefault(require("fs"));
const http_1 = __importDefault(require("http"));
const https_1 = __importDefault(require("https"));
const env = __importStar(require("./env"));
let server;
if (env.ssl) {
    server = https_1.default.createServer({
        cert: fs_1.default.readFileSync(env.sslCrt),
        key: fs_1.default.readFileSync(env.sslKey),
    });
}
else {
    server = http_1.default.createServer();
}
exports.default = server;
