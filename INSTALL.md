# Helin Latam CMS

Enterprise Content Management System for Helin Latam

## 🚀 Installation

### Prerequisites

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0.0
- NPM >= 8.0.0
- MySQL >= 8.0 or MariaDB >= 10.3
- Redis (optional, for caching and queues)

### 1. Clone the Repository

```bash
git clone <repository-url>
cd helin
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helin
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database

```bash
php artisan db:seed
```

### 8. Link Storage

```bash
php artisan storage:link
```

### 9. Compile Assets

```bash
npm run build
```

### 10. Start Development Server

```bash
# For PHP development server
php artisan serve

# For Vite development server (in a separate terminal)
npm run dev
```

## 📋 Available Commands

### PHP Commands

```bash
# Development
php artisan serve                    # Start development server
php artisan migrate                   # Run database migrations
php artisan db:seed                    # Seed database
php artisan tinker                    # Open Laravel Tinker
php artisan queue:work                 # Process queue jobs
php artisan schedule:run              # Run scheduled tasks

# Cache
php artisan config:cache              # Cache configuration
php artisan route:cache               # Cache routes
php artisan view:cache                # Cache views

# Testing
php artisan test                       # Run all tests
php artisan test --coverage          # Run tests with coverage
php artisan test --filter UserTest   # Run specific test

# Debugging
php artisan route:list               # List all routes
php artisan config:show              # Show configuration
php artisan about                    # Show environment info
```

### NPM Commands

```bash
# Development
npm run dev                         # Start Vite dev server
npm run build                       # Build for production
npm run preview                     # Preview production build

# Code Quality
npm run format                       # Format code with Prettier
npm run lint                         # Run ESLint
npm run lint:css                     # Run Stylelint
npm run type-check                  # Run TypeScript checks

# Analysis
npm run analyze                      # Analyze bundle size
npm run clean                        # Clean build files
```

## 🏗️ Architecture

### Directory Structure

```
helin/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # API Controllers
│   │   ├── Requests/             # Form Request Validation
│   │   ├── Middleware/           # Custom Middleware
│   │   └── Resources/            # API Resources
│   ├── Models/                   # Eloquent Models
│   ├── Services/                # Business Logic Services
│   ├── Utils/                   # Helper Classes
│   └── Providers/               # Service Providers
├── database/
│   ├── migrations/              # Database Migrations
│   ├── seeders/                 # Database Seeders
│   └── factories/               # Model Factories
├── resources/
│   ├── views/                   # Blade Templates
│   │   ├── components/          # Reusable Components
│   │   └── layouts/             # Page Layouts
│   ├── css/                     # CSS Stylesheets
│   └── js/                      # JavaScript Files
├── storage/
│   ├── app/                     # Application Files
│   └── public/                  # Public Assets
└── public/                       # Public Web Root
```

### Key Features

#### 🔐 Authentication & Authorization
- Laravel Sanctum for API authentication
- Spatie Laravel Permission for role-based access control
- JWT token support
- Multi-factor authentication ready

#### 📝 Content Management
- Dynamic content types (products, blogs, categories, etc.)
- Media management with Spatie Media Library
- SEO optimization with meta tags and sitemaps
- Multi-language support ready

#### 🎨 User Interface
- Modern responsive design with Tailwind CSS
- Interactive components with Alpine.js
- Real-time updates with Livewire
- Admin panel with Filament

#### 📊 Data Management
- Advanced search and filtering
- Import/export functionality (Excel, PDF, CSV)
- Activity logging with Spatie Activitylog
- Backup and restore capabilities

#### 🔧 Development Tools
- Comprehensive testing suite (PHPUnit, Pest)
- Code quality tools (ESLint, Prettier, PHPStan)
- Debugging tools (Laravel Telescope, Debugbar)
- Performance monitoring

## 🎯 Core Modules

### 1. **Products & Categories**
- Product catalog with variants
- Hierarchical categories
- Advanced search and filtering
- Inventory management
- Product attributes system

### 2. **Blog & Content**
- Blog posts with categories
- Rich text editing with Trix
- Media galleries
- Comment system
- SEO optimization

### 3. **Users & Roles**
- User management
- Role-based permissions
- Activity tracking
- Profile management
- Team collaboration

### 4. **Testimonials**
- Customer testimonials
- Rating system
- Social proof features
- Video testimonials
- Company information

### 5. **Quotes & Invoicing**
- Quote management
- Invoice generation
- Customer tracking
- Payment processing
- Reporting dashboard

### 6. **Settings & Configuration**
- System settings
- Email configuration
- Theme customization
- Backup management
- API configuration

## 🔧 Configuration

### Environment Variables

Key environment variables in `.env`:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helin
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Application
APP_NAME="Helin CMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@helin.com"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Cache
CACHE_DRIVER=file
CACHE_PREFIX=helin_
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/

# Queue
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

# Telescope
TELESCOPE_ENABLED=false
TELESCOPE_ENABLED=false

# Social Login
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=

# Pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
```

### Package Configuration

#### Composer Packages

The project uses these key Laravel packages:

