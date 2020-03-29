'use strict'

import { websocket } from '@/data/env'
import io from 'socket.io-client'

export default io(websocket)
