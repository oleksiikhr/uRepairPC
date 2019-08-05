<?php

namespace App\Enums;

use ReflectionClass;

abstract class Perm
{
    // Profile
    const PROFILE_EDIT = 'profile.edit';

    // Users
    const USERS_VIEW_SECTION = 'users.view_section';
    const USERS_VIEW_ALL = 'users.view_all';
    const USERS_EDIT_ALL = 'users.edit_all';
    const USERS_CREATE = 'users.create';
    const USERS_DELETE_ALL = 'users.delete_all';

    // Roles
    const ROLES_VIEW_SECTION = 'roles.view_section';
    const ROLES_VIEW_ALL = 'roles.view_all';
    const ROLES_EDIT_ALL = 'roles.edit_all';

    // Equipments
    const EQUIPMENTS_VIEW_SECTION = 'equipments.view_section';
    const EQUIPMENTS_VIEW_ALL = 'equipments.view_all';
    const EQUIPMENTS_VIEW_OWN = 'equipments.view_own';
    const EQUIPMENTS_EDIT_ALL = 'equipments.edit_all';
    const EQUIPMENTS_EDIT_OWN = 'equipments.edit_own';
    const EQUIPMENTS_CREATE = 'equipments.create';
    const EQUIPMENTS_DELETE_ALL = 'equipments.delete_all';
    const EQUIPMENTS_DELETE_OWN = 'equipments.delete_own';

    // Equipment Files
    const EQUIPMENTS_FILES_VIEW_ALL = 'equipments.files.view_all';
    const EQUIPMENTS_FILES_VIEW_OWN = 'equipments.files.view_own';
    const EQUIPMENTS_FILES_DOWNLOAD_ALL = 'equipments.files.download_all';
    const EQUIPMENTS_FILES_DOWNLOAD_OWN = 'equipments.files.download_own';
    const EQUIPMENTS_FILES_EDIT_ALL = 'equipments.files.edit_all';
    const EQUIPMENTS_FILES_EDIT_OWN = 'equipments.files.edit_own';
    const EQUIPMENTS_FILES_CREATE = 'equipments.files.create';
    const EQUIPMENTS_FILES_DELETE_ALL = 'equipments.files.delete_all';
    const EQUIPMENTS_FILES_DELETE_OWN = 'equipments.files.delete_own';

    // Equipments Config
    const EQUIPMENTS_CONFIG_VIEW_SECTION = 'equipments.config.view_section';
    const EQUIPMENTS_CONFIG_VIEW_ALL = 'equipments.config.view_all';
    const EQUIPMENTS_CONFIG_EDIT_ALL = 'equipments.config.edit_all';
    const EQUIPMENTS_CONFIG_EDIT_OWN = 'equipments.config.edit_own';
    const EQUIPMENTS_CONFIG_CREATE = 'equipments.config.create';
    const EQUIPMENTS_CONFIG_DELETE_ALL = 'equipments.config.delete_all';
    const EQUIPMENTS_CONFIG_DELETE_OWN = 'equipments.config.delete_own';

    // Requests
    const REQUESTS_VIEW_SECTION = 'requests.view_section';
    const REQUESTS_VIEW_ALL = 'requests.view_all';
    const REQUESTS_VIEW_OWN = 'requests.view_own';
    const REQUESTS_VIEW_ASSIGN = 'requests.view_assign';
    const REQUESTS_EDIT_ALL = 'requests.edit_all';
    const REQUESTS_EDIT_OWN = 'requests.edit_own';
    const REQUESTS_EDIT_ASSIGN = 'requests.edit_assign';
    const REQUESTS_CREATE = 'requests.create';
    const REQUESTS_DELETE_ALL = 'requests.delete_all';
    const REQUESTS_DELETE_OWN = 'requests.delete_own';
    const REQUESTS_DELETE_ASSIGN = 'requests.delete_assign';

    // Requests Comments
    const REQUESTS_COMMENTS_VIEW_ALL = 'requests.comments.view_all';
    const REQUESTS_COMMENTS_EDIT_ALL = 'requests.comments.edit_all';
    const REQUESTS_COMMENTS_EDIT_OWN = 'requests.comments.edit_own';
    const REQUESTS_COMMENTS_CREATE = 'requests.comments.create';
    const REQUESTS_COMMENTS_DELETE_ALL = 'requests.comments.delete_all';
    const REQUESTS_COMMENTS_DELETE_OWN = 'requests.comments.delete_own';

