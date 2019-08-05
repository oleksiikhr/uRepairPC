<?php

return [

//    TODO Translate to en, ru

    /*
     * Middleware.
     */
    'middleware' => [
        'no_permission' => 'Немає прав',
        'no_auth' => 'Необхідно увійти в систему',
    ],

    /*
     * Stat Controllers.
     */
    'settings' => [
        'global' => 'Налаштування оновлені',
        'manifest' => 'Маніфест оновлений',
    ],

    /*
     * AuthController.
     */
    'auth' => [
        'login_error' => 'Дані невірні',
        'login_success' => 'Ви увійшли в систему',
        'token_invalid' => 'Токен не дійсний',
        'token_refresh' => 'Токен оновлено',
        'logout' => 'Ви вийшли з системи',
    ],

    /*
     * RequestController.
     */
    'requests' => [
        'show' => 'Заявка отримана',
        'store' => 'Заявка створена',
        'update' => 'Заявка оновлена',
        'destroy' => 'Заявка видалена',
    ],

    /*
     * RequestCommentController.
     */
    'request_comments' => [
        'index' => 'Коментарії отримані',
        'show' => 'Коментарій отриман',
        'store' => 'Коментарій створений',
        'update' => 'Коментарій оновлений',
        'destroy' => 'Коментарій видалений',
    ],

    /*
     * UserController.
     */
    'users' => [
        'show' => 'Користувач отриман',
        'store' => 'Користувач створений',
        'update' => 'Користувач оновлений',
        'destroy' => 'Користувач видалений',
        'self_destroy_error' => 'Неможливо видалити самого себе',
        'email_changed' => 'E-mail змінений',
        'password_changed' => 'Пароль змінений',
        'roles_changed' => 'Ролі змінені',
        'password_email_changed' => 'Пароль змінений та відправлений на почту',
    ],

    /*
     * RoleController.
     */
    'roles' => [
        'show' => 'Роль отримана',
        'store' => 'Роль створена',
        'update' => 'Роль оновлена',
        'destroy' => 'Роль видалена',
        'update_permissions' => 'Доступи оновлені',
    ],

    /*
     * EquipmentController.
     */
    'equipments' => [
        'show' => 'Обладнання отримано',
        'store' => 'Обладнання створено',
        'update' => 'Обладнання оновлено',
        'destroy' => 'Обладнання видалено',
    ],

    /*
     * EquipmentManufacturerController.
     */
    'equipment_manufacturers' => [
        'show' => 'Виробник обладнання отриманий',
        'store' => 'Виробник обладнання створений',
        'update' => 'Виробник обладнання оновлений',
        'destroy' => 'Виробник обладнання видалено',
    ],

    /*
     * EquipmentModelController.
     */
    'equipment_model' => [
        'show' => 'Модель обладнання отримано',
        'store' => 'Модель обладнання створено',
        'update' => 'Модель обладнання оновлено',
        'destroy' => 'Модель обладнання видалено',
    ],

    /*
     * EquipmentTypeController.
     */
    'equipment_type' => [
        'show' => 'Тип обладнання отримано',
        'store' => 'Тип обладнання створено',
        'update' => 'Тип обладнання оновлено',
        'destroy' => 'Тип обладнання видалено',
    ],

    /*
     * RequestStatusController.
     */
    'request_status' => [
        'show' => 'Статус заявки отримано',
        'store' => 'Статус заявки створено',
        'update' => 'Статус заявки оновлено',
        'destroy' => 'Статус заявки видалено',
        'update_default' => 'Необхідно мати хоча б один статус за замовчуванням',
        'destroy_default' => 'Необхідно мати хоча б один статус за замовчуванням',
    ],

    /*
     * RequestPriorityController.
     */
    'request_priority' => [
        'show' => 'Пріорітет заявки отримано',
        'store' => 'Пріорітет заявки створено',
        'update' => 'Пріорітет заявки оновлено',
        'destroy' => 'Пріорітет заявки видалено',
        'update_default' => 'Необхідно мати хоча б один пріорітет за замовчуванням',
        'destroy_default' => 'Необхідно мати хоча б один пріорітет за замовчуванням',
    ],

    /*
     * RequestTypeController.
     */
    'request_type' => [
        'show' => 'Тип заявки отримано',
        'store' => 'Тип заявки створено',
        'update' => 'Тип заявки оновлено',
        'destroy' => 'Тип заявки видалено',
        'update_default' => 'Необхідно мати хоча б один тип за замовчуванням',
        'destroy_default' => 'Необхідно мати хоча б один тип за замовчуванням',
    ],

    /*
     * Working with files.
     */
    'files' => [
        'files_not_deleted' => 'Виникла помилка під час видалення файлів',
        'file_not_deleted' => 'Виникла помилка під час видалення файлу',
        'file_not_saved' => 'Файл не зберігся',
        'file_not_found' => 'Файл не знайдений',
        'file_destroyed' => 'Файл видалений',
        'file_updated' => 'Файл оновлено',
        'file_saved' => 'Файл збережено',
        'files_get' => 'Файли отримані',
        'upload_success' => 'Файли збережені',
        'upload_error' => 'З\'явилася помилка при завантаженні деяких файлів',
    ],

    /*
     * Database operations.
     */
    'database' => [
        'save_error' => 'Виникла помилка при збереженні',
        'destroy_error' => 'Виникла помилка при видаленні',
    ],

];
