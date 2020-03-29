import dotenv from 'dotenv';

dotenv.config();

/**
 * Convert string to boolean
 * @param {string|undefined} val
 * @return {boolean}
 */
const toBool = (val: string|undefined) => {
  if (!val) {
    return false;
  }

  return ['true', 'yes', 'on', '1'].includes(val);
};

/*
 * General
 */

export const hostName: string|undefined = process.env.APP_HOSTNAME;

export const port: number = typeof process.env.APP_PORT === 'undefined' ? 3000 : +process.env.APP_PORT;

/*
 * SSL - HTTPS
 */

export const ssl: boolean = toBool(process.env.APP_SSL);

export const sslCrt: string = process.env.SSL_CRT || '';

export const sslKey: string = process.env.SSL_KEY || '';
