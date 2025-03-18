# Install dependencies

```bash 
docker compose run --rm app composer create-project laravel/laravel=9 .
docker compose run --rm app composer require nwidart/laravel-modules="9.*"
docker compose run --rm app composer require crunzphp/crunz
docker compose run --rm app composer require predis/predis
```


# Public vendor
```bash
docker compose run --rm app php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```

# Make UI Scheduler module
```bash
docker-compose run --rm app php artisan module:make UiScheduler
```