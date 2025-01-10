# DontForgetMe

Pour activer la barre de débug : modifier le `.env` et mettre `APP_DEBUG=true` puis utiliser la commande `php artisan config:cache`. Mettre à `APP_DEBUG=false` pour désactiver
Pour l'installer : `composer require barryvdh/laravel-debugbar --dev --ignore-platform-req=ext-fileinfo` puis `php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider" --tag="config"` et enfin `php artisan config:cache` pour remttre à jour le cache


Liens : 
https://codepen.io/santanup789/pen/bGpVEEr?editors=1111