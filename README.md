# Sama_School
apres avoir colonner le repository voici les instructions a suivre:
composer install
dans le fichier .env vous chanfez le username de votre phpmyadmin et le mot de passe (et jamais le nom De La base)
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
Apres vous procesez comme suit:
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
il va vous demandez une cle vous mettez(sonatel) en (sonatel)
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
vous mettez (sonatel) encore


apres vous creez un fixture  php bin/console doctrine:fixtures:load
le username est sonatelacademy et mot de passe sonatelacademy
Mercii
!!!!