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
export const proxyTarget = withoutLastSlash(process.env.PROXY_TARGET) || 'http://localhost'

/** @return {string} */
export const server = isDev ? proxyTarget : location.origin

/** @return {string} */
export const serverSocket = window.WS_URL || `${isDev ? proxyTarget : location.hostname}:3000`
