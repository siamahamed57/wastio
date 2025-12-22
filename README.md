# Wastio - Recycling Platform

A simple waste management platform where people can buy and sell recyclable materials.

## What is this?

Wastio connects waste sellers with buyers. If you have recyclable waste, you can sell it. If you need recyclable materials, you can buy them. Collection agents help with pickup and delivery.

## Who can use it?

- **Waste Sellers** - People who want to sell their recyclable waste
- **Waste Buyers** - Businesses or individuals looking for recyclable materials  
- **Collection Agents** - People who handle pickup and delivery
- **Admins** - Platform managers

## What you need

- XAMPP (includes Apache and MySQL)
- A web browser
- That's it!

## Setup Instructions

### 1. Install XAMPP

Download and install XAMPP from https://www.apachefriends.org

### 2. Put the files in the right place

Copy the `wastio` folder to:
```
C:\xampp\htdocs\wastio
```

### 3. Start XAMPP

Open XAMPP Control Panel and start:
- Apache
- MySQL

### 4. Create the database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click "New" on the left side
3. Type `recycling_platform` as the database name
4. Click "Create"

### 5. Import the database

1. Click on the `recycling_platform` database you just created
2. Click the "Import" tab at the top
3. Click "Choose File" and select: `C:\xampp\htdocs\wastio\database\db.sql`
4. Scroll down and click "Go"

### 6. Open the website

Go to: `http://localhost/wastio`

You should see the home page!

## How to use

### Register an account

1. Click "Login" button in the header
2. Click "Register here" 
3. Fill in your details
4. Choose your role (Seller, Buyer, or Agent)
5. Click Register

### Login

1. Enter your email and password
2. Click Login
3. You'll be taken to your dashboard based on your role

## Project Structure

```
wastio/
├── admin/          - Admin dashboard
├── agent/          - Collection agent dashboard  
├── buyer/          - Buyer dashboard
├── seller/         - Seller dashboard
├── auth/           - Login and logout pages
├── assets/         - CSS, JS, and images
├── config/         - Database connection
├── database/       - SQL file
├── includes/       - Header and footer
├── pages/          - Home and other pages
└── index.php       - Main entry point
```

## Features

- User registration and login
- Role-based dashboards
- Browse waste items by category
- Post waste items for sale
- Request to buy items
- Collection agent system
- Admin management

## Database Info

- Database name: `recycling_platform`
- Default username: `root`
- Default password: (empty)

If your MySQL has a different password, update it in `config/db.php`

## Troubleshooting

**Can't connect to database?**
- Make sure MySQL is running in XAMPP
- Check if database name is `recycling_platform`
- Verify credentials in `config/db.php`

**Login not working?**
- Make sure you imported the `db.sql` file
- Check if the database has the `users` table
- Try registering a new account

**Page not found?**
- Make sure the folder is in `C:\xampp\htdocs\wastio`
- Check if Apache is running
- Try `http://localhost/wastio` (not `http://localhost/wastio/index.php`)

## Color Scheme

The platform uses a teal color palette:
- Primary Dark: #005461
- Primary Medium: #018790  
- Accent: #00B7B5
- Background: #F4F4F4

## Technologies Used

- PHP (backend)
- MySQL (database)
- HTML/CSS (frontend)
- JavaScript (interactivity)
- Poppins font (typography)

## Notes

- This is a college project for Web Technologies course
- All passwords are securely hashed
- Users can be blocked by admins
- The platform is designed to be simple and easy to use

## Need Help?

If something doesn't work:
1. Check if XAMPP services are running
2. Make sure the database is imported correctly
3. Clear your browser cache
4. Check the browser console for errors

---

Made with ♻️ for a sustainable future
