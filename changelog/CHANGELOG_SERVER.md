# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [3.0.0](https://github.com/uRepairPC/server/compare/v2.0.0...v3.0.0) (2019-08-04)


### Bug Fixes

* after create request - missing relationship data ([963581e](https://github.com/uRepairPC/server/commit/963581e))
* check permission for comment/file action ([11a1171](https://github.com/uRepairPC/server/commit/11a1171))
* join to rooms after create events (support array) ([69f2d14](https://github.com/uRepairPC/server/commit/69f2d14))
* logout (invalidate token uncomment) ([177ea92](https://github.com/uRepairPC/server/commit/177ea92))
* name of class IJsonFile to IJsonFIle ([67cb077](https://github.com/uRepairPC/server/commit/67cb077))
* permissions for download files + PAGINATE_DEFAULT set to 25 ([8ce3bb2](https://github.com/uRepairPC/server/commit/8ce3bb2))
* send events with roles (user edited), without permission ([fd44596](https://github.com/uRepairPC/server/commit/fd44596))
* **socket:** broadcast equipment with relationship after create ([ad358df](https://github.com/uRepairPC/server/commit/ad358df))
* show requests for assign_id user ([1b2e4db](https://github.com/uRepairPC/server/commit/1b2e4db))
* typo ([e1ffb19](https://github.com/uRepairPC/server/commit/e1ffb19))
* update permissions ([3b79c69](https://github.com/uRepairPC/server/commit/3b79c69))


### Features

* add request comments ([c6c63be](https://github.com/uRepairPC/server/commit/c6c63be))
* new socket system, change migrations, permissions, docs, observers ([740a564](https://github.com/uRepairPC/server/commit/740a564))
* rewrite role system, fix many bugs, improved code, change migration ([58bc51f](https://github.com/uRepairPC/server/commit/58bc51f))
* rewrite socket (events) system, correct roles, add more events ([6a6462a](https://github.com/uRepairPC/server/commit/6a6462a))


### BREAKING CHANGES

* new socket system, change migrations, permissions, docs, observers
* rewrite socket (events) system, correct roles, add more events
* rewrite role system, fix many bugs, improved code, change migration



## [2.1.0](https://github.com/uRepairPC/server/compare/v2.0.0...v2.1.0) (2019-05-26)


### Bug Fixes

* check permission for comment/file action ([11a1171](https://github.com/uRepairPC/server/commit/11a1171))
* logout (invalidate token uncomment) ([177ea92](https://github.com/uRepairPC/server/commit/177ea92))
* send events with roles (user edited), without permission ([fd44596](https://github.com/uRepairPC/server/commit/fd44596))
* show requests for assign_id user ([1b2e4db](https://github.com/uRepairPC/server/commit/1b2e4db))
* typo ([e1ffb19](https://github.com/uRepairPC/server/commit/e1ffb19))


### Features

* add request comments ([c6c63be](https://github.com/uRepairPC/server/commit/c6c63be))



## 2.0.0 (2019-05-16)


### Bug Fixes

* refresh token when user is deleted ([96d85e4](https://github.com/uRepairPC/server/commit/96d85e4))
* **socket:** join to request rooms ([cd7d149](https://github.com/uRepairPC/server/commit/cd7d149))
* relationship for EquipmentType (not EquipmentModel) ([64a0779](https://github.com/uRepairPC/server/commit/64a0779))


### Features

* **file:** improved working with json files ([ba05121](https://github.com/uRepairPC/server/commit/ba05121))
* **permissions:** add global.manifest, change settings ([0c3fd43](https://github.com/uRepairPC/server/commit/0c3fd43))
* add auth.profile route/method ([3b914cf](https://github.com/uRepairPC/server/commit/3b914cf))
* add files to request ([3a273f3](https://github.com/uRepairPC/server/commit/3a273f3))
* add manifest (controller, request, resource, etc) ([0f40424](https://github.com/uRepairPC/server/commit/0f40424))
* add update, destroy methods for request section + events ([80b7f06](https://github.com/uRepairPC/server/commit/80b7f06))
* big update for socket (new logic, broadcast), add many of events ([6e2c532](https://github.com/uRepairPC/server/commit/6e2c532))
* new logic to work with user avatar (files) ([eee0d7c](https://github.com/uRepairPC/server/commit/eee0d7c))
* SettignsFrontend  (save data on the file, remove tables, rewrite code) ([47ab97e](https://github.com/uRepairPC/server/commit/47ab97e))


### BREAKING CHANGES

* **permissions:** add global.manifest, change settings
