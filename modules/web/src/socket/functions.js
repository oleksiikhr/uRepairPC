'use strict'

import Notification from 'element-ui/lib/notification'
import sections from '@/enum/sections'
import io from '@/socket/io'
import axios from 'axios'

/**
 * Sync rooms by permissions on socket server.
 */
export function syncEvents() {
  axios.post('listeners/sync')
}

/**
 * @param {string} section - enum
 * @param {number} id
 */
export function offEventDynamic(section, id) {
  io.emit('leave', generateRooms(section, id))
}

/**
 * @param {string} title
 * @param {string} msg
 * @returns {{create: (function(): (*)), update: (function(): (*)), delete: (function(): (*))}}
 */
export function notify(title, msg = '') {
  const basic = {
    title: `RT: ${title}`,
    message: msg,
    position: 'bottom-left'
  }

  return {
    create: () => {
      return Notification({
        ...basic,
        iconClass: 'el-icon-circle-plus-outline',
        customClass: 'rt--create'
      })
    },
    update: () => {
      return Notification({
        ...basic,
        iconClass: 'el-icon-edit-outline',
        customClass: 'rt--update'
      })
    },
    delete: () => {
      return Notification({
        ...basic,
        iconClass: 'el-icon-delete',
        customClass: 'rt--delete'
      })
    }
  }
}

/**
 * Get multiple rooms from section if can.
 * @param {string} section
 * @param {number} id
 * @return {array}
 */
function generateRooms(section, id) {
  const rooms = [`${section}.${id}`]

  switch (section) {
  case sections.equipments:
    rooms.push(`equipment_files.${id}`)
    break
  case sections.requests:
    rooms.push(`request_files.${id}`)
    rooms.push(`request_comments.${id}`)
    break
  }

  return rooms
}
