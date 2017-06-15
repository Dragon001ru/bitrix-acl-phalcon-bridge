Мост для 1С-Битрикс и Phalcon ACL 
=================================

Позволяет интегрировать Phalcon ACL в 1С-Битрикс.

 * Добавлена возможность настраивать роли, ресурсы, операции и права доступа через конфигурационный
 файл Битрикса (.settings.php)
 * Добавлен "Комбинитор" для разрешения конфликтов прав доступа, возникающих при наследовании ролей.
 
Оглавление
----------
* [Словарь](#Словарь)
* [Исключения](#Исключения)
* [Использование](#Использование)
* [Конфигурационный файл](#Конфигурационный-файл)

 
Словарь 
-------

 **Роль** - понятие "Роли" совпадает с понятем "Группа пользователей" в 1С-Битрикс. Один пользователь может
 иметь несколько ролей. **Символьный код Группы пользователей = символьному коду Роли.**
 
 **Ресурс** - логически выделенный набор функциональных возможностей системы (прим. Лента комментариев), содержащий 
 в себе набор, независищих между собой **Операций**.
 
 **Операция** - действие, которое можно совершить над **Ресурсом**.

Исключения
----------

Все исключения наследуются от базового ``UW\Acl\Exceptions\AclException``

Исключения, возникающие в процессе конфигурирования ACL:

* UserRolesOperationException - ошибка при получении ролей пользователя
* BadConfigurationException - ошибка валидации данных конфигурации ACL
* PhalconAclException - ошибка создания конфигурации ACL на стороне Phalcon

Исключения, возникающие при попытке проверки доступа:

* AclNotInitializedException - ошибка получения объекта из хранилища, объект не был предварительно создан и настроен.
* CombinatorStrategyNotFoundException - ошибка поиска стратегии для Комбинатора, указанная в конфиге стратегия не реализована.

Использование
-------------

Для подключения функционала необходимо зарегистрировать метод ``UW\Acl\Helper::configureAcl()`` в качестве 
обработчика одного из системных событий битрикса, возникающих при загрузке старинице. На пример на 
событие ``OnProlog``:

```php
AddEventHandler('main', "OnProlog", ['UW\Acl\Helper', 'configureAcl']);
```
После регистрации события, объект ACL доступен в любом компоненте, модуле, файле и т.д.
 
Доступ к объекту ACL осуществляется через специальное хранилище - ``UW\Acl\Storage``
 
Пример проверки права доступа к операции "Редактирование" ресура "Сообщение":

```php
if(\UW\Acl\Storage::get()->isAllowed('message', 'edit')){
    //выполнение операции
}
```
 
Конфигурационный файл
---------------------
В качестве конфигурационного файла используется `.settings.php` или `.settings_extra.php`

В конфигурационном файле описывается два ключа:
1. **acl-settings** - настройки окружения для ACL
2. **acl** - конфигурация объекта ACL (роли, права, ресурсы, операции и т.д.)

Ключи для **acl-settings**

| Ключ | Описание  |
|---|---|
| configuration-cache-time  | Время жизни кеша для конфигурации ACL (сек.) |
| configuration-cache-folder  | Каталог хранения кеша (относительно /bitrix/cache/) |


Ключи для **acl**

| Ключ | Описание  | Допустипые значения
|---|---|---|
| guestRoleCode  | Код роли для неавторизованных пользователей. | Строка. Если параметр не установлен, то по умолчению задается код - "**everyone**" |
| defaultAction  | Действие по умолчанию, если не задано правило. | **deny/allow** - запретить/разрешить доступ |
| combineStrategy  | Стратегия для разрешения конфликтов, возникающих при наследовании прав | And/Or/Xor |
| availableRoles  | Доступные роли | Массив кодов ролей ``['role1', 'role2']``. Допускается наследование ролей ``['role1', ['role2', 'role1']]``|
| availableResources  | Доступные ресурсы | Массив ресурсов ``['resourceName' => ['operation1', 'operation2']]`` |
| rights  | Отношения между ролями, ресураси и их операциями | Массив прав ``['role' => ['operation1', 'operation2']]`` |


Пример конфигурационного файла

````
'acl-settings' => [
    'value' => [
        'configuration-cache-time' => 60,
        'configuration-cache-folder' => 'acl-config'
    ]
],
'acl' => [
    'value' => [
        'defaultAction' => 'deny',
        'combineStrategy' => 'Or',
        'availableRoles' => ['ownGroup', 'administrators'],
        'availableResources' => [
            'request' => [
                'create',
                'edit',
                'delete',
                'changeStatus'
            ],
            'message' => [
                'create',
                'read',
                'edit'
            ]
        ],
        'rights' => [
            'administrators' => [
                [
                    'access'   => 'allow',
                    'resource' => [
                        'name'    => 'request',
                        'actions' => [
                            'edit',
                            'delete',

                        ]
                    ]
                ]
            ],
            'ownGroup' => [
                [
                    'access'   => 'deny',
                    'resource' => [
                        'name'    => 'message',
                        'actions' => '*'
                    ]
                ]
            ]
        ]
    ]
]
````



