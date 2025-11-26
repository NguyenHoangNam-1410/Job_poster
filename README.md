# WorkNest - Job Posting Management System

A comprehensive web-based job posting and recruitment management platform built with PHP and MySQL. WorkNest enables employers to post job opportunities, manage applications, and connect with potential candidates while providing administrators and staff with tools to oversee the entire recruitment ecosystem.

## 📚 Project Overview

WorkNest is a full-featured job board management system designed to streamline the hiring process. The platform supports multiple user roles (Admin, Staff, Employer, and Job Seekers) with distinct interfaces and capabilities. It implements modern web development patterns including MVC architecture, service layer abstraction, and DAO pattern for clean, maintainable code.

**Key Capabilities:**
- Multi-role user management with role-based access control
- Job posting creation and management by employers
- Job category organization and filtering
- Company profile management
- Application tracking and management
- Feedback and rating system
- Statistical dashboards and reporting
- Staff action logging and audit trails

## ✨ Key Features

### For Employers
- **Company Profile Management**: Create and manage company profiles with logo, description, and contact information
- **Job Posting**: Create, edit, and manage job listings with detailed descriptions, requirements, and benefits
- **Application Management**: Review and manage job applications from candidates
- **Dashboard**: View statistics on job postings, applications, and engagement metrics

### For Job Seekers
- **Job Discovery**: Browse and search job listings by category, location, and keywords
- **Advanced Filtering**: Filter jobs by multiple criteria including company, category, and posting date
- **Job Details**: View comprehensive job information including company details and requirements
- **Related Jobs**: Discover similar job opportunities based on category and criteria

### For Admin & Staff
- **User Management**: Manage user s, roles, and permissions
- **Company Approval**: Review and approve company registrations
- **Job Moderation**: Monitor and moderate job postings for compliance
- **Category Management**: Create and organize job categories
- **Statistics & Reports**: Access comprehensive analytics and reporting dashboards
- **Audit Logs**: Track staff actions and system activities

### General Features
- **Responsive Design**: Mobile-friendly interface that works across all devices
- **Real-time Notifications**: Toast notifications for user actions and system events
- **AJAX-powered**: Dynamic content loading without page refreshes
- **Secure Authentication**: Session-based authentication with JWT token support
- **Email Integration**: PHPMailer integration for email notifications and OTP verification

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

### Libraries & Frameworks
- **Composer**: Dependency management
- **Firebase JWT**: JSON Web Token authentication
- **Google API Client**: Google services integration
- **PHPMailer**: Email sending functionality
- **Monolog**: Logging library
- **Guzzle**: HTTP client for API requests

### Architecture & Design Patterns
- **MVC Pattern**: Clear separation of Model-View-Controller components
- **Service Layer Pattern**: Business logic encapsulation separate from HTTP concerns
- **DAO Pattern**: Data Access Objects for clean database abstraction
- **Dependency Injection**: Loose coupling between components
- **Session Management**: Secure PHP sessions for authentication and state management

## 📁 Project Structure

