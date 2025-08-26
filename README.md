# E-Learning Platform

<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel Version">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP Version">
    <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

## 📚 About Laravel Storm

Laravel Storm is a comprehensive e-learning platform built with Laravel 12, designed to provide a seamless online education experience. The platform supports multiple user roles including students, instructors, and administrators, offering features for course creation, enrollment management, progress tracking, and more.

## ✨ Features

### 🔐 Authentication & Authorization
- **Multirole Authentication**: Students, Instructors, and Administrators
- **JWT Token-based API Authentication** using Laravel Sanctum
- **Secure Registration & Login** with email verification
- **Token Refresh** for extended sessions

### 👥 User Management
- **Role-based Access Control** (Student, Instructor, Admin)
- **User Profiles** with avatars, bios, and contact information
- **Instructor Management** with specialized endpoints
- **Admin Panel** for user administration

### 📖 Course Management
- **Course Creation & Management** by instructors
- **Course Categories** for better organization
- **Multi-level Courses** (Beginner, Intermediate, Advanced)
- **Course Thumbnails & Media** support
- **Course Pricing** and monetization
- **Course Status Management** (Published, Draft, Archived)

### 🎓 Learning Features
- **Student Enrollments** with enrollment tracking
- **Lesson Progress Tracking** for individual students
- **Course Reviews & Ratings** system
- **Comprehensive Progress Analytics**

### 🛠️ Technical Features
- **RESTful API** with comprehensive endpoints
- **Database Relationships** with Eloquent ORM
- **File Upload Support** for course materials
- **Robust Error Handling** and validation
- **Unit Testing** with PHPUnit

## 🏗️ System Architecture

### Database Schema
The platform includes the following main entities:
- **Users** (Students, Instructors, Admins)
- **Courses** with categories and lessons
- **Enrollments** linking students to courses
- **Lesson Progress** tracking
- **Reviews** and ratings system
- **Categories** for course organization

### API Endpoints
```
Authentication:
POST   /api/login
POST   /api/register
POST   /api/logout
POST   /api/refresh-token
GET    /api/user

User Management:
GET    /api/users/instructors
GET    /api/users/admins
GET    /api/users/instructor/{instructor}
GET    /api/users/admin/{id}
DELETE /api/users/admin/{id}
DELETE /api/users/instructor/{id}

Resource Management:
GET|POST|PUT|DELETE /api/users
GET|POST|PUT|DELETE /api/courses
GET|POST|PUT|DELETE /api/categories
GET|POST|PUT|DELETE /api/enrollments
GET|POST|PUT|DELETE /api/reviews

Relationship Endpoints:
GET /api/categories/{category}/courses
GET /api/courses/{course}/category
GET /api/enrollments/{enrollment}/courses
GET /api/enrollments/{enrollment}/students
GET /api/reviews/summary
GET /api/reviews/{review}/students
GET /api/reviews/{review}/courses
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgresSQL/SQLite database

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Laravel_Storm
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_storm
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the Database** (optional)
   ```bash
   php artisan db:seed
   ```

8. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

9. **Start the Development Server**
   ```bash
   # Start all services (server, queue, logs, vite)
   composer run dev
   
   # Or start individually:
   php artisan serve
   npm run dev
   ```

## 🧪 Testing

Run the test suite:
```bash
# Run all tests
composer run test

# Run specific test
php artisan test --filter=ExampleTest
```

## 📱 API Usage

### Authentication
1. **Register a new user**:
   ```bash
   POST /api/register
   Content-Type: application/json
   
   {
     "name": "John Doe",
     "email": "john@example.com",
     "password": "password123",
     "role": "student"
   }
   ```

2. **Login**:
   ```bash
   POST /api/login
   Content-Type: application/json
   
   {
     "email": "john@example.com",
     "password": "password123"
   }
   ```

3. **Use the token for authenticated requests**:
   ```bash
   Authorization: Bearer {your-token}
   ```

## 🛠️ Development

### Code Style
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting: `./vendor/bin/pint`

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## 📂 Project Structure

```
Laravel_Storm/
├── app/
│   ├── Http/Controllers/
│   │   ├── auth/           # Authentication controllers
│   │   ├── CategoryController.php
│   │   ├── CourseController.php
│   │   ├── EnrollmentController.php
│   │   └── ReviewController.php
│   └── Models/
│       ├── User.php
│       ├── Course.php
│       ├── Category.php
│       ├── Enrollment.php
│       ├── Lesson.php
│       ├── Lesson_progress.php
│       └── Review.php
├── database/
│   ├── migrations/         # Database schema
│   └── seeders/           # Sample data
├── routes/
│   ├── api.php            # API routes
│   └── web.php            # Web routes
├── tests/                 # Unit and Feature tests
└── Http_Api/              # API documentation
```

## 🔧 Configuration

### Environment Variables
Key environment variables for the application:

```env
# Application
APP_NAME="Laravel Storm"
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=mysql
DB_DATABASE=laravel_storm

# Authentication
SANCTUM_STATEFUL_DOMAINS=localhost:3000

# File Storage
FILESYSTEM_DISK=local
```

## 📈 Performance & Scaling

- **Database Optimization**: Proper indexing on foreign keys and search columns
- **Caching**: Laravel's built-in caching system for improved performance
- **Queue System**: Background job processing for heavy operations
- **API Rate Limiting**: Built-in throttling for API endpoints

## 🔒 Security Features

- **CSRF Protection** on web routes
- **SQL Injection Prevention** through Eloquent ORM
- **XSS Protection** with input validation
- **Authentication Rate Limiting**
- **Secure Password Hashing** with bcrypt

## 📊 Monitoring & Logging

- **Laravel Pail** for real-time log monitoring
- **Application logs** in `storage/logs/`
- **Error tracking** with detailed stack traces
- **Performance monitoring** capabilities

## 🚀 Deployment

### Production Checklist
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificates
5. Configure queue workers
6. Set up cron jobs for scheduled tasks
7. Optimize application: `php artisan optimize`

## 📄 License

This project is licensed under the MIT License—see the [LICENSE](LICENSE) file for details.

## 🤝 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation in `/docs`

## 🙏 Acknowledgments

- Built with [Laravel Framework](https://laravel.com)
- Authentication powered by [Laravel Sanctum](https://laravel.com/docs/sanctum)
- Icons and styling inspiration from various open-source projects

---

**Made with ❤️ using Laravel 12**
