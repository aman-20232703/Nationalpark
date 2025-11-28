
# 🌿 National Park Management System

A comprehensive web-based platform for exploring, booking, and managing visits to India's National Parks. This system provides an intuitive interface for users to discover wildlife sanctuaries, book tickets, and learn about India's rich biodiversity and conservation efforts.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

The National Park Management System is designed to streamline the process of exploring and visiting India's 107+ national parks. The platform offers a seamless experience from user registration to ticket booking, with integrated payment processing and profile management features.

### Key Highlights

- **107+ National Parks** across India
- **544,402 sq km** of protected area coverage
- User-friendly booking system with real-time availability
- Comprehensive park information, interactive maps, and immersive gallery popups
- Secure authentication and user profile management
- PDF ticket generation for confirmed bookings
- Review and rating system for parks
- Gallery showcasing India's wildlife and natural beauty

## ✨ Features

### User Management

- **User Registration & Authentication**: Secure signup/login with password encryption
- **Profile Management**: Update personal information, upload profile pictures, and manage ID proofs
- **Password Recovery**: Forgot password functionality with secure reset mechanism
- **Session Management**: Persistent login sessions with remember-me option

### Booking System

- **Park Selection**: Browse and select from multiple national parks
- **Date & Time Slots**: Choose visit dates (up to 2 months in advance) and safari timings
- **Entry Gate Selection**: Select preferred entry points
- **Ticket Management**: Book multiple tickets (1-10 per booking)
- **ID Verification**: Secure Aadhar/Passport validation
- **Payment Integration**: Multiple payment methods (UPI, Credit/Debit Card, Net Banking)
- **PDF Ticket Generation**: Downloadable booking confirmations

### Information & Engagement

- **Interactive Maps**: Google Maps integration showing all national parks
- **Park Gallery**: API-powered cards with lazy loading images, responsive sizing, and animated detail popups
- **Review System**: User reviews and ratings for parks
- **Contact Form**: Direct communication channel for queries
- **Educational Content**: Information about conservation efforts and government initiatives

### Administrative Features

- **Booking Management**: Track and manage all ticket reservations
- **User Database**: Comprehensive user information storage
- **Payment Tracking**: Monitor payment status and methods
- **Profile Image Management**: Secure upload and storage system

## 🛠 Technology Stack

### Frontend

- **HTML5**: Semantic markup and structure
- **CSS3**: Responsive design with modern styling
- **JavaScript**: Interactive UI components and client-side validation
- **AJAX**: Asynchronous data handling

### Backend

- **PHP**: Server-side logic and session management
- **MySQL**: Relational database management
- **mysqli**: Database connectivity and queries

### Libraries & Tools

- **FPDF**: PDF generation for tickets (v1.86)
- **Font Awesome**: Icon library for UI elements
- **Google Maps API**: Interactive park location mapping

### Security

- **Password Hashing**: Secure password storage using PHP's password functions
- **Session Management**: Server-side session handling
- **SQL Injection Prevention**: Prepared statements with mysqli
- **Input Validation**: Client and server-side validation

## 🏗 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     User Interface Layer                     │
│  (Landing, Home, Gallery, Booking, Profile, Review, Contact)│
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                   Application Layer (PHP)                    │
│  (Authentication, Booking Logic, Profile Management, PDF)   │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                    Database Layer (MySQL)                    │
│           (Users, Bookings, Reviews, Payments)              │
└─────────────────────────────────────────────────────────────┘
```

## 🚀 Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/LAMP (recommended for local development)

### Step-by-Step Setup

1. **Clone the Repository**

   ```bash
   git clone https://github.com/aman-20232703/Nationalpark.git
   cd Nationalpark
   ```

2. **Configure Web Server**

   - Place the project folder in your web server's document root
   - For XAMPP: `C:/xampp/htdocs/Nationalpark`
   - For LAMP: `/var/www/html/Nationalpark`

3. **Database Configuration**

   - Edit `dbconnect.php` with your database credentials:

   ```php
   $servername = 'localhost';
   $username = 'root';
   $password = 'your_password';
   $dbname = 'park';
   ```

4. **Start Services**

   ```bash
   # Start Apache and MySQL
   # For XAMPP: Use XAMPP Control Panel
   # For Linux:
   sudo service apache2 start
   sudo service mysql start
   ```

5. **Access Application**
   - Navigate to `http://localhost/Nationalpark/main/index.php`

## 🗄 Database Setup

### Create Database

```sql
CREATE DATABASE park;
USE park;
```

