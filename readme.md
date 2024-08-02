## Requires
PHP ^8.1

Extensions: gd, intl, mbstring

## Build Overview
* TALL Stack (Tailwind Alpine Laravel Livewire)
* Auth Jetstream
* Roles & Permissions (spatie/laravel-permission)
* Blade-UI-Kit
* Icons (blade-icons, blade-vaadin-icons)
* Spatie Media Library (+ intervention/image)

## Commands
### Create User
```bash 
php artisan app:user-create {name} {email} {password} {roles-csv}

// Example
php artisan app:user-create Chris chris@example.com Exampl398!! developer,admin
```

## Roles
* developer
* admin
* staff

## Languages
Supported languages: EN, ES

Configure via .env APP_LOCALE

## License

(CC BY) Creative Commons 2024 Christofer De David

https://creativecommons.org/share-your-work/cclicenses/

chris&middot;dedavid [at] gmail&middot;com
