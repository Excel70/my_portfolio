# Excel Portfolio CMS

A database-driven Laravel portfolio for Aderinlewo Excel Olamilekan. The public site uses a deliberately restrained deep-slate and navy visual system; the protected CMS lets its owner manage content without editing templates.

## Included

- Responsive public pages for Home, About, Skills, Services, Projects, Graphic Design, Web3, Writing, and Contact.
- Database-backed projects, technologies, project galleries, skills, services, designs, articles, messages, and profile settings.
- A private, server-authorized admin area with content CRUD, image validation/upload, search, pagination, message status management, and editable profile/contact settings.
- Public JSON endpoints at `/api/projects`, `/api/projects/{slug}`, `/api/skills`, `/api/services`, and `/api/articles`.
- Mobile navigation, gallery lightbox, accessible focus states, CSRF protection, rate-limited login/contact endpoints, Eloquent queries, and server-side validation.

No personal social links, project metrics, clients, outcomes, published articles, or administrator credentials are invented. The seeded projects use only the supplied descriptions. Create your own private administrator after setup.

## Requirements

- PHP 8.2 or newer with PDO MySQL, mbstring, OpenSSL, fileinfo, XML, and zip extensions enabled
- Composer 2
- MySQL 8+ or MariaDB 10.6+
- Apache/XAMPP or another PHP-compatible local server

## XAMPP setup

1. Install XAMPP, then start **Apache** and **MySQL** from its control panel.
2. In phpMyAdmin, create a database named `excel_portfolio` with `utf8mb4_unicode_ci` collation.
3. Open a terminal in this project and install dependencies:

   ```bash
   composer install
   ```

4. Copy the environment template and configure your MySQL values:

   ```bash
   copy .env.example .env
   ```

   Set `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` to match your local MySQL account. Do not commit `.env`.

5. Generate the application key, build the schema/seed content, and make public uploads available:

   ```bash
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

6. Create a private administrator interactively. This command never supplies a default password:

   ```bash
   php artisan portfolio:create-admin
   ```

7. Start Laravel:

   ```bash
   php artisan serve
   ```

   Open `http://localhost:8000`, then use `/admin/login` to sign in.

For Apache virtual hosting, point the site document root to this project's `public/` directory, never to the project root.

## Content workflow

- **Profile & settings** controls the public name, professional title, about copy, contact email, and optional social URLs.
- **Projects** can include a thumbnail, up to 12 gallery uploads at a time, technologies, links, feature status, and publishing state.
- **Articles** are public only when they are set to `published`; the CMS assigns a publication timestamp if one is not given.
- **Messages** are always stored in the database. Mail delivery is intentionally not claimed unless you configure a real mail provider in `.env`.

## Security notes

The administrator area requires both an authenticated session and the server-side `is_admin` authorization gate. Registration is not exposed publicly. Laravel hashes passwords, includes CSRF tokens on state-changing forms, regenerates sessions at login, rate-limits the login and contact endpoints, validates uploads, and keeps secrets in `.env`.

## Production deployment

Use a PHP-compatible server, Composer, MySQL, HTTPS, and a document root pointed at `public/`. Set `APP_ENV=production`, `APP_DEBUG=false`, a unique `APP_KEY`, correct database credentials, and a real `APP_URL`. Ensure `storage/` and `bootstrap/cache/` are writable by the web server. Run:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Back up the MySQL database and `storage/app/public` before updating or deploying new versions.