    // Requests Files
    const REQUESTS_FILES_VIEW_ALL = 'requests.files.view_all';
    const REQUESTS_FILES_VIEW_OWN = 'requests.files.view_own';
    const REQUESTS_FILES_DOWNLOAD_ALL = 'requests.files.download_all';
    const REQUESTS_FILES_DOWNLOAD_OWN = 'requests.files.download_own';
    const REQUESTS_FILES_EDIT_ALL = 'requests.files.edit_all';
    const REQUESTS_FILES_EDIT_OWN = 'requests.files.edit_own';
    const REQUESTS_FILES_CREATE = 'requests.files.create';
    const REQUESTS_FILES_DELETE_ALL = 'requests.files.delete_all';
    const REQUESTS_FILES_DELETE_OWN = 'requests.files.delete_own';

    // Requests Config
    const REQUESTS_CONFIG_VIEW_SECTION = 'requests.config.view_section';
    const REQUESTS_CONFIG_VIEW_ALL = 'requests.config.view_all';
    const REQUESTS_CONFIG_EDIT_ALL = 'requests.config.edit_all';
    const REQUESTS_CONFIG_EDIT_OWN = 'requests.config.edit_own';
    const REQUESTS_CONFIG_CREATE = 'requests.config.create';
    const REQUESTS_CONFIG_DELETE_ALL = 'requests.config.delete_all';
    const REQUESTS_CONFIG_DELETE_OWN = 'requests.config.delete_own';

    // Global
    const GLOBAL_SETTINGS_EDIT = 'global.settings.edit';
    const GLOBAL_MANIFEST_EDIT = 'global.manifest.edit';

