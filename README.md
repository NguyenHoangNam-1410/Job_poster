# Job Poster Management System

...

## 📚 Project Overview

...

## ✨ Key Features

...

## 🛠️ Tech Stack

### Frontend
- **HTML5**: Semantic markup structure
- **CSS3**: 
  - Tailwind CSS (utility-first framework)
  - Custom CSS for specific components
  - Swiper.js for carousels
- **JavaScript**:
  - Vanilla JavaScript for interactivity
  - Fetch API for AJAX requests
  - Notyf.js for notifications
  - jQuery (minimal usage)

### Backend
- **PHP 7.4+**: Server-side programming language
- **MySQL**: Relational database management
- **Apache**: Web server (XAMPP)

### Architecture
- **MVC Pattern**: Model-View-Controller architecture
- **Service Layer Pattern**: Business logic separated from controllers
- **DAO Pattern**: Data Access Object for database operations
- **Session Management**: PHP sessions for cart and user authentication

## 📁 Project Structure

```
Job_poster/
├── app/
│   ├── controllers/        # Controllers for handling HTTP requests
│   ├── services/          # Service layer for business logic
│   ├── dao/               # Data Access Objects
│   ├── helpers/           # Helper classes
│   ├── models/            # Data models
│   └── views/             # View templates
│       ├── admin/         # Admin panel views
│       ├── layouts/       # Shared layouts (header, footer)
│       └── public/        # Guest-facing views
├── config/                # Configuration files
├── public/                # Public assets and entry point
│   ├── index.php         # Main router
│   ├── css/              # Stylesheets
│   ├── javascript/       # JavaScript files
│   └── image/            # Images and media
└── README.md
```

## 🗄️ Database Schema

...

## 🚀 Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Edge, etc.)

### Setup Instructions

1. **Clone or download the project**
   ```bash
   git clone <>
   cd Job_poster
   ```

2. **Move to XAMPP directory**
   ```bash
   # Copy the project to XAMPP's htdocs folder
   cp -r Job_poster C:/xampp/htdocs/
   ```

3. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services

4. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `job_poster`
   - Import the SQL files from `config/` folder:
     - `create_db.sql` (database structure)
     - `init_db.sql` (sample data - if available)

5. **Configure Database Connection**
   - Edit `config/db.php` if needed
   - Default settings:
     ```php
     host: localhost
     username: root
     password: (empty)
     database: job_poster
     ```

6. **Access the Application**
   - Guest Interface: http://localhost/Job_poster/public/
   - Admin Panel: http://localhost/Job_poster/public/users

## 💡 Usage

### Guest Flow
...

### Admin Flow
...

## 🔧 Development

### Architecture Layers

The application follows a **3-tier architecture** with clear separation of concerns:

```
Controller (HTTP Layer)
    ↓
Service (Business Logic Layer)
    ↓
DAO (Data Access Layer)
    ↓
Database
```

### Adding New Features

1. **Create Model** (if needed)
   - Define data structure in `app/models/`
   - Example: `Product.php`, `User.php`

2. **Create DAO** for database operations
   - Location: `app/dao/`
   - Handle all SQL queries and database interactions
   - Example: `ProductDAO.php`

3. **Create Service** for business logic
   - Location: `app/services/`
   - Process data, validate inputs, implement business rules
   - Call DAOs for data operations
   - Example: `ProductService.php`

4. **Create Controller** for HTTP handling
   - Location: `app/controllers/`
   - Handle requests/responses
   - Call service methods
   - Render views or return JSON
   - Example: `ProductController.php`

5. **Create Views** for UI
   - Location: `app/views/`
   - HTML templates with embedded PHP
   - Admin views in `app/views/admin/`
   - Public views in `app/views/public/`

6. **Add Routes** in router
   - Edit `public/index.php`
   - Map URLs to controller methods

### Code Organization

- **Controllers**: 
  - Handle HTTP requests and responses
  - Validate request parameters
  - Call appropriate service methods
  - Return views or JSON responses
  
- **Services**: 
  - Contain business logic
  - Data validation and transformation
  - Coordinate between multiple DAOs
  - Transaction management
  
- **DAOs**: 
  - Database operations and queries
  - CRUD operations
  - Execute SQL statements
  - Return data models
  
- **Models**: 
  - Data structures and entity classes
  - Getters and setters
  - Simple validation methods
  
- **Views**: 
  - HTML templates with PHP
  - Display data from controllers
  - Forms and user interfaces
  
- **Helpers**: 
  - Reusable utility classes
  - Icons, formatters, validators

## 🎨 Features Highlights

...

## 📄 License

This project is developed for educational purposes.

## 👥 Contributors

- Developer: NguyenHoangNam-1410

## 🔗 Repository

GitHub: 

---

**Note**: This is a learning project demonstrating full-stack web development with PHP and MySQL.
