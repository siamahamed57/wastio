# WASTIO - Recycling Platform
## Project Report

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Project Overview](#project-overview)
3. [System Architecture](#system-architecture)
4. [Features & Functionality](#features--functionality)
5. [Technology Stack](#technology-stack)
6. [Database Design](#database-design)
7. [User Roles & Permissions](#user-roles--permissions)
8. [Security Implementation](#security-implementation)
9. [Screenshots & Demonstrations](#screenshots--demonstrations)
10. [Challenges & Solutions](#challenges--solutions)
11. [Future Enhancements](#future-enhancements)
12. [Conclusion](#conclusion)

---

## Executive Summary

**Wastio** is a comprehensive web-based recycling platform designed to connect waste sellers with buyers, facilitating efficient waste management and promoting environmental sustainability. The platform serves as a digital marketplace where recyclable materials can be listed, browsed, and traded, contributing to the circular economy.

### Key Highlights:
- **Multi-role system** supporting Sellers, Buyers, Collection Agents, and Administrators
- **Real-time waste item management** with image uploads and categorization
- **Secure authentication** with password hashing and Remember Me functionality
- **Admin dashboard** for comprehensive platform oversight
- **Responsive design** optimized for all devices
- **Cookie consent system** for GDPR compliance

---

## Project Overview

### 1.1 Problem Statement

Traditional waste management systems often lack efficient channels for connecting waste generators with recycling facilities. This leads to:
- Valuable recyclable materials ending up in landfills
- Missed economic opportunities for both waste sellers and buyers
- Inefficient waste collection and transportation
- Limited transparency in the recycling process

### 1.2 Proposed Solution

Wastio addresses these challenges by providing:
- A centralized digital platform for waste trading
- Transparent pricing and categorization of recyclable materials
- Direct communication between sellers and buyers
- Administrative oversight for quality control
- Collection agent coordination for logistics

### 1.3 Project Objectives

1. **Primary Objectives:**
   - Create a user-friendly platform for waste trading
   - Implement secure user authentication and authorization
   - Develop comprehensive admin controls
   - Enable real-time waste item management

2. **Secondary Objectives:**
   - Promote environmental awareness
   - Facilitate the circular economy
   - Provide economic opportunities in waste management
   - Ensure data security and user privacy

---

## System Architecture

### 3.1 Architecture Overview

Wastio follows a **three-tier architecture**:

```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│  (HTML, CSS, JavaScript, PHP Views)     │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│         Application Layer               │
│     (PHP Business Logic, APIs)          │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│           Data Layer                    │
│        (MySQL Database)                 │
└─────────────────────────────────────────┘
```

### 3.2 Directory Structure

```
wastio/
├── admin/                  # Admin panel
│   ├── api/               # Admin API endpoints
│   ├── dashboard.php      # Admin dashboard
│   ├── script.js          # Admin JavaScript
│   └── style.css          # Admin styles
├── auth/                  # Authentication
│   ├── login.php          # Login & Registration
│   └── logout.php         # Logout handler
├── buyer/                 # Buyer dashboard
├── seller/                # Seller dashboard
│   └── wastio-seller/     # Seller interface
├── agent/                 # Collection agent
├── pages/                 # Public pages
│   ├── about.php
│   ├── browse.php
│   ├── contact.php
│   └── how-it-works.php
├── includes/              # Shared components
│   ├── header.php
│   └── footer.php
├── assets/                # Static resources
│   ├── css/
│   ├── js/
│   └── images/
├── config/                # Configuration
│   └── db.php            # Database connection
├── uploads/               # User uploads
│   └── waste_items/      # Waste item images
└── database/              # Database scripts
    └── db.sql            # Database schema
```

---

## Features & Functionality

### 4.1 Authentication System

#### Registration
- **Multi-role registration** (Seller, Buyer, Collection Agent)
- **Comprehensive validation:**
  - Name: Minimum 3 characters
  - Email: Valid format + duplicate check
  - Phone: 10-15 digits + duplicate check
  - Password: Minimum 8 characters
- **Password strength indicator** (Weak/Medium/Strong)
- **Real-time client-side validation**
- **Server-side validation** for security
- **Admin approval workflow**

#### Login
- **Secure password verification** using bcrypt hashing
- **Remember Me functionality** (30-day cookie)
- **Auto-login** for returning users
- **Role-based redirection** to appropriate dashboards
- **Account status checks** (approved/blocked)

### 4.2 Waste Seller Features

1. **Dashboard Overview:**
   - Total items listed
   - Pending requests
   - Accepted requests
   - Quick statistics

2. **Item Management:**
   - Add new waste items with images
   - Edit existing items
   - Delete items with confirmation
   - Mark items as sold
   - View item details

3. **Request Management:**
   - View incoming buy requests
   - Accept/reject requests
   - Contact buyer information
   - Request status tracking

4. **Profile Management:**
   - Update personal information
   - Change password
   - View account status

### 4.3 Admin Panel Features

1. **Dashboard Statistics:**
   - Total users count
   - Pending approvals
   - Total waste items
   - Total buy requests
   - Blocked users count

2. **User Management:**
   - View all users with role information
   - Approve pending registrations
   - Block/unblock users
   - Delete user accounts
   - Filter by role and status
   - Search functionality

3. **Waste Item Management:**
   - View all waste items across platform
   - See seller information
   - Track request counts
   - Delete inappropriate items
   - Monitor item status

4. **Buy Request Management:**
   - View all buy requests
   - See buyer and seller details
   - Delete requests if needed
   - Monitor transaction status

5. **Advanced Features:**
   - Theme toggle (Light/Dark mode)
   - Real-time data updates
   - Confirmation modals for destructive actions
   - Toast notifications for feedback

### 4.4 Public Pages

1. **Home Page:**
   - Platform introduction
   - Key features showcase
   - Call-to-action buttons

2. **About Us:**
   - Mission and vision
   - Core values
   - Impact statistics
   - Team information

3. **Browse Items:**
   - Live waste item listings
   - Category filtering
   - Search functionality
   - Price sorting
   - Contact seller directly

4. **How It Works:**
   - Step-by-step guide
   - User role explanations
   - Getting started instructions

5. **Contact:**
   - Contact form with validation
   - Business information
   - Social media links
   - Office hours

### 4.5 Additional Features

1. **Cookie Consent System:**
   - GDPR-compliant popup
   - Accept/Decline options
   - 365-day persistence
   - Smooth animations

2. **Responsive Design:**
   - Mobile-first approach
   - Tablet optimization
   - Desktop enhancement
   - Touch-friendly interfaces

3. **Theme System:**
   - Light/Dark mode toggle
   - Persistent preference storage
   - Smooth transitions
   - Consistent theming

---

## Technology Stack

### 5.1 Frontend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| HTML5 | - | Structure and semantics |
| CSS3 | - | Styling and animations |
| JavaScript (ES6+) | - | Client-side interactivity |
| Font Awesome | 6.4.0 | Icons and visual elements |
| Google Fonts (Poppins) | - | Typography |

### 5.2 Backend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 7.4+ | Server-side logic |
| MySQL | 8.0+ | Database management |
| Apache | 2.4+ | Web server |

### 5.3 Development Tools

- **XAMPP** - Local development environment
- **VS Code** - Code editor
- **Git** - Version control
- **Chrome DevTools** - Debugging and testing

### 5.4 Design Patterns & Principles

1. **MVC-inspired architecture** - Separation of concerns
2. **RESTful API design** - For admin endpoints
3. **Progressive enhancement** - Core functionality without JavaScript
4. **Mobile-first design** - Responsive from the ground up
5. **DRY principle** - Reusable components and functions

---

## Database Design

### 6.1 Database Schema

#### Tables Overview

1. **users**
   - Stores user account information
   - Links to roles table
   - Tracks approval and block status

2. **roles**
   - Defines user roles (Seller, Buyer, Agent, Admin)
   - Referenced by users table

3. **waste_categories**
   - Categorizes waste items
   - Used for filtering and organization

4. **waste_items**
   - Stores waste item listings
   - Links to sellers and categories
   - Tracks item status

5. **buy_requests**
   - Manages purchase requests
   - Links buyers to waste items
   - Tracks request status

### 6.2 Entity Relationship Diagram

```
┌─────────────┐         ┌──────────────┐
│    roles    │────────<│    users     │
└─────────────┘         └──────────────┘
                              │
                              │ (seller_id)
                              ↓
                        ┌──────────────┐
                        │ waste_items  │
                        └──────────────┘
                              │
                              │
                              ↓
                        ┌──────────────┐
                        │buy_requests  │
                        └──────────────┘
                              │
                              │ (buyer_id)
                              ↓
                        ┌──────────────┐
                        │    users     │
                        └──────────────┘
```

### 6.3 Key Database Features

1. **Foreign Key Constraints:**
   - Maintain referential integrity
   - Cascade deletions where appropriate
   - Prevent orphaned records

2. **Indexes:**
   - Primary keys on all tables
   - Indexes on frequently queried columns
   - Composite indexes for complex queries

3. **Data Types:**
   - Appropriate types for each field
   - VARCHAR for text with limits
   - DECIMAL for precise pricing
   - TIMESTAMP for date tracking

---

## User Roles & Permissions

### 7.1 Role Hierarchy

```
System Admin (role_id: 3)
    ↓
Collection Agent (role_id: 4)
    ↓
Waste Seller (role_id: 1)
    ↓
Waste Buyer (role_id: 2)
```

### 7.2 Permission Matrix

| Feature | Seller | Buyer | Agent | Admin |
|---------|--------|-------|-------|-------|
| List waste items | ✓ | ✗ | ✗ | ✓ |
| Send buy requests | ✗ | ✓ | ✗ | ✓ |
| Manage pickups | ✗ | ✗ | ✓ | ✓ |
| Approve users | ✗ | ✗ | ✗ | ✓ |
| Delete any item | ✗ | ✗ | ✗ | ✓ |
| Block users | ✗ | ✗ | ✗ | ✓ |
| View statistics | Own | Own | Own | All |

### 7.3 Access Control Implementation

1. **Session-based authentication**
2. **Role verification on every protected page**
3. **API endpoint authorization checks**
4. **Database-level permission enforcement**

---

## Security Implementation

### 8.1 Authentication Security

1. **Password Security:**
   - Bcrypt hashing (PASSWORD_DEFAULT)
   - Minimum 8 character requirement
   - Password strength validation
   - No plain text storage

2. **Session Management:**
   - Secure session handling
   - Session regeneration on login
   - Automatic session timeout
   - Session hijacking prevention

3. **Cookie Security:**
   - HttpOnly flag enabled
   - Secure flag for HTTPS
   - SameSite attribute
   - Proper expiration times

### 8.2 Input Validation

1. **Client-side Validation:**
   - Real-time form validation
   - Pattern matching for emails/phones
   - Length restrictions
   - Type checking

2. **Server-side Validation:**
   - mysqli_real_escape_string()
   - filter_var() for emails
   - Regex validation for phones
   - Prepared statements

### 8.3 SQL Injection Prevention

1. **Prepared Statements:**
   ```php
   $stmt = mysqli_prepare($conn, $query);
   mysqli_stmt_bind_param($stmt, "i", $user_id);
   ```

2. **Input Sanitization:**
   - Escape special characters
   - Validate data types
   - Whitelist validation

### 8.4 XSS Prevention

1. **Output Encoding:**
   ```php
   echo htmlspecialchars($user_input);
   ```

2. **Content Security Policy:**
   - Restrict inline scripts
   - Whitelist trusted sources

### 8.5 CSRF Protection

1. **Token-based verification** (planned)
2. **SameSite cookie attribute**
3. **Referer header validation**

### 8.6 File Upload Security

1. **File type validation**
2. **File size limits**
3. **Secure file naming**
4. **Separate upload directory**
5. **No script execution in uploads**

---

## Challenges & Solutions

### 9.1 Technical Challenges

#### Challenge 1: Modal Separation for Different Actions
**Problem:** Initially used a single modal for all confirmation actions (delete, mark as sold, block, unblock), causing confusion.

**Solution:** Created separate modals with distinct styling:
- Delete modal: Red theme with warning icon
- Sold modal: Green theme with success icon
- Block modal: Orange theme with ban icon
- Dynamic content and button styling based on action

#### Challenge 2: Password Strength Indication
**Problem:** Users creating weak passwords without guidance.

**Solution:** Implemented real-time password strength checker:
- Checks for length, uppercase, lowercase, numbers, special characters
- Visual progress bar with color coding
- Text feedback (Weak/Medium/Strong)
- Client and server-side validation

#### Challenge 3: Remember Me Functionality
**Problem:** Users having to login repeatedly.

**Solution:** Implemented secure cookie-based Remember Me:
- Secure token generation
- HttpOnly cookies
- 30-day expiration
- Auto-login on return visits
- Proper cookie cleanup on logout

#### Challenge 4: Admin Panel Data Management
**Problem:** Managing large amounts of data efficiently.

**Solution:** Implemented filtering and search:
- Real-time filtering by role and status
- Search functionality
- Sorting options
- Pagination-ready structure

### 9.2 Design Challenges

#### Challenge 1: Consistent Theming Across Roles
**Problem:** Different dashboards looking inconsistent.

**Solution:**
- Created shared CSS variables
- Reusable component library
- Consistent color palette
- Unified design system

#### Challenge 2: Mobile Responsiveness
**Problem:** Complex dashboards not working well on mobile.

**Solution:**
- Mobile-first approach
- Flexible grid layouts
- Touch-friendly buttons
- Collapsible mobile menu
- Responsive tables

---

## Future Enhancements

### 10.1 Planned Features

1. **Payment Integration:**
   - Online payment gateway
   - Escrow system
   - Transaction history
   - Invoice generation

2. **Rating & Review System:**
   - User ratings
   - Transaction reviews
   - Reputation scores
   - Verified badges

3. **Real-time Notifications:**
   - Push notifications
   - Email alerts
   - SMS notifications
   - In-app notifications

4. **Advanced Search:**
   - Location-based search
   - Price range filters
   - Advanced sorting options
   - Saved searches

5. **Analytics Dashboard:**
   - Sales analytics
   - User behavior tracking
   - Revenue reports
   - Trend analysis

6. **Mobile Application:**
   - Native iOS app
   - Native Android app
   - Progressive Web App (PWA)

7. **AI Integration:**
   - Waste categorization
   - Price suggestions
   - Demand prediction
   - Fraud detection

8. **Logistics Integration:**
   - Route optimization
   - Pickup scheduling
   - GPS tracking
   - Delivery confirmation

### 10.2 Scalability Improvements

1. **Database Optimization:**
   - Query optimization
   - Caching layer (Redis)
   - Database replication
   - Sharding for large datasets

2. **Performance Enhancement:**
   - CDN integration
   - Image optimization
   - Lazy loading
   - Code minification

3. **Security Enhancements:**
   - Two-factor authentication
   - Biometric login
   - Advanced fraud detection
   - Security audit logging

---

## Conclusion

### 11.1 Project Summary

Wastio successfully demonstrates a comprehensive web-based solution for waste management and recycling. The platform effectively addresses the gap between waste generators and recycling facilities through:

- **Robust multi-role system** supporting diverse user types
- **Secure authentication** with modern security practices
- **Intuitive user interfaces** with responsive design
- **Comprehensive admin controls** for platform management
- **Professional public pages** for user engagement

### 11.2 Learning Outcomes

Through this project, we gained valuable experience in:

1. **Full-stack web development** using PHP and MySQL
2. **User authentication and authorization** implementation
3. **Database design and normalization**
4. **Responsive web design** principles
5. **Security best practices** for web applications
6. **Project management** and version control
7. **User experience (UX)** design
8. **API development** and integration

### 11.3 Impact & Significance

Wastio contributes to:
- **Environmental sustainability** by facilitating recycling
- **Economic opportunities** in waste management
- **Digital transformation** of traditional waste trading
- **Community engagement** in environmental initiatives

### 11.4 Acknowledgments

We would like to express our gratitude to our honorable teachers for their guidance and support throughout this project. Their expertise and encouragement were instrumental in bringing this project to fruition.

---

## Appendices

### Appendix A: Installation Guide

1. **Prerequisites:**
   - XAMPP (PHP 7.4+, MySQL 8.0+)
   - Web browser (Chrome, Firefox, Safari)

2. **Installation Steps:**
   ```bash
   # 1. Clone/Copy project to htdocs
   # 2. Import database
   mysql -u root recycling_platform < database/db.sql
   
   # 3. Configure database connection
   # Edit config/db.php with your credentials
   
   # 4. Start Apache and MySQL
   # 5. Access: http://localhost/wastio
   ```

3. **Default Admin Credentials:**
   - Email: admin@gmail.com
   - Password: admin1234

### Appendix B: API Documentation

#### Admin API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/admin/api/get_users.php` | GET | Fetch all users |
| `/admin/api/approve_user.php` | POST | Approve user |
| `/admin/api/block_user.php` | POST | Block/unblock user |
| `/admin/api/delete_user.php` | POST | Delete user |
| `/admin/api/get_all_waste.php` | GET | Fetch all waste items |
| `/admin/api/delete_waste_item.php` | POST | Delete waste item |
| `/admin/api/get_all_requests.php` | GET | Fetch all buy requests |
| `/admin/api/delete_request.php` | POST | Delete buy request |
| `/admin/api/get_admin_stats.php` | GET | Fetch statistics |

### Appendix C: Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 90+ | ✓ Fully Supported |
| Firefox | 88+ | ✓ Fully Supported |
| Safari | 14+ | ✓ Fully Supported |
| Edge | 90+ | ✓ Fully Supported |
| Opera | 76+ | ✓ Fully Supported |

### Appendix D: System Requirements

**Minimum Requirements:**
- Processor: 1 GHz
- RAM: 2 GB
- Storage: 500 MB
- Internet: Broadband connection

**Recommended Requirements:**
- Processor: 2 GHz dual-core
- RAM: 4 GB
- Storage: 1 GB
- Internet: High-speed broadband

---

**Project Team:**
- [Your Name]
- [Team Member Names]

**Institution:**
- [Your Institution Name]

**Submission Date:**
- January 19, 2026

**Supervisor:**
- [Teacher's Name]

---

*This project report is submitted in partial fulfillment of the requirements for [Course Name/Degree Program].*