- **Authentication**: `laravel/sanctum`, `tymon/jwt-auth`
- **Admin Panel**: `filament/filament`
- **Media Management**: `spatie/laravel-medialibrary`
- **Permissions**: `spatie/laravel-permission`
- **Activity Logging**: `spatie/laravel-activitylog`
- **Backups**: `spatie/laravel-backup`
- **Excel Export**: `maatwebsite/excel`
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **Social Login**: `laravel/socialite`
- **Real-time**: `laravel/reverb`
- **Search**: `laravel/scout`
- **Monitoring**: `laravel/telescope`, `laravel/horizon`

#### NPM Packages

Frontend development uses:

- **Build Tool**: Vite
- **CSS Framework**: Tailwind CSS
- **JavaScript Framework**: Alpine.js
- **UI Components**: Custom components with Lucide icons
- **Charts**: Chart.js
- **Date Handling**: date-fns
- **Form Validation**: Custom validation
- **File Upload**: Enhanced file handling
- **Notifications**: Toast notifications
- **Search**: Fuse.js for fuzzy search

## 🚀 Deployment

### Production Setup

1. **Environment Configuration**

```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Configure production database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=helin_production
DB_USERNAME=your-production-user
DB_PASSWORD=your-production-password

# Configure cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Configure file storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-s3-bucket
```

2. **Optimize Application**

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
php artisan optimize
```

3. **Build Assets**

```bash
npm run build
```

4. **Set Up Queue Worker**

```bash
# Install supervisor if not available
sudo apt-get install supervisor

# Configure supervisor for queue worker
sudo nano /etc/supervisor/conf.d/helin-worker.conf
```

5. **Set Up Scheduled Tasks**

```bash
# Add to crontab
* * * * * cd /path/to/helin && php artisan schedule:run >> /dev/null 2>&1
```

### Docker Deployment

Docker configuration is available for containerized deployment:

```bash
# Build Docker image
docker build -t helin-cms .

# Run container
docker run -d -p 8000:8000 helin-cms
```

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter UserTest

# Run Pest tests
./vendor/bin/pest
```

### Test Coverage

The project maintains high test coverage:

- **Unit Tests**: Models, Services, Utils
- **Feature Tests**: API endpoints, user flows
- **Browser Tests**: Critical user journeys
- **Integration Tests**: Third-party integrations

## 📊 Performance

### Optimization Techniques

1. **Database Optimization**
- Proper indexing on frequently queried columns
- Query optimization with eager loading
- Database connection pooling

2. **Caching Strategy**
- Redis for session and application cache
- HTTP caching for static assets
- Query result caching

3. **Asset Optimization**
- Vite build optimization
- Image compression
- CSS and JS minification
- CDN deployment

4. **Code Optimization**
- Lazy loading of components
- Efficient database queries
- Memory usage optimization

## 🔒 Security

### Security Measures

1. **Authentication**
- Secure password hashing
- Rate limiting on login attempts
- Session management
- CSRF protection

2. **Authorization**
- Role-based access control
- Permission checking
- Resource ownership validation

3. **Data Protection**
- Input validation and sanitization
- XSS protection
- SQL injection prevention
- File upload security

4. **Infrastructure**
- HTTPS enforcement
- Security headers
- Firewall configuration
- Regular security updates

## 📚 Documentation

### API Documentation

API documentation is available at `/docs/api` when running the application.

### Code Documentation

All PHP classes and methods are documented with JSDoc comments:

```php
/**
 * Example service class
 *
 * @package App\Services
 * @author  Helin Latam Development Team
 * @version 1.0.0
 */
class ExampleService
{
    /**
     * Example method
     *
     * @param string $param1 The first parameter
     * @param int $param2 The second parameter
     * @return array<string> The result
     */
    public function exampleMethod(string $param1, int $param2): array
    {
        // Implementation
    }
}
```

## 🤝 Contributing

### Development Workflow

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Ensure code quality
6. Submit a pull request

### Code Quality Standards

- Follow PSR-12 coding standards
- Use strict types where possible
- Add comprehensive JSDoc comments
- Maintain test coverage above 80%
- Use ESLint and Prettier for formatting

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/your-feature-name

# Make changes
# Add and commit files

# Push to remote
git push origin feature/your-feature-name

# Create pull request
```

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:

- **Email**: support@helinlatam.com
- **Documentation**: [Project Wiki](https://github.com/helin-latam/cms/wiki)
- **Issues**: [GitHub Issues](https://github.com/helin-latam/cms/issues)

## 🎉 Credits

Built with ❤️ by the Helin Latam Development Team.

---

## 📈 Roadmap

### Version 1.1 (Q1 2024)
- [ ] API rate limiting
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Mobile app API

### Version 1.2 (Q2 2024)
- [ ] Advanced workflow automation
- [ ] Email campaign system
- [ ] Advanced reporting
- [ ] API versioning

### Version 2.0 (Q3 2024)
- [ ] Headless CMS mode
- [ ] GraphQL API
- [ ] Microservices architecture
- [ ] Advanced AI features

---

**Built with Laravel 11, Tailwind CSS, Alpine.js, and ❤️**
