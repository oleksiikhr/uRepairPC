'use strict'

import io from 'socket.io-client'

export default io(location.origin, {
  path: '/ws/server',
  transports: ['websocket']
})
