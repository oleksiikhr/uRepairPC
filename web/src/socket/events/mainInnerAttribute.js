'use strict'

import socketTypes from '@/enum/socketTypes'
import { isArray } from '@/scripts/helpers'
import { notify } from '@/socket/functions'
import sections from '@/enum/sections'
import io from '@/socket/io'
import store from '@/store'

/*
 * NOTE: Values in attr must be Array!
 */

Array(
  {
    event: 'server.equipment_files',
    section: sections.equipments,
    sectionAttrId: 'equipment_id',
    attr: 'files',
    orderByAsc: false,
    title: 'Обладнання - Файли'
  },
  {
    event: 'server.request_files',
    section: sections.requests,
    sectionAttrId: 'request_id',
    attr: 'files',
    orderByAsc: false,
    title: 'Замовлення - Файли'
  },
  {
    event: 'server.request_comments',
    section: sections.requests,
    sectionAttrId: 'request_id',
    attr: 'comments',
    orderByAsc: true,
    title: 'Замовлення - Коментарі'
  }
)
  .forEach((obj) => {
    io.on(obj.event, (payload) => {

      // Get sidebar section, if items not found - return
      const sidebar = store.state.template.sidebar[obj.section]
      if (!payload.params || !payload.params[obj.sectionAttrId]) {
        return
      }

      // Notification
      switch (payload.type) {
      case socketTypes.UPDATE:
        notify(`[${payload.params[obj.sectionAttrId]}] ${obj.title}`).update()
        break
      case socketTypes.CREATE:
        notify(`[${payload.params[obj.sectionAttrId]}] ${obj.title}`).create()
        break
      case socketTypes.DELETE:
        notify(`[${payload.params[obj.sectionAttrId]}] ${obj.title}`).delete()
        break
      }

      if (!sidebar) {
        return
      }

      // Get item from sidebarItem, if user not view this page - return
      const sidebarItem = sidebar[payload.params[obj.sectionAttrId]]
      if (!sidebarItem) {
        return
      }

      // If event is update/delete and has attr - try update item
      if ((payload.type === socketTypes.UPDATE || payload.type === socketTypes.DELETE) &&
        isArray(sidebarItem[obj.attr]) && payload.params.id) {

        // Find [obj.attr] by id in array
        const sidebarItemData = [...sidebarItem[obj.attr]]
        const findIndex = sidebarItemData.findIndex(item => item.id === payload.params.id)

        if (~findIndex) {
          if (payload.type === socketTypes.UPDATE) {
            sidebarItemData[findIndex] = { ...sidebarItemData[findIndex], ...payload.data }
          } else {
            sidebarItemData.splice(findIndex, 1)
          }

          store.commit('template/ADD_SIDEBAR_ITEM', {
            section: obj.section,
            data: { ...sidebarItem, [obj.attr]: sidebarItemData }
          })
        }
      }

      if (payload.type === socketTypes.CREATE) {
        // Accept [{}, {}, ..] or {}
        const payloadDataArray = isArray(payload.data) ? payload.data : [payload.data]
        let dataAttr = []

        if (obj.orderByAsc) {
          dataAttr = [...sidebarItem[obj.attr] || [], ...payloadDataArray]
        } else {
          dataAttr = [...payloadDataArray, ...sidebarItem[obj.attr] || []]
        }

        store.commit('template/ADD_SIDEBAR_ITEM', {
          section: obj.section,
          data: { ...sidebarItem, [obj.attr]: dataAttr }
        })
      }
    })
  })
