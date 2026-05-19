# 🚀 ventureLink - Fintech Startup Investment Platform

Welcome to **ventureLink**, a modern, premium, MongoDB-backed fintech platform designed to connect ambitious **Founders** with visionary **Investors**. The application is built using **Laravel 12**, **MongoDB**, **Tailwind CSS v4**, and **Vite**.

This README provides complete instructions to set up, run, and explore the application.

---

## 🐳 Easy Docker Setup (Highly Recommended)

Using Docker is the easiest way to run the project. **You do not need to install PHP, Node.js, Composer, MongoDB, or the PHP MongoDB extension manually.** Everything is built-in and configured automatically!

### 1. Prerequisites
Ensure you have **Docker Desktop** installed and running on your machine:
* [Download Docker Desktop](https://www.docker.com/products/docker-desktop/)

### 2. Steps to Run
Open your terminal (PowerShell, Bash, or Command Prompt) and run:

```bash
# Clone the repository
git clone https://github.com/Dheeraj-Kapuganti/ventureLink.git
cd ventureLink

# Run Docker Compose to build and start the containers
docker compose up --build
```

**That's it!** The Docker container will automatically:
1. Create your `.env` file and link it to the container's MongoDB database.
2. Install all PHP Composer dependencies.
3. Install all Node.js/NPM packages.
4. Generate the application encryption key.
5. Setup and seed the MongoDB database with default testing accounts.
6. Launch the Laravel development server and Vite asset compiler simultaneously.

Once you see the servers starting, open your browser to:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 💻 Manual Local Setup (Alternative)

If you prefer not to use Docker, you can set up the environment manually on your system.

### Prerequisites
1. **PHP (>= 8.2)**
   - Verify with: `php -v`
2. **Composer (PHP Dependency Manager)**
   - Verify with: `composer -V`
3. **Node.js (>= 18.0) & npm**
   - Verify with: `node -v` and `npm -v`
4. **MongoDB Community Server**
   - Must be installed and running locally on `127.0.0.1:27017`.
5. **MongoDB PHP Extension (`mongodb`)**
   - Essential for Laravel to communicate with MongoDB.
   - **Windows Installation Guide:**
     - Download the DLL from [PECL](https://pecl.php.net/package/mongodb) matching your PHP version (Thread Safe/Non-Thread Safe) and architecture (x64/x86).
     - Copy `php_mongodb.dll` to your PHP `ext` directory.
     - Add `extension=mongodb` to your active `php.ini` file.
   - **macOS Installation Guide:** Run `pecl install mongodb`.
   - **Linux Installation Guide:** Run `sudo apt-get install php-mongodb`.

### Setup Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Dheeraj-Kapuganti/ventureLink.git
   cd ventureLink
   ```

2. **Configure Environment Variables**
   Copy the `.env.example` file to create a `.env` file:
   - **Windows:** `copy .env.example .env`
   - **macOS / Linux:** `cp .env.example .env`

3. **Run the Auto-Setup Script**
   ```bash
   composer setup
   ```
   *(Alternative manual commands: `composer install`, `php artisan key:generate`, `npm install`, and `npm run build`)*

4. **Seed the Database**
   To populate testing accounts and startup records:
   ```bash
   php artisan db:seed
   php artisan db:seed --class=TempSeeder
   ```

5. **Start Dev Servers**
   ```bash
   composer dev
   ```
   Access the app at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Default Login Credentials

Use the following seeded credentials to explore the different dashboards:

### 1. 🛡️ System Admin Dashboard
*Manage startups, toggle/block users, view global analytics and investments.*
- **URL:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin) or navigate to Login page and log in.
- **Email:** `admin@example.com`
- **Password:** `admin123`

### 2. 🚀 Founder Dashboard
*Create and manage startups, view funding progress, stage, deadlines, and active investment details.*
- **Email:** `founder@test.com`
- **Password:** `password`

### 3. 💼 Investor Dashboard
*Create a new Investor account via the `/register` page to browse active startups, bookmark favorites, post reviews/star ratings, make mock investments, and track your investment portfolio.*
- **Register Link:** [http://127.0.0.1:8000/register](http://127.0.0.1:8000/register) (Select **Investor** role)

---

## 🌟 Key Application Features

- **Dynamic Role-Based Redirection**: A unified login system that seamlessly routes Admins, Founders, and Investors to their customized panels.
- **Comprehensive Founder Workflow**: Seamlessly register your startup, submit it for admin approval, edit details, and track real-time funding progress.
- **Investor Suite**: Detailed search/discovery of approved startups, high-end investment simulations, custom bookmarking, and investment portfolio tracking.
- **Robust Community Interaction**: Interactive nested comments threads and star-rating review systems on every startup profile.
- **Smart Notification Hub**: In-app notifications alerting users about new investments, comments, goal completions, and admin approvals.
- **Advanced Admin Analytics**: Complete user management, platform audit controls, and real-time dashboard analytics displaying beautiful, responsive charts, platform growth, and investment volume.
