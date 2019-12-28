## Commands

#### Init on server
- copy **.env.example** as **.env**
- change **database credentials** and **APP_URL**
- set writable permissions for **./storage**

```$xslt
composer install
php artisan doctrine:migration:migrate
php artisan db:seed
php artisan storage:link
```

#### Clear Database
```$xslt
php artisan doctrine:migration:rollback
```

#### Fix missing proxies error
```$xslt
php artisan doctrine:generate:proxies
```

#### Generate Migrations
```$xslt
php artisan doctrine:clear:metadata:cache 
php artisan doctrine:migration:diff
```
#### Execute migrations to the latest available version
```$xslt
php artisan doctrine:migration:migrate

```

#### Add missing permissions
```$xslt
php artisan db:seed --class=PermissionsTableUpdater

```

#### Add missing visitors
```$xslt
php artisan db:seed --class=VisitorsTableUpdater

```


#### Send password reset invitations for all users with specified status and role
```$xslt
php artisan auth:reset-password:send-invitations <url_template> <user_status> [<user_role>]
```
- Arguments:
```$xslt
url_template - Template url must have protocol and token placeholder '{token}'. For example, 'https://gochinait.loc/password/reset/{token}'
user_status -   User status. Available statuses: active, pending, canceled, fraudulent, suspended, inactive, deleted, archived
user_role -       User role. Available statuses: super_admin, admin, client, visitor, guest
```
- Example:
```$xslt
php artisan auth:reset-password:send-invitations https://gochinait.loc/password/reset/{token} active client
```


#### Generate QR-codes by specified url template for specified entities and collecting entities data to html file.
```$xslt
php artisan entity:data-collect <entity> <url_template>
```
- Arguments:
```$xslt
entity - Entity class name. Available values: event, company
url_template - Template url for generating QrCode. Template must have protocol and entity id placeholder '{id}'. For example, 'https://gochinait.loc/event/{id}'
```
- Example:
```$xslt
php artisan entity:data-collect event https://gochinait.loc/event/{id}
```


## Generate api documentation and postman json
```$xslt
make apidoc
```
result:
- /public/docs/index.html
- /public/docs/collection.json (Postman)


## RocketChat
Configure `ROCKET_CHAT_API_*` in .env

```php artisan rocketchat:sync:groups``` Sync backend groups with RocketChat.
 
```php artisan rocketchat:sync:users``` Sync website users with RocketChat users.

```php artisan rocketchat:sync:employees``` Sync employees with RocketChat users.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.
