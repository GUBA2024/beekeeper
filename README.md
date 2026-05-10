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
1. **Create database and seed sample data**
   ```bash
   mysql -u root -p < db/schema.sql
   ```
2. **Set environment variables (optional)**
   - `APP_URL` (default `http://localhost:8000`)
   - `DB_HOST` `DB_PORT` `DB_NAME` `DB_USER` `DB_PASS`
3. **Serve with PHP**
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000/index.php`

## Sample Credentials
- Admin: `admin@goldenhive.local`
- Customer: `sara@example.com`
- Password for both seed users: `password123`

## Notes
- Uploaded product images are stored in `/uploads`.
- Contact form stores notifications in the `notifications` table.
- Replace sample media URLs and phone number with production values.
