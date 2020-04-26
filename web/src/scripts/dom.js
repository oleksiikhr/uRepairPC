'use strict'

import Loading from 'element-ui/lib/loading'
import Message from 'element-ui/lib/message'

/**
 * @param {String, Node} node
 */
export function selectAll(node) {
  if (!(node instanceof Node)) {
    node = document.querySelector(node)
  }

  window.getSelection().selectAllChildren(node)
}

/**
 * @return {boolean}
 */
export function execCopy() {
  return document.execCommand('copy')
}

/**
 * @param {Node} node
 */
export function copyNode(node) {
  selectAll(node)

  if (execCopy()) {
    Message('Скопійовано в буфер')
  } else {
    Message('Виникла помилка')
  }
}

/**
 * @param {string} text
 * @returns {ElLoadingComponent}
 */
export function runLoadingService(text) {
  return Loading.service({
    lock: true,
    text,
    spinner: 'el-icon-loading',
    background: 'rgba(0, 0, 0, .7)'
  })
}

/**
 * Changing website favicon dynamically.
 * @see https://stackoverflow.com/a/260876/9612245
 */
export function setFavicon(href) {
  const link = document.querySelector('link[rel*=\'icon\']') || document.createElement('link')
  link.type = 'image/x-icon'
  link.rel = 'shortcut icon'
  link.href = href
  document.getElementsByTagName('head')[0].appendChild(link)
}
