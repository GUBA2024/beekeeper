# Golden Hive - Luxury Honey E-commerce

A premium PHP 8 + MySQL e-commerce platform for the **Golden Hive** luxury honey brand with cinematic frontend animation and secure backend APIs.

## Tech Stack
- Frontend: HTML5, CSS3, JavaScript
- Libraries (CDN): GSAP, AOS, Swiper.js, Three.js, Font Awesome
- Backend: PHP 8, PDO, session authentication
- Database: MySQL (schema at `/db/schema.sql`)

## Features
- Luxury responsive UI with honey-inspired visuals, glassmorphism, dark/light mode, AR/EN toggle
- Animated hero, bee motion, counters, honey cursor, floating WhatsApp CTA, loading screen
- Pages: Home, Shop, Product Details, Cart, Checkout, Login/Register, User Dashboard, Admin Dashboard
- REST-style APIs: `/api/auth.php`, `/api/products.php`, `/api/cart.php`, `/api/orders.php`, `/api/admin.php`, `/api/contact.php`
- Security: password hashing, prepared statements, CSRF token validation, output escaping, secure session cookie setup
- Product CRUD entry point via admin dashboard (create with image upload)

## Folder Structure
- `/assets/css`, `/assets/js`, `/assets/images`, `/assets/videos`
- `/includes`, `/admin`, `/api`, `/uploads`, `/db`

## Setup

### Option A — XAMPP (recommended for local development)

1. **Place the project folder inside `htdocs`**
   ```
   C:\xampp\htdocs\beekeeper\
   ```
2. **Start Apache and MySQL** from the XAMPP Control Panel.
3. **Create the database and seed sample data**
   Open `http://localhost/phpmyadmin`, create a database named `beekeeper`, then import `db/schema.sql`.
   Or from the command line:
   ```bash
   mysql -u root -p < db/schema.sql
   ```
4. **Open the site**
   ```
   http://localhost/beekeeper/
   ```
   No extra configuration is needed — the app auto-detects that it is served from `/beekeeper` and adjusts all links and API paths accordingly.

5. **Custom subdirectory name** (optional)
   If you place the folder under a different name (e.g. `htdocs/myhoney`), set the `APP_URL` environment variable so the app knows the correct base URL:
   ```
   APP_URL=http://localhost/myhoney
   ```
   On XAMPP you can set this in `C:\xampp\apache\conf\httpd.conf` (or a `.htaccess` / `SetEnv` directive), or export it before running PHP.

### Option B — PHP built-in server

1. **Create database and seed sample data**
   ```bash
   mysql -u root -p < db/schema.sql
   ```
2. **Set environment variables (optional)**
   - `APP_URL` (default `http://localhost`) — full base URL of the site
   - `DB_HOST` `DB_PORT` `DB_NAME` `DB_USER` `DB_PASS`
3. **Serve with PHP**
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000/`

## Sample Credentials
- Admin: `admin@goldenhive.local`
- Customer: `sara@example.com`
- Password for both seed users: `password123`

## Notes
- Uploaded product images are stored in `/uploads`.
- Contact form stores notifications in the `notifications` table.
- Replace sample media URLs and phone number with production values.
