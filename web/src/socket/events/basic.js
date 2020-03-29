'use strict'

import Notification from 'element-ui/lib/notification'
import { syncEvents } from '@/socket/functions'
import types from '@/enum/types'
import io from '@/socket/io'
import store from '@/store'
import axios from 'axios'

/** @type {string} */
export const SOCKET_HEADER = 'X-Socket-ID'

io.on('connect', () => {
  // Append socketId to every request to the server (API)
  axios.defaults.headers[SOCKET_HEADER] = io.id

  if (store.state.profile.isLogin) {
    // Join to default rooms for listen events
    syncEvents()
  }

  Notification({ title: 'Real-Time підключен', position: 'bottom-left', type: types.SUCCESS })
})

io.on('disconnect', () => {
  // Remove socketId from axios
  axios.defaults.headers[SOCKET_HEADER] = null

  Notification({ title: 'Real-Time відключений', position: 'bottom-left', type: types.WARNING })
})
