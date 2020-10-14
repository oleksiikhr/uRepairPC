'use strict'

/*
 * Don't use require.context for performance
 */

// Equipments
import equipmentManufacturers from '@/store/modules/equipmentManufacturers'
import equipmentModels from '@/store/modules/equipmentModels'
import equipmentTypes from '@/store/modules/equipmentTypes'
import equipments from '@/store/modules/equipments'

// Requests
import requestPriorities from '@/store/modules/requestPriorities'
import requestStatuses from '@/store/modules/requestStatuses'
import requestTypes from '@/store/modules/requestTypes'
import requests from '@/store/modules/requests'

// Other
import permissions from '@/store/modules/permissions'
import settings from '@/store/modules/settings'
import template from '@/store/modules/template'
import profile from '@/store/modules/profile'
import users from '@/store/modules/users'
import roles from '@/store/modules/roles'
import jobs from '@/store/modules/jobs'

export default {
  equipmentManufacturers,
  equipmentModels,
  equipmentTypes,
  equipments,

  requestPriorities,
  requestStatuses,
  requestTypes,
  requests,

  permissions,
  settings,
  template,
  profile,
  users,
  roles,
  jobs
}
