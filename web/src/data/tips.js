'use strict'

import * as perm from '@/enum/perm'

/**
 * @var {array}
 * @example
 *  - text (string)
 *  - permissions (array of enum permissions)
 */
export default [
  {
    text: '<code>Shift + Mouse Wheel</code> - переміщати горизонтальний скролл.'
  },
  {
    text: 'Ви можете призначати декілька ролей на одного користувача.',
    permissions: perm.ROLES_EDIT_ALL
  },
  {
    text: '<code>Esc</code> - закрити модальне вікно'
  },
  {
    text: 'Користувач після створення має <strong>ролі за замовчуванням</strong>',
    permissions: perm.ROLES_VIEW_SECTION
  },
  {
    text: 'Оновлення через <strong>Real-Time</strong> приходять тільки до тих даних, які ви бачите'
  },
  {
    text: 'Доступи з різних ролей підсумовуються до користувача',
    permissions: perm.ROLES_VIEW_SECTION
  },
  {
    text: '<strong>Real-Time</strong> - це оновлення даних без перезавантаження сторінки'
  },
  {
    text: 'Ви бачите поради в залежності від ваших <strong>доступів</strong>'
  },
  {
    text: 'Вилучені дані залишаються в <strong>базі даних</strong>',
    permissions: perm.ROLES_EDIT_ALL
  },
  {
    text: '<strong>Https</strong> з\'єднання дає можливість додавати сайт на робочий стіл (PC/Моб.)'
  },
  {
    text: '<strong>Https</strong> з\'єднання дає можливість користуватись сайтом без інтернету'
  }
]
