# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [3.0.0](https://github.com/uRepairPC/web/compare/v2.3.1...v3.0.0) (2019-08-04)


### Bug Fixes

* get element from array on undefined ([b231625](https://github.com/uRepairPC/web/commit/b231625))
* select dialog display roles ([0e91e85](https://github.com/uRepairPC/web/commit/0e91e85))
* vuex rewrite data ([0317e44](https://github.com/uRepairPC/web/commit/0317e44))
* **socket:** delete item for main section ([81f00af](https://github.com/uRepairPC/web/commit/81f00af))
* **store:** cut data when update on section ([2e26d22](https://github.com/uRepairPC/web/commit/2e26d22))


### Features

* **socket:** add module update informatio - autodeloy ([2c5ed78](https://github.com/uRepairPC/web/commit/2c5ed78))
* rewrite permission system, new pagination, optimization, sockets/events, lazy loading, styles, etc ([de05630](https://github.com/uRepairPC/web/commit/de05630))
* socket change notification ([be47f19](https://github.com/uRepairPC/web/commit/be47f19))


### BREAKING CHANGES

* rewrite permission system, new pagination, optimization, sockets/events, lazy loading, styles, etc



### [2.3.1](https://github.com/uRepairPC/web/compare/v2.3.0...v2.3.1) (2019-07-08)


### Bug Fixes

* clear filters and fetch data ([26c8037](https://github.com/uRepairPC/web/commit/26c8037))
* **socket:** update favicon/title on settings.global event ([97460e4](https://github.com/uRepairPC/web/commit/97460e4))
* jump table on scroll bottom (last page) ([eb51629](https://github.com/uRepairPC/web/commit/eb51629))



## [2.3.0](https://github.com/uRepairPC/web/compare/v2.2.1...v2.3.0) (2019-06-18)


### Features

* **socket:** add module update informatio - autodeloy ([06e9434](https://github.com/uRepairPC/web/commit/06e9434))



### [2.2.1](https://github.com/uRepairPC/web/compare/v2.2.0...v2.2.1) (2019-06-08)


### Bug Fixes

* change menu on user permissions ([d31252b](https://github.com/uRepairPC/web/commit/d31252b))
* scroll window to bottom after append new content ([bb4e0dc](https://github.com/uRepairPC/web/commit/bb4e0dc))



## [2.2.0](https://github.com/uRepairPC/web/compare/v2.1.0...v2.2.0) (2019-05-26)


### Bug Fixes

* show items in sidebar on small devices ([f2cac3a](https://github.com/uRepairPC/web/commit/f2cac3a))


### Features

* remove global Search ([8d8ce17](https://github.com/uRepairPC/web/commit/8d8ce17))



## [2.1.0](https://github.com/uRepairPC/web/compare/v2.0.0...v2.1.0) (2019-05-20)


### Bug Fixes

* **socket:** append files on null ([7e9d05d](https://github.com/uRepairPC/web/commit/7e9d05d))
* accept type of permission (props) ([3cf81d1](https://github.com/uRepairPC/web/commit/3cf81d1))
* access for profile page ([6cba9f7](https://github.com/uRepairPC/web/commit/6cba9f7))
* defaultValue not pass on select components ([1829c55](https://github.com/uRepairPC/web/commit/1829c55))
* display add button on TableButtons component ([81e0aea](https://github.com/uRepairPC/web/commit/81e0aea))
* init Select component for equipments and users ([54954b4](https://github.com/uRepairPC/web/commit/54954b4))
* open component (dialog) through store ([0d7d4e7](https://github.com/uRepairPC/web/commit/0d7d4e7))
* set permissions on update profile ([f628b5e](https://github.com/uRepairPC/web/commit/f628b5e))
* show loading on create comment ([dd9f4fe](https://github.com/uRepairPC/web/commit/dd9f4fe))


### Features

* add delete dialog for requests section ([c0e67bb](https://github.com/uRepairPC/web/commit/c0e67bb))
* add display comments to request ([e5289ec](https://github.com/uRepairPC/web/commit/e5289ec))
* add edit dialog for request section ([0da5a22](https://github.com/uRepairPC/web/commit/0da5a22))
* add files to request, improved includePermission fn ([74d3ea4](https://github.com/uRepairPC/web/commit/74d3ea4))
* add permissions on route (Guard) ([ea7f8bd](https://github.com/uRepairPC/web/commit/ea7f8bd))
* add requests/One page ([e566ac5](https://github.com/uRepairPC/web/commit/e566ac5))



## [2.0.0](https://github.com/uRepairPC/web/compare/v1.0.1...v2.0.0) (2019-05-13)


### Bug Fixes

* animation on settings ([4862eb4](https://github.com/uRepairPC/web/commit/4862eb4))
* **firefox:** open global search by hot keys ([14c2480](https://github.com/uRepairPC/web/commit/14c2480))
* caches.delete on not defined ([b592024](https://github.com/uRepairPC/web/commit/b592024))
* **store:** append data on fetch page > 1 ([78c0d14](https://github.com/uRepairPC/web/commit/78c0d14))
* display request on history block ([f523047](https://github.com/uRepairPC/web/commit/f523047))
* loading on Global dialog (settings) ([8736ca4](https://github.com/uRepairPC/web/commit/8736ca4))
* logout on edit currentUser by websocket + working update ([6ccbefa](https://github.com/uRepairPC/web/commit/6ccbefa))
* logout on refresh token when go to another page ([5a31382](https://github.com/uRepairPC/web/commit/5a31382))
* refresh list on click button ([f0b5296](https://github.com/uRepairPC/web/commit/f0b5296))
* register pwa, failed navigateFallback ([399fc9a](https://github.com/uRepairPC/web/commit/399fc9a))
* run tests ([4b18934](https://github.com/uRepairPC/web/commit/4b18934))
* send request with empty search ([86122b3](https://github.com/uRepairPC/web/commit/86122b3))
* show equipments/Cascader component without permissions ([c2d76ac](https://github.com/uRepairPC/web/commit/c2d76ac))


### chore

* **permissions:** sync data from backend ([b5db934](https://github.com/uRepairPC/web/commit/b5db934))


### Features

* add Manifest page, move dialog storage to separate ([9906b2c](https://github.com/uRepairPC/web/commit/9906b2c))
* add request/Create page ([50bb0d1](https://github.com/uRepairPC/web/commit/50bb0d1))
* add standard-version library ([b658095](https://github.com/uRepairPC/web/commit/b658095))
* disable fetchList on create/delete/edit element on settigns tables ([4559636](https://github.com/uRepairPC/web/commit/4559636))
* **settings:** add manifest EditDialog ([0029b88](https://github.com/uRepairPC/web/commit/0029b88))
* GenerateForm support filter by permissions ([0caaf53](https://github.com/uRepairPC/web/commit/0caaf53))
* make dynamic listener for socket.io ([7075105](https://github.com/uRepairPC/web/commit/7075105))
* new logic for events, permissions ([5e1222d](https://github.com/uRepairPC/web/commit/5e1222d))
* rewrite settings frontend to backend, change url ([ca56ec6](https://github.com/uRepairPC/web/commit/ca56ec6))
* rewrite styles on full page scroll ([7656d6f](https://github.com/uRepairPC/web/commit/7656d6f))
* support hide attribute on GenerateForm ([8e06526](https://github.com/uRepairPC/web/commit/8e06526))


### BREAKING CHANGES

* **permissions:** sync data from backend
