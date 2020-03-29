'use strict'

/** @type {array} */
export const email = [
  { required: true, message: 'Будь ласка, введіть E-mail' },
  { type: 'email', message: 'Введіть правильну адресу електронної пошти' }
]

/** @type {array} */
export const password = [
  { required: true, message: 'Будь ласка, введіть пароль' },
  { min: 6, message: 'Пароль повинен бути більше, ніж 5 символів' }
]

/** @type {array} */
export const required = [
  { required: true, message: 'Будь ласка, введіть дані' }
]
