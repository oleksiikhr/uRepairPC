'use strict'

import columnTypes from '@/enum/columnTypes'

/*
 * Display on table.
 * Some columns are in store
 *
 * Attributes:
 *  - disableSearch |Boolean| - disable send column on list of resources
 *  - customType |String| - transform value depends on type (bool, timestamp, color)
 *  - hideList |Boolean| - display column on page (Index)
 */

/** @return {array} */
export const equipmentTypes = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]

/** @return {array} */
export const equipmentManufacturers = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]

/** @return {array} */
export const equipmentModels = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'manufacturer_name', label: 'Виробник', 'min-width': 150, sortable: true },
  { prop: 'type_name', label: 'Тип', 'min-width': 150, sortable: true },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]

/** @return {array} */
export const requestStatuses = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'color', label: 'Колір', 'min-width': 100, customType: columnTypes.TYPE_COLOR },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'default', label: 'За замовчуванням', 'min-width': 150, sortable: true, customType: columnTypes.TYPE_BOOL },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]

/** @return {array} */
export const requestPriorities = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'value', label: 'Значення', 'min-width': 100, sortable: true },
  { prop: 'color', label: 'Колір', 'min-width': 100, customType: columnTypes.TYPE_COLOR },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'default', label: 'За замовчуванням', 'min-width': 150, sortable: true, customType: columnTypes.TYPE_BOOL },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]

/** @return {array} */
export const requestTypes = [
  { prop: 'id', label: 'ID', 'min-width': 70, sortable: true },
  { prop: 'name', label: 'Назва', 'min-width': 150, sortable: true },
  { prop: 'description', label: 'Опис', 'min-width': 150 },
  { prop: 'default', label: 'За замовчуванням', 'min-width': 150, sortable: true, customType: columnTypes.TYPE_BOOL },
  { prop: 'updated_at', label: 'Оновлено', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME },
  { prop: 'created_at', label: 'Створений', 'min-width': 200, sortable: true, customType: columnTypes.TYPE_DATETIME }
]
