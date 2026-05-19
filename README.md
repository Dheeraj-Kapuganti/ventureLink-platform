# 🚀 ventureLink - Fintech Startup Investment Platform

Welcome to **ventureLink**, a modern, premium, MongoDB-backed fintech platform designed to connect ambitious **Founders** with visionary **Investors**. The application is built using **Laravel 12**, **MongoDB**, **Tailwind CSS v4**, and **Vite**.

This README provides complete, step-by-step instructions to set up, run, and explore the application.

---

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed on your local machine:

1. **PHP (>= 8.2)**
   - Verify with: `php -v`
2. **Composer (PHP Dependency Manager)**
   - Verify with: `composer -V`
3. **Node.js (>= 18.0) & npm**
   - Verify with: `node -v` and `npm -v`
4. **MongoDB Community Server**
   - Must be installed and running locally on `127.0.0.1:27017` (or you can use a remote MongoDB Atlas URI).
   - Verify MongoDB is running (e.g., using MongoDB Compass or terminal command).
5. **MongoDB PHP Extension (`mongodb`)**
   - Essential for Laravel to communicate with MongoDB.
   - **Installation Guide:**
     - **Windows:** Download the DLL from [PECL](https://pecl.php.net/package/mongodb) matching your PHP version (Thread Safe/Non-Thread Safe) and architecture (x64/x86). Add `extension=mongodb` to your `php.ini`.
     - **macOS (via Homebrew):** Run `pecl install mongodb` and ensure your active PHP configuration registers the extension.
     - **Linux (Ubuntu/Debian):** Run `sudo apt-get install php-mongodb` or `sudo pecl install mongodb`.

---

## ⚡ Quick Start Setup

To run this project locally, your friend should open a terminal (PowerShell, Bash, or Command Prompt) and follow these exact steps:

### 1. Clone the Repository
```bash
git clone https://github.com/Dheeraj-Kapuganti/ventureLink.git
cd ventureLink
```

### 2. Configure Environment Variables
Copy the `.env.example` file to create a `.env` file:
- **Windows (Command Prompt / PowerShell):**
  ```powershell
  copy .env.example .env
  ```
- **macOS / Linux:**
  ```bash
  cp .env.example .env
  ```

*Note: The database configuration inside `.env` will default to a local MongoDB instance. If your database runs on a different port or requires a password, open `.env` in a text editor and adjust these values:*
```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=startup-finance
DB_USERNAME=
DB_PASSWORD=
```

### 3. Run the Auto-Setup Script
We've included a handy `setup` composer script that runs the heavy lifting (installing composer packages, generating the App Key, installing npm packages, and building frontend assets):
```bash
composer setup
```

*Alternative (Manual Steps):*
If the setup script is not used, run these commands in order:
```bash
composer install
php artisan key:generate
npm install
npm run build
```

### 4. Seed the Database
To populate the database with default accounts (Admin, Founder) and test data, run the seeders:
```bash
php artisan db:seed
php artisan db:seed --class=TempSeeder
```

---

## 🚀 Running the Application

To start the local development servers for both the Laravel backend and Vite frontend assets in parallel, simply run:

```bash
composer dev
```

This runs the backend server and Vite bundler simultaneously. You can access the application in your web browser at:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

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