    // Disable access to resource
    const DISABLE = '-';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        try {
            $constants = (new ReflectionClass(__CLASS__))->getConstants();

            return array_values($constants);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return object
     */
    public static function getStructure()
    {
        return (object) [
            __('perm.sections.profile') => [
                self::PROFILE_EDIT => __('perm.actions.edit'),
            ],
            __('perm.sections.users') => [
                self::USERS_VIEW_SECTION => __('perm.actions.view_section'),
                self::USERS_VIEW_ALL => __('perm.actions.view_all'),
                self::USERS_EDIT_ALL => __('perm.actions.edit_all'),
                self::USERS_CREATE => __('perm.actions.create'),
                self::USERS_DELETE_ALL => __('perm.actions.delete_all'),
            ],
            __('perm.sections.roles') => [
                self::ROLES_VIEW_SECTION => __('perm.actions.view_section'),
                self::ROLES_VIEW_ALL => __('perm.actions.view_all'),
                self::ROLES_EDIT_ALL => __('perm.actions.edit_all'),
            ],
            __('perm.sections.equipments') => [
                self::EQUIPMENTS_VIEW_SECTION => __('perm.actions.view_section'),
                self::EQUIPMENTS_VIEW_ALL => __('perm.actions.view_all'),
                self::EQUIPMENTS_VIEW_OWN => __('perm.actions.edit_all'),
                self::EQUIPMENTS_EDIT_ALL => __('perm.actions.edit_all'),
                self::EQUIPMENTS_EDIT_OWN => __('perm.actions.edit_own'),
                self::EQUIPMENTS_CREATE => __('perm.actions.create'),
                self::EQUIPMENTS_DELETE_ALL => __('perm.actions.delete_all'),
                self::EQUIPMENTS_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.equipments_files') => [
                self::EQUIPMENTS_FILES_VIEW_ALL => __('perm.actions.view_all'),
                self::EQUIPMENTS_FILES_VIEW_OWN => __('perm.actions.view_own'),
                self::EQUIPMENTS_FILES_DOWNLOAD_ALL => __('perm.actions.download_all'),
                self::EQUIPMENTS_FILES_DOWNLOAD_OWN => __('perm.actions.download_own'),
                self::EQUIPMENTS_FILES_EDIT_ALL => __('perm.actions.edit_all'),
                self::EQUIPMENTS_FILES_EDIT_OWN => __('perm.actions.edit_own'),
                self::EQUIPMENTS_FILES_CREATE => __('perm.actions.create'),
                self::EQUIPMENTS_FILES_DELETE_ALL => __('perm.actions.delete_all'),
                self::EQUIPMENTS_FILES_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.equipments_config') => [
                self::EQUIPMENTS_CONFIG_VIEW_SECTION => __('perm.actions.view_section'),
                self::EQUIPMENTS_CONFIG_VIEW_ALL => __('perm.actions.view_all'),
                self::EQUIPMENTS_CONFIG_EDIT_ALL => __('perm.actions.edit_all'),
                self::EQUIPMENTS_CONFIG_EDIT_OWN => __('perm.actions.edit_own'),
                self::EQUIPMENTS_CONFIG_CREATE => __('perm.actions.create'),
                self::EQUIPMENTS_CONFIG_DELETE_ALL => __('perm.actions.delete_all'),
                self::EQUIPMENTS_CONFIG_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.requests') => [
                self::REQUESTS_VIEW_SECTION => __('perm.actions.view_section'),
                self::REQUESTS_VIEW_ALL => __('perm.actions.view_all'),
                self::REQUESTS_VIEW_OWN => __('perm.actions.view_own'),
                self::REQUESTS_VIEW_ASSIGN => __('perm.actions.view_assign'),
                self::REQUESTS_EDIT_ALL => __('perm.actions.edit_all'),
                self::REQUESTS_EDIT_OWN => __('perm.actions.edit_own'),
                self::REQUESTS_EDIT_ASSIGN => __('perm.actions.edit_assign'),
                self::REQUESTS_CREATE => __('perm.actions.create'),
                self::REQUESTS_DELETE_ALL => __('perm.actions.delete_all'),
                self::REQUESTS_DELETE_OWN => __('perm.actions.delete_own'),
                self::REQUESTS_DELETE_ASSIGN => __('perm.actions.delete_assign'),
            ],
            __('perm.sections.requests_comments') => [
                self::REQUESTS_COMMENTS_VIEW_ALL => __('perm.actions.view_all'),
                self::REQUESTS_COMMENTS_EDIT_ALL => __('perm.actions.edit_all'),
                self::REQUESTS_COMMENTS_EDIT_OWN => __('perm.actions.edit_own'),
                self::REQUESTS_COMMENTS_CREATE => __('perm.actions.create'),
                self::REQUESTS_COMMENTS_DELETE_ALL => __('perm.actions.delete_all'),
                self::REQUESTS_COMMENTS_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.requests_files') => [
                self::REQUESTS_FILES_VIEW_ALL => __('perm.actions.view_all'),
                self::REQUESTS_FILES_VIEW_OWN => __('perm.actions.view_own'),
                self::REQUESTS_FILES_DOWNLOAD_ALL => __('perm.actions.download_all'),
                self::REQUESTS_FILES_DOWNLOAD_OWN => __('perm.actions.download_own'),
                self::REQUESTS_FILES_EDIT_ALL => __('perm.actions.edit_all'),
                self::REQUESTS_FILES_EDIT_OWN => __('perm.actions.edit_own'),
                self::REQUESTS_FILES_CREATE => __('perm.actions.create'),
                self::REQUESTS_FILES_DELETE_ALL => __('perm.actions.delete_all'),
                self::REQUESTS_FILES_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.requests_config') => [
                self::REQUESTS_CONFIG_VIEW_SECTION => __('perm.actions.view_section'),
                self::REQUESTS_CONFIG_VIEW_ALL => __('perm.actions.view_all'),
                self::REQUESTS_CONFIG_EDIT_ALL => __('perm.actions.edit_all'),
                self::REQUESTS_CONFIG_EDIT_OWN => __('perm.actions.edit_own'),
                self::REQUESTS_CONFIG_CREATE => __('perm.actions.create'),
                self::REQUESTS_CONFIG_DELETE_ALL => __('perm.actions.delete_all'),
                self::REQUESTS_CONFIG_DELETE_OWN => __('perm.actions.delete_own'),
            ],
            __('perm.sections.global') => [
                self::GLOBAL_SETTINGS_EDIT => __('perm.actions.edit'),
                self::GLOBAL_MANIFEST_EDIT => __('perm.actions.edit'),
            ],
        ];
    }
}