```
Worknest/
├── app/
│   ├── controllers/           # HTTP request handlers
│   │   ├── AuthController.php         # Authentication & authorization
│   │   ├── CompanyController.php      # Company profile management
│   │   ├── JobController.php          # Job posting CRUD operations
│   │   ├── UserController.php         # User management
│   │   ├── StatisticController.php    # Analytics and reporting
│   │   ├── FeedbackController.php     # Feedback system
│   │   └── ...
│   ├── services/              # Business logic layer
│   │   ├── JobService.php             # Job-related business logic
│   │   ├── CompanyService.php         # Company operations
│   │   ├── UserService.php            # User management logic
│   │   ├── StatisticsService.php      # Statistical computations
│   │   └── ...
│   ├── dao/                   # Data Access Objects
│   │   ├── JobDAO.php                 # Job database operations
│   │   ├── EmployerDAO.php            # Employer data access
│   │   ├── UserDAO.php                # User data access
│   │   └── ...
│   ├── models/                # Data entity classes
│   │   ├── Job.php                    # Job entity
│   │   ├── User.php                   # User entity
│   │   ├── Employer.php               # Employer entity
│   │   └── ...
│   ├── views/                 # View templates
│   │   ├── admin/                     # Admin dashboard views
│   │   ├── employer/                  # Employer interface views
│   │   ├── staff/                     # Staff management views
│   │   ├── public/                    # Public-facing views
│   │   ├── auth/                      # Authentication views
│   │   ├── components/                # Reusable UI components
│   │   └── layouts/                   # Shared layouts
│   └── helpers/               # Utility classes
│       └── Icons.php                  # Icon helper functions
├── config/                    # Configuration files
│   ├── db.php                         # Database configuration
│   ├── create_db.sql                  # Database schema
│   └── init_db.sql                    # Initial data seeding
├── public/                    # Public web root
│   ├── index.php                      # Application entry point & router
│   ├── css/                           # Stylesheets
│   │   ├── tailwind.min.css           # Tailwind CSS framework
│   │   ├── homepage.css               # Homepage styles
│   │   ├── admin.css                  # Admin panel styles
│   │   ├── employer.css               # Employer dashboard styles
│   │   └── ...
│   ├── javascript/                    # Client-side scripts
│   │   ├── main.js                    # Core JavaScript
│   │   ├── jquery.min.js              # jQuery library
│   │   ├── notyf.min.js               # Notification library
│   │   └── ...
│   ├── image/                         # User-uploaded images
│   │   ├── avatar/                    # User avatars
│   │   └── logo/                      # Company logos
│   ├── images/                        # Static assets
│   │   ├── bg/                        # Background images
│   │   └── jobs/                      # Job-related images
│   ├── ajax/                          # AJAX endpoints
│   │   ├── jobs_filters.php           # Job filtering
│   │   └── jobs_list.php              # Job listing
│   └── resources/                     # Additional resources
├── vendor/                    # Composer dependencies
│   ├── firebase/php-jwt/              # JWT authentication
│   ├── phpmailer/phpmailer/           # Email library
│   ├── google/apiclient/              # Google API client
│   ├── monolog/monolog/               # Logging
│   └── ...
├── composer.json              # Composer dependencies definition
└── README.md                  # Project documentation
```

## 🗄️ Database Schema

The application uses a MySQL database (`job_poster`) with the following main tables:

### Core Tables

**`users`** - User s and authentication
- User credentials, roles (admin, staff, employer, job_seeker)
- Profile information (name, email, phone, address)
-  status and verification

**`employers`** - Company/Employer profiles
- Company information (name, description, industry)
- Contact details and location
- Logo and branding assets
- Approval status

**`jobs`** - Job postings
- Job details (title, description, requirements)
- Employment terms (salary, type, level)
- Location and category
- Application deadline
- Status (active, closed, pending)

**`job_categories`** - Job classification
- Category names and descriptions
- Hierarchical categorization support

**`job_applications`** - Application tracking
- Links job seekers to job postings
- Application status tracking
- Resume and cover letter storage
- Timestamp tracking

**`feedback`** - User feedback and ratings
- Employer/company ratings
- Feedback comments
- Rating scores

**`staff_actions`** - Audit trail
- Staff activity logging
- Action types and timestamps
- User ability tracking

**`statistics`** - Analytics data
- View counts and engagement metrics
- Application statistics
- Platform usage data

### Relationships
- Users → Employers (1:1 for employer s)
- Employers → Jobs (1:N)
- Jobs → Job Categories (N:1)
- Jobs → Applications (1:N)
- Users → Applications (1:N)
- Employers → Feedback (1:N)

For detailed schema, see `config/create_db.sql`.

## 🚀 Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Edge, etc.)

### Setup Instructions

#### 1. Clone the Repository
```bash
git clone https://github.com/NguyenHoangNam-1410/Job_poster.git
cd Job_poster
```

#### 2. Install to XAMPP
Move or copy the project to your XAMPP's `htdocs` directory:

**Windows:**
```bash
xcopy /E /I Job_poster C:\xampp\htdocs\Worknest
```

**Linux/Mac:**
```bash
cp -r Job_poster /opt/lampp/htdocs/Worknest
# or for MAMP
cp -r Job_poster /Applications/MAMP/htdocs/Worknest
```

#### 3. Install Dependencies
Navigate to the project directory and install Composer dependencies:

```bash
cd C:\xampp\htdocs\Worknest
composer install
```

If you don't have Composer installed, download it from [getcomposer.org](https://getcomposer.org/).

#### 4. Start XAMPP Services
- Open **XAMPP Control Panel**
- Start **Apache** server
- Start **MySQL** database server
- Verify both services are running (green status)

#### 5. Create and Configure Database

**Option A: Using phpMyAdmin (Recommended for beginners)**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "New" in the left sidebar
3. Create a database named `job_poster`
4. Select the database
5. Click "Import" tab
6. Choose file: `config/create_db.sql`
7. Click "Go" to execute
8. (Optional) Import `config/init_db.sql` for sample data

