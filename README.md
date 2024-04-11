# Server config

_ Install necessary libs

```
apt update && apt install -y mysql-server composer nginx php8.2 php8.2-curl php8.2-bcmath php8.2-ctype php8.2-mbstring php8.2-pdo php8.2-tokenizer php8.2-xml php8.2-zip php8.2-fileinfo php8.2-gd php8.2-mysql php8.2-fpm libapache2-mod-php
```

_ Create new SSH key

```
ssh-keygen -t ed25519 -C "your_email@example.com"
```

_ clone project to `/var/www/service-place-admin`

_ Install libs

```
composer update
```

_ Run command:

```
sudo chmod -R 777 /var/www/service-place-admin
```

_ Edit nginx config at `/etc/nginx/sites-available/default`

```
server {
        listen 80;
        listen [::]:80;
        server_name sieuthidichvu.vn www.sieuthidichvu.vn;
        root /var/www/service-place-admin;

        index index.php index.html index.htm;

        charset utf-8;

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
                fastcgi_index  index.php;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass    unix:/var/run/php/php8.2-fpm.sock;
                fastcgi_param   PATH_INFO       $fastcgi_path_info;
                fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include         fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
}
```

_ Add SSL:

```
apt install certbot python3-certbot-nginx
sudo certbot --nginx -d sieuthidichvu.vn -d www.sieuthidichvu.vn
```

_ Restart Nginx:

```
service nginx restart
```

_ Config mysql:

```

sudo mysql -u root -p
CREATE DATABASE db_name;
CREATE USER 'db_user'@'localhost' IDENTIFIED BY 'db_password';
GRANT ALL PRIVILEGES ON db_name . * TO 'db_user'@'localhost';

```

_ Enable firewall

```
ufw enable
ufw allow 'Nginx Full'
```

_ If get CORS error, add this to nginx config:

```
add_header Access-Control-Allow-Origin $http_origin;
```
