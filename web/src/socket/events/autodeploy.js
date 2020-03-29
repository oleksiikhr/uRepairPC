'use strict'

import { runLoadingService } from '@/scripts/dom'
import io from '@/socket/io'

/**
 * @type {object}
 *  key - app name, String
 *  value - { process: Boolean, loading: ElLoadingComponent }
 */
const modules = {}

/** @return {boolean} */
const hasActiveModule = () => Object.values(modules).some(mod => mod.process)

/** @return {any} */
const findActiveModule = () => Object.values(modules).find(mod => mod.process)

/** @returns {ElLoadingComponent} */
const startLoading = () => runLoadingService('Оновлення проекту')

Array(
  { event: 'autodeploy.update' },
  { event: 'server.update' }
)
  .forEach((item) => {
    io.on(item.event, (payload) => {
      if (payload.data.process) {
        modules[item.event] = {
          process: true,
          loading: hasActiveModule() ? null : startLoading()
        }
        return
      }

      modules[item.event].loading.close()
      delete modules[item.event]

      const findModule = findActiveModule()
      if (findModule) {
        findModule.loading = startLoading()
      }
    })
  })
