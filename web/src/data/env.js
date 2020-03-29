/* eslint-disable */
'use strict'

/**
 * Remove last slash if exists
 * @param input
 * @return {string}
 */
const withoutLastSlash = (input) => {
  if (!input) {
    return ''
  }

  if (input.slice(-1) === '/') {
    return input.slice(0, input.length - 1)
  }

  return input
}

/** @return {boolean} */
export const isDev = ['dev', 'development'].includes(process.env.NODE_ENV)

/** @return {string} */
export const proxyServer = withoutLastSlash(process.env.PROXY_SERVER) || 'http://localhost'

/** @return {string} */
export const proxyWebsocket = withoutLastSlash(process.env.PROXY_WEBSOCKET) || 'http://localhost:3000'

/** @return {string} */
export const server = isDev ? proxyServer : location.origin

/** @return {string} */
export const websocket = isDev ? proxyWebsocket : `${location.hostname}:3000`