### Users Table

```sql
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NULL,
  address TEXT NULL,
  id_proof VARCHAR(100) NULL,
  profile_image VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Bookings Table

```sql
CREATE TABLE IF NOT EXISTS bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id VARCHAR(64) NOT NULL UNIQUE,
  user_id INT NULL,
  park VARCHAR(100) NOT NULL,
  visit_date DATE NOT NULL,
  time_slot VARCHAR(64) NOT NULL,
  entry_gate VARCHAR(64) NOT NULL,
  tickets_count TINYINT UNSIGNED NOT NULL,
  visitor_names TEXT NOT NULL,
  id_proof VARCHAR(64) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Directory Permissions

Ensure upload directories have proper write permissions:

```bash
chmod 755 uploads/
chmod 755 uploads/idproofs/
```

## 📖 Usage

### For Users

1. **Registration**

   - Navigate to the signup page
   - Provide full name, email, and password
   - Verify email and login

2. **Booking Tickets**

   - Login to your account
   - Navigate to the booking page
   - Select park, date, time slot, and number of tickets
   - Enter visitor details and ID proof
   - Complete payment via preferred method
   - Download your PDF ticket

3. **Profile Management**

   - Access profile page from navigation
   - Update personal information
   - Upload profile picture
   - Manage ID proofs

4. **Explore Parks**
   - Browse the gallery for dynamically loaded park cards
   - Click any card to view full details in an animated popup overlay
   - View interactive maps showing all parks
   - Read reviews from other visitors
   - Learn about conservation efforts

### For Administrators

- Monitor bookings through database
- Track payment status
- Manage user accounts
- Generate reports on park visits

## 📁 Project Structure

```
Nationalpark/
├── contact/              # Contact form and handling
│   ├── contact.php
│   ├── contact.css
│   └── contact.js
├── forgotpassword/       # Password recovery module
│   ├── forgotpassword.php
│   ├── forgot.css
│   └── forgot.js
├── fpdf186/             # PDF library for ticket generation
│   ├── fpdf.php
│   ├── font/
│   └── tutorial/
├── gallery/             # Park image gallery
│   ├── gallery.php
│   ├── gallery.css
│   └── gallery.js
├── home/                # Main dashboard
│   ├── home.php
│   ├── home.css
│   └── home.js
├── login/               # User authentication
│   ├── login.php
│   ├── login.css
│   └── login.js
├── main/                # Landing page
│   ├── index.php
│   └── style.css
├── privacy/             # Privacy policy
│   ├── privacy.php
│   └── privacy.css
├── profile/             # User profile management
│   ├── profile.php
│   ├── edit_profile.php
│   ├── profile.css
│   └── profile.js
├── review/              # Park reviews and ratings
│   ├── review.php
│   ├── review.css
│   └── review.js
├── sinup/               # User registration
│   ├── sinup.php
│   ├── sinup.css
│   └── sinup.js
├── tickets/             # Booking system
│   ├── tickets.php
│   ├── ticket.php
│   ├── view_ticket.php
│   ├── tickets.css
│   └── tickets.js
├── term/                # Terms of service
│   └── term.php
├── uploads/             # User uploaded files
│   ├── idproofs/
│   └── profile_images/
├── dbconnect.php        # Database configuration
└── README.md            # Project documentation
```

## 🤝 Contributing

We welcome contributions to improve the National Park Management System!

### How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards for PHP
- Use meaningful variable and function names
- Comment complex logic
- Test thoroughly before submitting

## 🐛 Known Issues & Future Enhancements

### Known Issues

- Payment gateway integration is currently using QR code placeholder
- Email verification system needs implementation

### Planned Features

- [ ] Real payment gateway integration (Razorpay/PayU)
- [ ] Email notifications for bookings
- [ ] Admin dashboard
- [ ] Multi-language support
- [ ] Mobile app version
- [ ] Advanced search and filtering
- [ ] Booking history and analytics
- [ ] Wildlife sighting reports

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Authors

- **Aman** - Initial work - [aman-20232703](https://github.com/aman-20232703)

## 🙏 Acknowledgments

- FPDF library for PDF generation
- Font Awesome for icons
- Google Maps API for location services
- India's Ministry of Environment, Forest and Climate Change for park data
- All contributors and testers

## 📞 Support

For support, email support@nationalpark.com or open an issue on GitHub.

## 🌟 Show Your Support

Give a ⭐️ if this project helped you!

---

**Built with 💚 for wildlife conservation and nature lovers**
