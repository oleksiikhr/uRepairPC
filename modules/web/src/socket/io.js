'use strict'

import { serverSocket } from '@/data/env'
import io from 'socket.io-client'

export default io(serverSocket)
