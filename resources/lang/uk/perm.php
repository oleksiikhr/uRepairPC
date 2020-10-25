<?php

declare(strict_types=1);

// TODO Translate to en, ru

return [

    'roles' => [
        'admins' => 'Адміністратори',
        'users' => 'Користувачі',
    ],

    'sections' => [
        'users' => 'Користувачі',
        'profile' => 'Профіль',
        'roles' => 'Ролі',
        'equipments' => 'Обладнання',
        'equipments_files' => 'Обладнання - Файли',
        'equipments_config' => 'Обладнання - Конфігурація',
        'requests' => 'Заявки',
        'requests_comments' => 'Заявки - Коментарі',
        'requests_files' => 'Заявки - Файли',
        'requests_config' => 'Заявки - Конфігурація',
        'jobs' => 'Задачі',
        'global' => 'Глобальні',
    ],

    'actions' => [
        // Basic
        'view' => 'Бачити',
        'view_section' => 'Бачити розділ',
        'view_all' => 'Бачити всі',
        'view_own' => 'Бачити свої',
        'edit' => 'Редагувати',
        'edit_all' => 'Редагувати всі',
        'edit_own' => 'Редагувати свої',
        'create' => 'Створювати',
        'delete_all' => 'Видаляти всі',
        'delete_own' => 'Видаляти свої',

        // Files
        'download_all' => 'Завантажувати всі',
        'download_own' => 'Завантажувати свої',

        // Requests
        'view_assign' => 'Бачити присвоєні',
        'edit_assign' => 'Редагувати присвоєні',
        'delete_assign' => 'Видаляти присвоєні',

        // Jobs
        'retry' => 'Повторити спробу',
        'delete_queue' => 'Видалити чергу',
        'delete_failed_queue' => 'Видалити невдалу чергу',
    ],

];
