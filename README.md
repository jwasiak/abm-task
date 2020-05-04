# Wymagania
* PHP 7.*
* composer
* npm
* serwer postgresql

# Instalacja aplikacji
* cd abm-task;
* composer install
* npm install
* npm build
* webpack (nie wymagane - w folderze public/js jest już utworzony plik js)

# konfiguracja bazy danych
* w pliku sql/db.sql znajduje się plik do utworzenia bazy
* konfiguracja aplikacji config/settings.php

# Uruchomienie aplikacji
* cd abm-task;
* php -S 0.0.0.0:7200 -t public/
* http://localhost:7200
