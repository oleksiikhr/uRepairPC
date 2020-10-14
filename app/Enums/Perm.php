<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;

abstract class Perm
{
    // Profile
    public const PROFILE_EDIT = 'profile.edit';

    // Users
    public const USERS_VIEW_SECTION = 'users.view_section';
    public const USERS_VIEW_ALL = 'users.view_all';
    public const USERS_EDIT_ALL = 'users.edit_all';
    public const USERS_CREATE = 'users.create';
    public const USERS_DELETE_ALL = 'users.delete_all';

    // Roles
    public const ROLES_VIEW_SECTION = 'roles.view_section';
    public const ROLES_VIEW_ALL = 'roles.view_all';
    public const ROLES_EDIT_ALL = 'roles.edit_all';

    // Equipments
    public const EQUIPMENTS_VIEW_SECTION = 'equipments.view_section';
    public const EQUIPMENTS_VIEW_ALL = 'equipments.view_all';
    public const EQUIPMENTS_VIEW_OWN = 'equipments.view_own';
    public const EQUIPMENTS_EDIT_ALL = 'equipments.edit_all';
    public const EQUIPMENTS_EDIT_OWN = 'equipments.edit_own';
    public const EQUIPMENTS_CREATE = 'equipments.create';
    public const EQUIPMENTS_DELETE_ALL = 'equipments.delete_all';
    public const EQUIPMENTS_DELETE_OWN = 'equipments.delete_own';

    // Equipment Files
    public const EQUIPMENTS_FILES_VIEW_ALL = 'equipments.files.view_all';
    public const EQUIPMENTS_FILES_VIEW_OWN = 'equipments.files.view_own';
    public const EQUIPMENTS_FILES_DOWNLOAD_ALL = 'equipments.files.download_all';
    public const EQUIPMENTS_FILES_DOWNLOAD_OWN = 'equipments.files.download_own';
    public const EQUIPMENTS_FILES_EDIT_ALL = 'equipments.files.edit_all';
    public const EQUIPMENTS_FILES_EDIT_OWN = 'equipments.files.edit_own';
    public const EQUIPMENTS_FILES_CREATE = 'equipments.files.create';
    public const EQUIPMENTS_FILES_DELETE_ALL = 'equipments.files.delete_all';
    public const EQUIPMENTS_FILES_DELETE_OWN = 'equipments.files.delete_own';

    // Equipments Config
    public const EQUIPMENTS_CONFIG_VIEW_SECTION = 'equipments.config.view_section';
    public const EQUIPMENTS_CONFIG_VIEW_ALL = 'equipments.config.view_all';
    public const EQUIPMENTS_CONFIG_EDIT_ALL = 'equipments.config.edit_all';
    public const EQUIPMENTS_CONFIG_EDIT_OWN = 'equipments.config.edit_own';
    public const EQUIPMENTS_CONFIG_CREATE = 'equipments.config.create';
    public const EQUIPMENTS_CONFIG_DELETE_ALL = 'equipments.config.delete_all';
    public const EQUIPMENTS_CONFIG_DELETE_OWN = 'equipments.config.delete_own';

    // Requests
    public const REQUESTS_VIEW_SECTION = 'requests.view_section';
    public const REQUESTS_VIEW_ALL = 'requests.view_all';
    public const REQUESTS_VIEW_OWN = 'requests.view_own';
    public const REQUESTS_VIEW_ASSIGN = 'requests.view_assign';
    public const REQUESTS_EDIT_ALL = 'requests.edit_all';
    public const REQUESTS_EDIT_OWN = 'requests.edit_own';
    public const REQUESTS_EDIT_ASSIGN = 'requests.edit_assign';
    public const REQUESTS_CREATE = 'requests.create';
    public const REQUESTS_DELETE_ALL = 'requests.delete_all';
    public const REQUESTS_DELETE_OWN = 'requests.delete_own';
    public const REQUESTS_DELETE_ASSIGN = 'requests.delete_assign';

    // Requests Comments
    public const REQUESTS_COMMENTS_VIEW_ALL = 'requests.comments.view_all';
    public const REQUESTS_COMMENTS_EDIT_ALL = 'requests.comments.edit_all';
    public const REQUESTS_COMMENTS_EDIT_OWN = 'requests.comments.edit_own';
    public const REQUESTS_COMMENTS_CREATE = 'requests.comments.create';
    public const REQUESTS_COMMENTS_DELETE_ALL = 'requests.comments.delete_all';
    public const REQUESTS_COMMENTS_DELETE_OWN = 'requests.comments.delete_own';

    // Requests Files
    public const REQUESTS_FILES_VIEW_ALL = 'requests.files.view_all';
    public const REQUESTS_FILES_VIEW_OWN = 'requests.files.view_own';
    public const REQUESTS_FILES_DOWNLOAD_ALL = 'requests.files.download_all';
    public const REQUESTS_FILES_DOWNLOAD_OWN = 'requests.files.download_own';
    public const REQUESTS_FILES_EDIT_ALL = 'requests.files.edit_all';
    public const REQUESTS_FILES_EDIT_OWN = 'requests.files.edit_own';
    public const REQUESTS_FILES_CREATE = 'requests.files.create';
    public const REQUESTS_FILES_DELETE_ALL = 'requests.files.delete_all';
    public const REQUESTS_FILES_DELETE_OWN = 'requests.files.delete_own';

    // Requests Config
    public const REQUESTS_CONFIG_VIEW_SECTION = 'requests.config.view_section';
    public const REQUESTS_CONFIG_VIEW_ALL = 'requests.config.view_all';
    public const REQUESTS_CONFIG_EDIT_ALL = 'requests.config.edit_all';
    public const REQUESTS_CONFIG_EDIT_OWN = 'requests.config.edit_own';
    public const REQUESTS_CONFIG_CREATE = 'requests.config.create';
    public const REQUESTS_CONFIG_DELETE_ALL = 'requests.config.delete_all';
    public const REQUESTS_CONFIG_DELETE_OWN = 'requests.config.delete_own';

    // Jobs
    public const JOBS_VIEW_SECTION = 'jobs.view_section';
    public const JOBS_VIEW_ALL = 'jobs.view_all';
    public const JOBS_RETRY = 'jobs.retry';
    public const JOBS_DELETE_ALL_QUEUE = 'jobs.delete_all_queue';
    public const JOBS_DELETE_FAILED_QUEUE = 'jobs.delete_failed_queue';

    // Global
    public const GLOBAL_SETTINGS_EDIT = 'global.settings.edit';
    public const GLOBAL_MANIFEST_EDIT = 'global.manifest.edit';

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
     * @return array
     */
    public static function getStructure(): array
    {
        return [
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
            __('perm.sections.jobs') => [
                self::JOBS_VIEW_SECTION => __('perm.actions.view_section'),
                self::JOBS_VIEW_ALL => __('perm.actions.view_all'),
                self::JOBS_RETRY => __('perm.actions.retry'),
                self::JOBS_DELETE_ALL_QUEUE => __('perm.actions.delete_all_queue'),
                self::JOBS_DELETE_FAILED_QUEUE => __('perm.actions.delete_failed_queue'),
            ],
            __('perm.sections.global') => [
                self::GLOBAL_SETTINGS_EDIT => __('perm.actions.edit'),
                self::GLOBAL_MANIFEST_EDIT => __('perm.actions.edit'),
            ],
        ];
    }
}