**Option B: Using MySQL Command Line**
```bash
# Login to MySQL
mysql -u root -p

# Create database and import schema
CREATE DATABASE job_poster;
USE job_poster;
SOURCE C:/xampp/htdocs/Worknest/config/create_db.sql;
SOURCE C:/xampp/htdocs/Worknest/config/init_db.sql;
EXIT;
```

#### 6. Configure Database Connection

Edit `config/db.php` with your database credentials:

```php
<?php
$host = 'localhost';
$username = 'root';          // Your MySQL username
$password = '';              // Your MySQL password (empty by default in XAMPP)
$database = 'job_poster';
$port = 3306;                // Default MySQL port
?>
```

#### 7. Set Permissions (Linux/Mac only)

Ensure proper file permissions for uploads:
```bash
chmod -R 755 public/image/
chmod -R 755 public/images/
```

#### 8. Access the Application

Open your web browser and navigate to:

- **Homepage**: http://localhost/Worknest/public/
- **Job Listings**: http://localhost/Worknest/public/?page=jobs
- **Admin/Staff Login**: http://localhost/Worknest/public/?page=login
- **Employer Registration**: http://localhost/Worknest/public/?page=register

## 💡 Usage Guide

### For Job Seekers (Guest Users)

1. **Browse Jobs**
   - Visit the homepage to see featured and recent job postings
   - Navigate to "Jobs" page for complete listings
   - Use search and filter options to find relevant positions

2. **Search & Filter**
   - Filter by job category (IT, Marketing, Sales, etc.)
   - Filter by company
   - Search by keywords in job title or description
   - Sort by date posted, salary, or relevance

3. **View Job Details**
   - Click on any job to view full description
   - See company information and profile
   - Check requirements, benefits, and application deadline
   - View related job opportunities

4. **Apply for Jobs** (Future feature)
   - Register for an account
   - Submit applications with resume
   - Track application status

### For Employers

1. **Company Registration**
   - Register as an employer
   - Complete company profile (name, logo, description, industry)
   - Submit for admin approval
   - Wait for account verification

2. **Post Jobs**
   - Login to employer dashboard
   - Click "Create New Job"
   - Fill in job details:
     - Title and description
     - Requirements and qualifications
     - Salary range and benefits
     - Employment type (full-time, part-time, contract)
     - Job level (entry, mid, senior)
     - Location and category
     - Application deadline
   - Publish job posting

3. **Manage Postings**
   - View all your job postings
   - Edit job details
   - Close or reopen positions
   - Delete old postings
   - Monitor application statistics

4. **Review Applications** (Future feature)
   - View applicant profiles
   - Download resumes
   - Update application status
   - Communicate with candidates

### For Admin

1. **Dashboard Overview**
   - View system-wide statistics
   - Monitor platform activity
   - Track user registrations and job postings

2. **User Management**
   - View all registered users
   - Approve or reject employer registrations
   - Modify user roles and permissions
   - Deactivate or ban problematic accounts

3. **Company Management**
   - Review pending company registrations
   - Approve or reject company profiles
   - Edit company information if needed
   - Monitor company activity

4. **Job Management**
   - View all job postings across platform
   - Moderate job content for policy compliance
   - Remove inappropriate or fraudulent postings
   - View job statistics and trends

5. **Category Management**
   - Create new job categories
   - Edit existing categories
   - Organize category hierarchy
   - Delete unused categories

6. **Reports & Analytics**
   - Generate statistical reports
   - View user engagement metrics
   - Analyze job posting trends
   - Export data for analysis

### For Staff

1. **Content Moderation**
   - Review flagged job postings
   - Verify company information
   - Handle user reports
   - Ensure policy compliance

2. **User Support**
   - Assist with account issues
   - Handle employer verifications
   - Process feedback and complaints

3. **Audit Logging**
   - All staff actions are automatically logged
   - Maintain accountability and transparency
   - Track changes for security purposes

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

Follow the layered architecture approach for consistency and maintainability:

#### Step 1: Define the Model (if needed)
Create a new entity class in `app/models/`:

```php
<?php
// app/models/NewFeature.php
class NewFeature {
    private $id;
    private $name;
    private $description;
    
    // Constructor
    public function __construct($id, $name, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
    
    // Getters and Setters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    // ... additional methods
}
?>
```

#### Step 2: Create the DAO (Data Access Object)
Implement database operations in `app/dao/`:

