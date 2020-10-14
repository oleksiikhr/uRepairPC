'use strict'

import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import * as perm from '@/enum/perm'
import store from '@/store'

/**
 * NOTE: Support meta permissions
 * @example
 * meta: {
 *   check: (to, from) => false, // call callback on beforeRoute guard
 *   permissions: permissions.USER_VIEW, // check on hasPerm
 *   failRouteName: 'home' // go to this route name, if permissions not found
 * }
 */

/** @type {object} */
const notAuthorizedRoutes = {
  path: `/${sections.auth}`,
  component: () => import('@/layouts/NotAuthorized'),
  children: [
    {
      path: '/',
      name: sections.auth,
      component: () => import('@/pages/Auth')
    }
  ]
}

/** @type {object} */
const authorizedRoutes = {
  path: '',
  component: () => import('@/layouts/Default'),
  children: [
    {
      path: '/',
      name: sections.home,
      component: () => import('@/pages/Home')
    },
    // -------------------------------------------------------------------------------------- Requests
    {
      path: `/${sections.requests}`,
      name: sections.requests,
      component: () => import('@/pages/requests/Index'),
      meta: {
        check: () => hasPerm(perm.REQUESTS_VIEW_SECTION) && hasPerm([
          perm.REQUESTS_VIEW_ALL, perm.REQUESTS_VIEW_ASSIGN, perm.REQUESTS_VIEW_OWN
        ])
      }
    },
    {
      path: `/${sections.requests}/:id(\\d+)`,
      name: `${sections.requests}-id`,
      component: () => import('@/pages/requests/One'),
      meta: {
        check: () => hasPerm(perm.REQUESTS_VIEW_SECTION) && hasPerm([
          perm.REQUESTS_VIEW_ALL, perm.REQUESTS_VIEW_ASSIGN, perm.REQUESTS_VIEW_OWN
        ])
      }
    },
    {
      path: `/${sections.requests}/create`,
      name: `${sections.requests}-create`,
      component: () => import('@/pages/requests/Create'),
      meta: {
        permissions: perm.REQUESTS_CREATE,
        failRouteName: sections.requests
      }
    },
    // -------------------------------------------------------------------------------------- Users
    {
      path: `/${sections.users}`,
      name: sections.users,
      component: () => import('@/pages/users/Index'),
      meta: {
        check: () => hasPerm(perm.USERS_VIEW_SECTION) && hasPerm(perm.USERS_VIEW_ALL)
      }
    },
    {
      path: `/${sections.users}/:id(\\d+)`,
      name: `${sections.users}-id`,
      component: () => import('@/pages/users/One'),
      meta: {
        check: (to) => {
          // Profile page
          if (+to.params.id === store.state.profile.user.id) {
            return true
          }

          return hasPerm(perm.USERS_VIEW_SECTION) && hasPerm(perm.USERS_VIEW_ALL)
        }
      }
    },
    {
      path: `/${sections.users}/create`,
      name: `${sections.users}-create`,
      component: () => import('@/pages/users/Create'),
      meta: {
        permissions: perm.USERS_CREATE,
        failRouteName: sections.users
      }
    },
    // -------------------------------------------------------------------------------------- Roles
    {
      path: `/${sections.roles}`,
      name: sections.roles,
      component: () => import('@/pages/roles/Index'),
      meta: {
        check: () => hasPerm(perm.ROLES_VIEW_SECTION) && hasPerm(perm.ROLES_VIEW_ALL)
      }
    },
    {
      path: `/${sections.roles}/:id(\\d+)`,
      name: `${sections.roles}-id`,
      component: () => import('@/pages/roles/One'),
      meta: {
        check: () => hasPerm(perm.ROLES_VIEW_SECTION) && hasPerm(perm.ROLES_VIEW_ALL)
      }
    },
    {
      path: `/${sections.roles}/create`,
      name: `${sections.roles}-create`,
      component: () => import('@/pages/roles/Create'),
      meta: {
        permissions: perm.ROLES_EDIT_ALL,
        failRouteName: sections.roles
      }
    },
    // -------------------------------------------------------------------------------------- Equipments
    {
      path: `/${sections.equipments}`,
      name: sections.equipments,
      component: () => import('@/pages/equipments/Index'),
      meta: {
        check: () => hasPerm(perm.EQUIPMENTS_VIEW_SECTION) && hasPerm([
          perm.EQUIPMENTS_VIEW_ALL, perm.EQUIPMENTS_VIEW_OWN
        ])
      }
    },
    {
      path: `/${sections.equipments}/:id(\\d+)`,
      name: `${sections.equipments}-id`,
      component: () => import('@/pages/equipments/One'),
      meta: {
        check: () => hasPerm(perm.EQUIPMENTS_VIEW_SECTION) && hasPerm([
          perm.EQUIPMENTS_VIEW_ALL, perm.EQUIPMENTS_VIEW_OWN
        ])
      }
    },
    {
      path: `/${sections.equipments}/create`,
      name: `${sections.equipments}-create`,
      component: () => import('@/pages/equipments/Create'),
      meta: {
        permissions: perm.EQUIPMENTS_CREATE,
        failRouteName: sections.equipments
      }
    },
    // -------------------------------------------------------------------------------------- Jobs
    {
      path: `/${sections.jobs}`,
      name: sections.jobs,
      component: () => import('@/pages/jobs/Index'),
      meta: {
        check: () => hasPerm(perm.JOBS_VIEW_SECTION) && hasPerm(perm.JOBS_VIEW_ALL)
      }
    },
    // -------------------------------------------------------------------------------------- Settings
    {
      path: `/${sections.settings}`,
      component: () => import('@/pages/settings/Core'),
      children: [
        {
          path: `/${sections.settings}`,
          name: sections.settings,
          component: () => import('@/pages/settings/Index'),
          meta: {
            permissions: [
              perm.GLOBAL_SETTINGS_EDIT,
              perm.GLOBAL_MANIFEST_EDIT,
              perm.REQUESTS_CONFIG_VIEW_SECTION,
              perm.EQUIPMENTS_CONFIG_VIEW_SECTION
            ]
          }
        },
        {
          path: `/${sections.settings}/global`,
          name: sections.settingsGlobal,
          component: () => import('@/pages/settings/Global'),
          meta: {
            permissions: perm.GLOBAL_SETTINGS_EDIT,
            failRouteName: sections.settings
          }
        },
        {
          path: `/${sections.settings}/manifest`,
          name: sections.settingsManifest,
          component: () => import('@/pages/settings/Manifest'),
          meta: {
            permissions: perm.GLOBAL_MANIFEST_EDIT,
            failRouteName: sections.settings
          }
        },
        // -------------------------------------------------------------------------------------- Settings - Requests
        {
          path: `/${sections.settings}/${sections.requestsStatuses}`,
          name: `${sections.requestsStatuses}`,
          component: () => import('@/pages/settings/requests/Statuses'),
          meta: {
            permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        },
        {
          path: `/${sections.settings}/${sections.requestsPriorities}`,
          name: `${sections.requestsPriorities}`,
          component: () => import('@/pages/settings/requests/Priorities'),
          meta: {
            permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        },
        {
          path: `/${sections.settings}/${sections.requestsTypes}`,
          name: `${sections.requestsTypes}`,
          component: () => import('@/pages/settings/requests/Types'),
          meta: {
            permissions: perm.REQUESTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        },
        // -------------------------------------------------------------------------------------- Settings - Equipments
        {
          path: `/${sections.settings}/${sections.equipmentsManufacturers}`,
          name: `${sections.equipmentsManufacturers}`,
          component: () => import('@/pages/settings/equipments/Manufacturers'),
          meta: {
            permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        },
        {
          path: `/${sections.settings}/${sections.equipmentsTypes}`,
          name: `${sections.equipmentsTypes}`,
          component: () => import('@/pages/settings/equipments/Types'),
          meta: {
            permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        },
        {
          path: `/${sections.settings}/${sections.equipmentsModels}`,
          name: `${sections.equipmentsModels}`,
          component: () => import('@/pages/settings/equipments/Models'),
          meta: {
            permissions: perm.EQUIPMENTS_CONFIG_VIEW_SECTION,
            failRouteName: sections.settings
          }
        }
      ]
    },
    {
      path: '*',
      redirect: { name: sections.home }
    }
  ]
}

/**
 * For route guard.
 * @type {array}
 */
export const notAuthorizedRoutesName = notAuthorizedRoutes.children
  .map(route => route.name)

export default [
  notAuthorizedRoutes,
  authorizedRoutes
]
