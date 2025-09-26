# Установка Laravel проекта на Ubuntu

sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server -y

Создаем базу и юзера:

sudo mysql -e "CREATE DATABASE laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'laraveluser'@'localhost' IDENTIFIED BY 'password123';"
sudo mysql -e "GRANT ALL PRIVILEGES ON laravel.* TO 'laraveluser'@'localhost'; FLUSH PRIVILEGES;"

Ставим PHP 8.3 и нужные модули:


sudo apt install software-properties-common ca-certificates lsb-release apt-transport-https -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.3 libapache2-mod-php8.3 php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath unzip -y

Ставим composer:

curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

Клонируем проект и ставим зависимости:


cd /var/www/html
git clone https://github.com/Treeio01/brave brave
cd brave
composer install
cp .env.example .env
php artisan key:generate

Выдаём права:

sudo chown -R www-data:www-data /var/www/html/brave
sudo chmod -R 775 /var/www/html/brave/storage
sudo chmod -R 775 /var/www/html/brave/bootstrap/cache
php artisan storage:link

Конфиг апачи под домен:


sudo tee /etc/apache2/sites-available/brave.conf > /dev/null <<'EOF'
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/brave/public
    <Directory /var/www/html/brave/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

sudo a2enmod rewrite
sudo a2ensite brave.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2


НАСТРОЙКА ТЕЛЕГРАММ БОТА.

cd ../var/www/html/brave/
php artisan telegram:setup {bot_token} {chat_id}