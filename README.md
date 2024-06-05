# Install

Create a folder for your project and move to it

### Clone (or fork, or download)

`git clone https://github.com/HEIG-COMEM/HEIG-VD_ProjArt_Sentiers-Culturels.git`

### Install dependencies

```
composer install
npm ci
```

### Create a .env file

Copy the example file and edit it to your needs

`cp .env.example .env`

### Routing API

Create an account on [Openroute Service](https://openrouteservice.org/), generate an API token and put it in the `.env` file

### Generate a key

`php artisan key:generate`

## Run development environment

Create link to storage directory `php artisan storage:link`

Create a database in your mysql DB with the same name as defined in the `.env` file

Then run

`php artisan migrate:fresh --seed`

To create tables and populate them

Then in two separate terminals:

`php artisan serve`
`npm run dev`

# Deploy

1. connect to the server using SSH
2. go to the site directory by executing the command `cd path-to-site`.
3. clone the project from github `git clone https://github.com/HEIG-COMEM/HEIG-VD_ProjArt_Sentiers-Culturels.git`
4. Install dependencies with `composer install` and `npm ci`.
5. Create .env file from model `cp .env.example .env`.
6. Create an account on [Openroute Service](https://openrouteservice.org/), generate an API token and put it in the `.env` file
7. modify the `.env` file in the hosting root to update the connection parameters for the new database:

```env
DB_CONNECTION=mysql
DB_HOST=xxxx.myd.infomaniak.com
DB_PORT=3306
DB_DATABASE= the name of the MySQL database (xxxx_newbdd)
DB_USERNAME= the MySQL user with rights to this database
DB_PASSWORD= its password
```

8. Create the application key

    `php artisan key:generate`

9. Perform migrations and database seeding

    `php artisan migrate:fresh --seed`

10. Create link to storage directory

    `php artisan storage:link`

11. (Optional) Optimization
    1. `composer install --optimize-autoloader --no-dev`
    2. `php artisan config:cache`
    3. `php artisan route:cache`
    4. `php artisan view:cache`

## Maintenance

1. Pause the site

    `php artisan down`

2. Update the site

    ```
    git pull
    composer install
    npm ci
    php artisan migrate
    ```

3. Restart queue (optional)

    `php artisan queue:restart`

4. Clear cache (optional)

    `php artisan cache:clear`

5. Set back up the site

    `php artisan up`
