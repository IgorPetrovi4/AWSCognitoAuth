### Local Install

#### Create `.env.local`
```bash
cat << EOF > .env.local
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=aws_cognito_db
MYSQL_PORT=3317
NGINX_PORT=8017
LOCAL_USER=1000:1000
TIMEZONE=Europe/Kiev
DATABASE_URL=mysql://root:root@mysql_db/aws_cognito_db
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ddd64968f02152a0723018d241db1296d0537b8fa4867d680a873c5f9985b8f8
NBU_API_BASE_URL=https://bank.gov.ua/
APP_ENV=dev
APP_SECRET=65976e6c0450aa5cf7ddba12c093a0c3
#aws
AWS_KEY=your_key
AWS_SECRET=your_secret
COGNITO_CLIENT_ID=your_client_id
COGNITO_USER_POOL_ID=your_user_pool_id
EOF
```

#### Install the project
```bash
docker compose -f docker-compose-local.yaml --env-file ./.env.local build --no-cache              # Build containers from images
```
```bash
docker compose -f docker-compose-local.yaml --env-file ./.env.local up -d                         # Run containers       
```              
```bash
docker exec -it testcrudaws-php-1 composer install --optimize-autoloader                             # Install dependencies
```
```bash
docker exec -it testcrudaws-php-1 php bin/console doctrine:database:create --if-not-exists           # Create database
```
```bash
docker exec -it testcrudaws-php-1 php bin/console doctrine:migrations:migrate -n                     # Run migrations
```
```bash
docker exec -it testcrudaws-php-1 php bin/console doctrine:fixtures:load -n                           # Load fixtures
```

#### Open in browser
http://localhost:8017/api/doc                                                               







