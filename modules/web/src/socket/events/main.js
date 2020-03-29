'use strict'

import socketTypes from '@/enum/socketTypes'
import { notify } from '@/socket/functions'
import { hasPerm } from '@/scripts/utils'
import sections from '@/enum/sections'
import logout from '@/scripts/logout'
import * as perm from '@/enum/perm'
import router from '@/router'
import io from '@/socket/io'
import store from '@/store'

Array(
  {
    event: 'server.equipments',
    store: 'equipments',
    section: sections.equipments,
    title: 'Обладнання',
    msgAttr: 'type_name'
  },
  {
    event: 'server.users',
    store: 'users',
    section: sections.users,
    title: 'Користувачі',
    msgAttr: 'last_name'
  },
  {
    event: 'server.roles',
    store: 'roles',
    section: sections.roles,
    title: 'Ролі',
    msgAttr: 'name'
  },
  {
    event: 'server.requests',
    store: 'requests',
    section: sections.requests,
    title: 'Замовлення',
    msgAttr: 'title'
  }
)
  .forEach((obj) => {
    io.on(obj.event, (payload) => {
      if (payload.type === socketTypes.CREATE) {
        handleCreate(payload, obj)
        return
      }

      const sidebar = store.state.template.sidebar[obj.store]
      if (!payload.params || !payload.params.id) {
        return
      }

      // Current user (profile)
      if (obj.section === sections.users && store.state.profile.user.id === payload.params.id) {
        // If the current user is deleted - logout
        if (payload.type === socketTypes.DELETE) {
          logout()
          return
        }

        handleCurrentUser(payload)
      }

      // Sidebar/SidebarItem can empty, but user see list of items (Index.vue)
      // Update general list
      if (payload.type === socketTypes.UPDATE) {
        // Some data may not have id
        store.commit(`${obj.section}/UPDATE_ITEM`, { ...payload.data, id: payload.params.id })
        notify(obj.title, `[${payload.params.id}] ${payload.data[obj.msgAttr] || '-'}`).update()
      } else {
        store.commit(`${obj.section}/DELETE_ITEM`, payload.params.id)
        notify(obj.title, `[${payload.params.id}]`).delete()
      }

      // Section in sidebar not found
      if (!sidebar) {
        return
      }

      // Element in sidebar section not found
      const sidebarItem = sidebar[payload.params.id]
      if (!sidebarItem) {
        return
      }

      if (payload.type === socketTypes.UPDATE) {
        handleUpdate(payload, obj)
      } else {
        handleDelete(payload, obj)
      }
    })
  })

function handleCreate(payload, obj) {
  store.commit(`${obj.section}/APPEND_DATA`, payload.data)

  // Only users who can create other users or current profile - accept notify
  if (obj.section !== sections.users || hasPerm(perm.USERS_CREATE)) {
    notify(obj.title, `[${payload.data.id}] ${payload.data[obj.msgAttr] || '-'}`).create()
  }
}

function handleCurrentUser(payload) {
  // Set new data to the current user
  store.commit('profile/SET_USER', payload.data)

  // Update permissions
  if (payload.data.permissions) {
    store.commit('profile/SET_PERMISSIONS', payload.data.permissions)
  }
}

function handleUpdate(payload, obj) {
  store.commit('template/ADD_SIDEBAR_ITEM', {
    section: obj.section,
    data: { id: payload.params.id, ...payload.data }
  })
}

function handleDelete(payload, obj) {
  const route = router.currentRoute

  /*
   * Item is deleted and user is stay on this page - leave the page
   */
  if (`${obj.section}-id` === route.name && payload.params.id === +route.params.id) {
    router.push({ name: obj.section })
  }

  store.commit('template/REMOVE_SIDEBAR_ITEM', {
    section: obj.section,
    id: payload.params.id
  })
}
