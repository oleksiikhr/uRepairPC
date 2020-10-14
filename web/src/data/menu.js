'use strict'

import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import types from '@/enum/types'
import router from '@/router'
import store from '@/store'

/**
 * Display on sidebar. Route name must be equal to template.sidebar
 * store for show on the left sidebar
 * In store menu will be filtered by permissions.
 * @type {object} of objects
 */
export default {
  [sections.home]: {
    icon: 'home',
    title: 'Головна сторінка',
    route: { name: sections.home }
  },
  [sections.requests]: {
    icon: 'description',
    title: 'Замовлення',
    route: { name: sections.requests },
    permissions: perm.REQUESTS_VIEW_SECTION,
    history: {
      show: true,
      callback: (obj) => `[${obj.id}] ${obj.title}`
    },
    children: {
      add: {
        title: 'Створити замовлення',
        icon: 'add',
        type: types.PRIMARY,
        permissions: perm.REQUESTS_CREATE,
        action: () => router.push({ name: `${sections.requests}-create` })
      }
    }
  },
  [sections.users]: {
    icon: 'people_outline',
    title: 'Користувачі',
    route: { name: sections.users },
    permissions: perm.USERS_VIEW_SECTION,
    history: {
      show: true,
      callback: (obj) => `[${obj.id}] ${obj.last_name} ${obj.first_name}`
    },
    children: {
      add: {
        title: 'Створити користувача',
        icon: 'add',
        type: types.PRIMARY,
        permissions: perm.USERS_CREATE,
        action: () => router.push({ name: `${sections.users}-create` })
      }
    }
  },
  [sections.roles]: {
    icon: 'group',
    title: 'Ролі',
    route: { name: sections.roles },
    permissions: perm.ROLES_VIEW_SECTION,
    history: {
      show: true
    },
    children: {
      add: {
        title: 'Створити роль',
        icon: 'add',
        type: types.PRIMARY,
        permissions: perm.ROLES_EDIT_ALL,
        action: () => router.push({ name: `${sections.roles}-create` })
      }
    }
  },
  [sections.equipments]: {
    icon: 'storage',
    title: 'Обладнання',
    route: { name: sections.equipments },
    permissions: perm.EQUIPMENTS_VIEW_SECTION,
    history: {
      show: true,
      callback: (obj) => `[${obj.id}] ${obj.serial_number || ''} / ${obj.inventory_number || ''}`
    },
    children: {
      add: {
        title: 'Створити обладнання',
        icon: 'add',
        type: types.PRIMARY,
        permissions: perm.EQUIPMENTS_CREATE,
        action: () => router.push({ name: `${sections.equipments}-create` })
      }
    }
  },
  [sections.jobs]: {
    icon: 'memory',
    title: 'Задачі',
    route: { name: sections.jobs },
    permissions: perm.JOBS_VIEW_SECTION,
    history: {
      show: true,
      callback: (obj) => obj.id
    }
  },
  [sections.settings]: {
    icon: 'settings',
    title: 'Конфігурація',
    route: { name: sections.settings },
    permissions: [
      perm.GLOBAL_SETTINGS_EDIT,
      perm.GLOBAL_MANIFEST_EDIT,
      perm.REQUESTS_CONFIG_VIEW_SECTION,
      perm.EQUIPMENTS_CONFIG_VIEW_SECTION
    ],
    children: {
      [sections.settingsGlobal]: {
        title: 'Глобальні налаштування',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.GLOBAL_SETTINGS_EDIT,
        route: { name: sections.settingsGlobal },
        children: {
          edit: {
            title: 'Редагувати',
            icon: 'edit',
            type: types.PRIMARY,
            permissions: perm.GLOBAL_SETTINGS_EDIT,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/settings/dialogs/Global')
              })
            }
          }
        }
      },
      [sections.settingsManifest]: {
        title: 'Маніфест',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.GLOBAL_MANIFEST_EDIT,
        route: { name: sections.settingsManifest },
        children: {
          edit: {
            title: 'Редагувати',
            icon: 'edit',
            type: types.PRIMARY,
            permission: perm.GLOBAL_MANIFEST_EDIT,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/settings/dialogs/Manifest')
              })
            }
          }
        }
      },
      [sections.requestsStatuses]: {
        title: 'Статуси замовлень',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
        route: { name: sections.requestsStatuses },
        children: {
          add: {
            title: 'Створити статус',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.REQUESTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/requests/statuses/dialogs/Create')
              })
            }
          }
        }
      },
      [sections.requestsPriorities]: {
        title: 'Пріорітети замовлень',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
        route: { name: sections.requestsPriorities },
        children: {
          add: {
            title: 'Створити пріорітет',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.REQUESTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/requests/priorities/dialogs/Create')
              })
            }
          }
        }
      },
      [sections.requestsTypes]: {
        title: 'Типи замовлень',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
        route: { name: sections.requestsTypes },
        children: {
          add: {
            title: 'Створити тип',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.REQUESTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/requests/types/dialogs/Create')
              })
            }
          }
        }
      },
      [sections.equipmentsTypes]: {
        title: 'Типи обладнання',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
        route: { name: sections.equipmentsTypes },
        children: {
          add: {
            title: 'Створити тип',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.EQUIPMENTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/equipments/types/dialogs/Create')
              })
            }
          }
        }
      },
      [sections.equipmentsManufacturers]: {
        title: 'Виробники обладнання',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
        route: { name: sections.equipmentsManufacturers },
        children: {
          add: {
            title: 'Створити виробника',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.EQUIPMENTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/equipments/manufacturers/dialogs/Create')
              })
            }
          }
        }
      },
      [sections.equipmentsModels]: {
        title: 'Моделі обладнання',
        icon: 'dashboard',
        tag: 'page',
        permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
        route: { name: sections.equipmentsModels },
        children: {
          add: {
            title: 'Створити модель',
            icon: 'add',
            type: types.PRIMARY,
            permissions: perm.EQUIPMENTS_CONFIG_CREATE,
            action: () => {
              store.commit('template/OPEN_DIALOG', {
                component: () => import('@/components/equipments/models/dialogs/Create')
              })
            }
          }
        }
      }
    }
  }
}