```php
<?php
// app/dao/NewFeatureDAO.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/NewFeature.php';

class NewFeatureDAO {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT * FROM new_features ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM new_features WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $query = "INSERT INTO new_features (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$data['name'], $data['description']]);
    }
    
    // Add update, delete methods...
}
?>
```

#### Step 3: Create the Service (Business Logic)
Implement business rules in `app/services/`:

```php
<?php
// app/services/NewFeatureService.php
require_once __DIR__ . '/../dao/NewFeatureDAO.php';

class NewFeatureService {
    private $dao;
    
    public function __construct($db) {
        $this->dao = new NewFeatureDAO($db);
    }
    
    public function getAllFeatures() {
        return $this->dao->getAll();
    }
    
    public function getFeatureById($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid ID");
        }
        return $this->dao->getById($id);
    }
    
    public function createFeature($data) {
        // Validation
        if (empty($data['name'])) {
            throw new Exception("Name is required");
        }
        
        // Business logic
        $data['name'] = trim($data['name']);
        $data['description'] = trim($data['description']);
        
        // Call DAO
        return $this->dao->create($data);
    }
}
?>
```

#### Step 4: Create the Controller (HTTP Handler)
Handle requests in `app/controllers/`:

```php
<?php
// app/controllers/NewFeatureController.php
require_once __DIR__ . '/../services/NewFeatureService.php';

class NewFeatureController {
    private $service;
    
    public function __construct($db) {
        $this->service = new NewFeatureService($db);
    }
    
    public function index() {
        try {
            $features = $this->service->getAllFeatures();
            require_once __DIR__ . '/../views/features/index.php';
        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: ?page=error');
        }
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->service->createFeature($_POST);
                header('Location: ?page=features&success=created');
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once __DIR__ . '/../views/features/create.php';
            }
        } else {
            require_once __DIR__ . '/../views/features/create.php';
        }
    }
}
?>
```

#### Step 5: Create Views
Design the UI in `app/views/`:

```php
<!-- app/views/features/index.php -->
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Features List</h1>
    
    <?php foreach ($features as $feature): ?>
        <div class="feature-card">
            <h3><?= htmlspecialchars($feature['name']) ?></h3>
            <p><?= htmlspecialchars($feature['description']) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
```

#### Step 6: Add Routes
Update the router in `public/index.php`:

```php
// public/index.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/NewFeatureController.php';

// Get page parameter
$page = $_GET['page'] ?? 'home';

// Route handling
switch ($page) {
    case 'features':
        $controller = new NewFeatureController($conn);
        $action = $_GET['action'] ?? 'index';
        $controller->$action();
        break;
    
    // ... existing routes
}
```

#### Step 7: Add Database Table
Create migration SQL in `config/`:

