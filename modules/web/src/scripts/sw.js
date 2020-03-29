'use strict'

import { isDev } from '@/data/env'

// Register Service Worker
if ('serviceWorker' in navigator && !isDev) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
  })
}
