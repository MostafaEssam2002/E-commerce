# Laravel E-commerce Website 🛒

A comprehensive e-commerce platform built with Laravel that supports multiple user roles and bilingual functionality.

## 📋 Overview

This is a full-featured e-commerce website developed using Laravel framework with support for Arabic and English languages. The platform provides a complete online shopping experience with role-based access control for different types of users.

## 🚀 Features

### Multi-Role User System
- **Admin**: Full system control and management capabilities
- **Salesman**: Product management (add, edit, delete products)
- **User/Customer**: Shopping and order management

### Product Management
- Multiple product images with slider display
- Product categories organization
- Comprehensive product catalog

### Shopping Experience
- Shopping cart functionality with persistent storage
- Secure checkout process
- Order history tracking
- User-friendly product browsing

### Localization
- **Bilingual Support**: Arabic and English
- RTL (Right-to-Left) support for Arabic interface

### Authentication & Security
- Laravel built-in authentication system
- Role-based access control
- Secure user sessions

## 🛠️ Technology Stack

- **Backend**: Laravel (PHP Framework)
- **Frontend**: HTML5, CSS3, JavaScript
- **Database**: MySQL
- **Authentication**: Laravel Auth
- **Languages**: PHP, JavaScript, HTML, CSS

## 📦 Installation

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Node.js & NPM (for frontend assets)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/MostafaEssam2002/E-commerce.git
   cd E-commerce
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   npm run dev
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Create a MySQL database
   - Update `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

## 👥 User Roles & Permissions

### Admin
- Complete system administration
- User management
- Product and category management
- Order management
- System settings

### Salesman
- Add new products
- Edit existing products
- Delete products
- Manage product images
- View product statistics

### User/Customer
- Browse products and categories
- Add products to cart
- Persistent cart storage
- Checkout and order placement
- Order history
- Profile management

## 🌐 Language Support

The website supports both Arabic and English languages:
- Dynamic language switching
- RTL layout support for Arabic
- Localized content and interface
- Database content translation

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── views/
│   ├── lang/
│   │   ├── ar/
│   │   └── en/
│   └── assets/
└── routes/
```

## 🛒 Key Functionalities

### Product Features
- Multiple image upload and display
- Image slider/gallery
- Category-based organization
- Product search and filtering

### Shopping Cart
- Add/remove products
- Quantity management
- Persistent storage across sessions
- Real-time price calculations
- Checkout process

### Order Management
- Checkout process
- Order confirmation
- Order history tracking
- Order status updates

## 🔒 Security Features

- Laravel's built-in CSRF protection
- Input validation and sanitization
- Secure authentication system
- Role-based access control
- XSS protection

## 🤝 Contributing

1. Fork the project
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🔄 Future Enhancements

- **Payment gateway integration** (PayPal, Stripe, etc.)
- Advanced product filtering and search
- Wishlist functionality
- Product reviews and ratings
- Email notifications
- Advanced reporting dashboard
- Mobile app development