```sql
-- config/migrations/add_new_features_table.sql
CREATE TABLE IF NOT EXISTS new_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

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

## 🎨 Technical Highlights

### Security Features
- **SQL Injection Prevention**: Prepared statements with PDO
- **XSS Protection**: Output escaping with `htmlspecialchars()`
- **CSRF Protection**: Token validation for forms
- **Session Security**: Secure session configuration
- **JWT Authentication**: Token-based API authentication
- **Password Hashing**: Bcrypt password encryption
- **Role-Based Access Control**: Permission management by user role

### Performance Optimizations
- **AJAX Loading**: Dynamic content without page reloads
- **Database Indexing**: Optimized queries with proper indexes
- **Lazy Loading**: Images and content loaded on demand
- **Caching**: Session-based caching for frequently accessed data
- **Minified Assets**: Compressed CSS and JavaScript files

### User Experience
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Toast Notifications**: Non-intrusive feedback with Notyf.js
- **Smooth Animations**: CSS transitions and animations
- **Carousel Sliders**: Swiper.js for image galleries and job highlights
- **Form Validation**: Client and server-side validation
- **Search Autocomplete**: Fast job and company search
- **Pagination**: Efficient data browsing

### Code Quality
- **MVC Architecture**: Clean separation of concerns
- **DRY Principle**: Reusable components and functions
- **Error Handling**: Try-catch blocks and error logging
- **Code Comments**: Documented functions and complex logic
- **Consistent Naming**: PSR-compliant naming conventions
- **Composer Autoloading**: PSR-4 autoloading for classes

## 🔌 API Endpoints

### AJAX Endpoints

**Job Filtering**
```
GET /public/ajax/jobs_filters.php
Parameters: category, company, search, sort
Response: Filtered job listings (JSON)
```

**Job Listings**
```
GET /public/ajax/jobs_list.php
Parameters: page, limit, category
Response: Paginated job data (JSON)
```

**Related Jobs**
```
GET /public/ajax/jobs_related.php
Parameters: job_id, category_id
Response: Similar job recommendations (JSON)
```

### Future API Expansion
- RESTful API for mobile applications
- OAuth integration for social login
- Webhook support for third-party integrations
- GraphQL endpoint for flexible queries

## 🧪 Testing

### Manual Testing Checklist

**Authentication:**
- [ ] User registration with validation
- [ ] Login with correct credentials
- [ ] Login with incorrect credentials
- [ ] Session persistence
- [ ] Logout functionality
- [ ] Password reset (if implemented)

**Job Management:**
- [ ] Create new job posting
- [ ] Edit existing job
- [ ] Delete job
- [ ] View job details
- [ ] Search and filter jobs
- [ ] Job category assignment

**User Roles:**
- [ ] Admin can access admin panel
- [ ] Staff can moderate content
- [ ] Employer can manage own jobs
- [ ] Unauthorized access blocked

**Forms:**
- [ ] Client-side validation
- [ ] Server-side validation
- [ ] Error message display
- [ ] Success notifications

**Responsive Design:**
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### Automated Testing (Future Implementation)
- PHPUnit for unit tests
- Selenium for browser automation
- PHPStan for static analysis
- PHPCS for code standards

## 🐛 Troubleshooting

### Common Issues

**Database Connection Failed**
```
Solution:
1. Verify MySQL is running in XAMPP
2. Check credentials in config/db.php
3. Ensure database 'job_poster' exists
4. Test connection: mysql -u root -p
```

**404 Page Not Found**
```
Solution:
1. Check project is in correct directory (htdocs/Worknest/)
2. Verify Apache is running
3. Check .htaccess file exists
4. Use correct URL: http://localhost/Worknest/public/
```

**Composer Dependencies Missing**
```
Solution:
1. Install Composer from getcomposer.org
2. Run: composer install
3. Check vendor/ directory is created
4. Verify autoload.php exists
```

**Images Not Uploading**
```
Solution:
1. Check folder permissions (755 on Linux/Mac)
2. Verify upload_max_filesize in php.ini
3. Ensure directory paths are correct
4. Check file extension validation
```

**Session Not Persisting**
```
Solution:
1. Check session.save_path in php.ini
2. Verify cookies are enabled in browser
3. Clear browser cache and cookies
4. Check session_start() is called
```

**Blank White Page**
```
Solution:
1. Enable error reporting in php.ini:
   - display_errors = On
   - error_reporting = E_ALL
2. Check Apache error logs
3. Verify all required files exist
4. Check for PHP syntax errors
```

### Getting Help

- **Documentation**: Check this README and code comments
- **Error Logs**: View XAMPP logs in xampp/apache/logs/
- **PHP Errors**: Enable error display for debugging
- **Database Issues**: Check phpMyAdmin for queries
- **GitHub Issues**: Report bugs at repository issues page

## 🚀 Deployment

### Production Deployment Checklist

**Security:**
- [ ] Change default credentials
- [ ] Update database passwords
- [ ] Enable HTTPS/SSL
- [ ] Disable error display
- [ ] Enable error logging only
- [ ] Configure secure session settings
- [ ] Update JWT secret keys
- [ ] Set secure file permissions (644 for files, 755 for directories)
- [ ] Remove or protect phpmyadmin access

**Configuration:**
- [ ] Update database connection for production server
- [ ] Set production base URL
- [ ] Configure email SMTP settings
- [ ] Update API keys and credentials
- [ ] Set correct timezone
- [ ] Configure backup schedule

**Performance:**
- [ ] Enable OPcache for PHP
- [ ] Minify CSS and JavaScript
- [ ] Optimize images
- [ ] Enable Gzip compression
- [ ] Configure CDN for static assets
- [ ] Set up database query caching
- [ ] Implement Redis/Memcached

**Monitoring:**
- [ ] Set up error monitoring (e.g., Sentry)
- [ ] Configure uptime monitoring
- [ ] Enable performance monitoring
- [ ] Set up automated backups
- [ ] Configure log rotation

### Recommended Hosting
- **Shared Hosting**: cPanel with PHP 7.4+ and MySQL
- **VPS**: DigitalOcean, Linode, Vultr
- **Cloud**: AWS EC2, Google Cloud, Azure
- **Managed**: Cloudways, Kinsta, WP Engine

### Environment Variables
Create a `.env` file for sensitive configuration:

```env
DB_HOST=localhost
DB_USER=your_username
DB_PASS=your_password
DB_NAME=job_poster

MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USER=noreply@worknest.com
MAIL_PASS=your_email_password

JWT_SECRET=your_secret_key_here
APP_ENV=production
APP_DEBUG=false
```

## 🔮 Future Enhancements

### Planned Features
- [ ] Advanced job search with AI-powered recommendations
- [ ] Real-time chat between employers and candidates
- [ ] Video interview scheduling and integration
- [ ] Resume builder and CV parser
- [ ] Job alert notifications via email/SMS
- [ ] Social media integration (LinkedIn, Facebook)
- [ ] Payment gateway for premium job postings
- [ ] Multi-language support (i18n)
- [ ] Mobile applications (iOS/Android)
- [ ] Analytics dashboard with charts
- [ ] Job seeker profile and portfolio
- [ ] Skill assessment tests
- [ ] Background verification integration
- [ ] Applicant tracking system (ATS)
- [ ] Employer branding pages
- [ ] Job fair/event management

### Technical Improvements
- [ ] RESTful API development
- [ ] GraphQL implementation
- [ ] Microservices architecture
- [ ] Docker containerization
- [ ] CI/CD pipeline setup
- [ ] Automated testing suite
- [ ] Progressive Web App (PWA)
- [ ] Server-side rendering
- [ ] Redis caching layer
- [ ] Elasticsearch integration
- [ ] Message queue (RabbitMQ/Kafka)

## 📚 Learning Resources

### PHP & MySQL
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PDO Tutorial](https://phpdelusions.net/pdo)
- [PHP The Right Way](https://phptherightway.com/)

### Design Patterns
- [MVC Pattern Explained](https://www.tutorialspoint.com/mvc_framework/index.htm)
- [DAO Pattern](https://www.oracle.com/java/technologies/dataaccessobject.html)
- [Service Layer Pattern](https://martinfowler.com/eaaCatalog/serviceLayer.html)

### Frontend
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Swiper.js](https://swiperjs.com/)
- [Notyf.js](https://carlosroso.com/notyf/)

### Tools
- [Composer](https://getcomposer.org/doc/)
- [XAMPP](https://www.apachefriends.org/docs/)
- [Git](https://git-scm.com/doc)

## 📄 License

This project is developed for educational purposes and portfolio demonstration.

**License:** MIT License (or specify your chosen license)

**Free to use for:**
- Learning and education
- Personal projects
- Portfolio demonstrations
- Non-commercial applications

**Attribution Required:**
If you use this project as a base, please provide credit to the original author.

## 👥 Contributors

### Development Team
- **Lead Developer**: [NguyenHoangNam-1410](https://github.com/NguyenHoangNam-1410)

### How to Contribute

We welcome contributions! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/AmazingFeature`
3. **Commit your changes**: `git commit -m 'Add some AmazingFeature'`
4. **Push to the branch**: `git push origin feature/AmazingFeature`
5. **Open a Pull Request**

**Contribution Guidelines:**
- Follow existing code style and conventions
- Write clear commit messages
- Add comments for complex logic
- Test your changes thoroughly
- Update documentation as needed

## 📞 Contact & Support

- **GitHub Repository**: [Job_poster](https://github.com/NguyenHoangNam-1410/Job_poster)
- **Issue Tracker**: [Report Bugs](https://github.com/NguyenHoangNam-1410/Job_poster/issues)
- **Email**: [Contact Developer](mailto:your-email@example.com)

## 🙏 Acknowledgments

- **Tailwind CSS** - Utility-first CSS framework
- **PHPMailer** - Email sending library
- **Firebase JWT** - Authentication tokens
- **Swiper.js** - Modern slider library
- **Notyf** - Notification library
- **Font Awesome** - Icon library
- **Unsplash** - Stock photos for placeholders

## 📊 Project Stats

- **Lines of Code**: ~15,000+
- **Files**: 100+
- **Database Tables**: 8+
- **API Endpoints**: 10+
- **Supported Roles**: 4 (Admin, Staff, Employer, Job Seeker)
- **Dependencies**: 20+ (via Composer)

---

**Built with ❤️ using PHP, MySQL, and modern web technologies**

**⭐ If you find this project helpful, please consider giving it a star on GitHub!**

---

*Last Updated: November 22, 2025*  
*Version: 1.0.0*  
*Status: Active Development*
