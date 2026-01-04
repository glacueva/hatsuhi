# Hatsuhi - Personal Finance App

![Hatsuhi Logo](public/assets/Hatsuhi.png)

**Hatsuhi** (初日) - *"First sunrise of the year"* - is a self-hosted personal finance application designed for mindful financial awareness. In a world of fast-paced spending and financial automation, Hatsuhi encourages you to pause, reflect, and regain control through intentional financial practice.

## 🌅 What is Hatsuhi?

Hatsuhi is not just another finance tracker. It's a tool for developing **financial mindfulness** through regular reflection on your spending patterns. Unlike cloud-based services, Hatsuhi runs on your own server, keeping your financial data completely private and under your control.

### ✨ Key Features

- **Self-Hosted & Private**: Your data stays on your server. No third-party access, no data selling
- **Mindful Financial Practice**: Weekly reflection, monthly analysis, yearly reset cycles
- **Complete Dashboard**: Income vs expenses, budget tracking, category breakdowns
- **Yearly Expectations**: Set and track annual budgets with monthly breakdowns
- **Custom Categories**: Flexible category system for income, expenses, extras, and unexpected costs
- **No AI, No Integrations**: You maintain complete analytical control
- **Built with Modern Tech**: Laravel + Filament v4 for robust backend with elegant admin interface

### 🚫 What Hatsuhi is NOT

- ❌ **Not a banking application** - You manually input data from external sources
- ❌ **No third-party integrations** - Your data remains disconnected from external systems
- ❌ **Not an AI-powered tool** - No data sent to external servers for analysis
- ❌ **Not an investment platform** - Focuses solely on spending awareness and budgeting

## 🎯 The Philosophy

In today's fast-paced world, we've lost touch with our finances. Hatsuhi helps you develop:

- **Meditation**: Weekly reflection on spending patterns creates mindfulness around money
- **Projection**: Set and track financial goals with yearly expectations
- **Detection**: Identify spending patterns, leaks, and optimization opportunities
- **Own Saving Goals**: Personal targets based on your actual income and lifestyle

The name "Hatsuhi" (初日) refers to the first morning of the year - a time for new beginnings and fresh intentions, just as each new year brings new financial expectations.

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x
- **Admin Panel**: Filament v4
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Breeze

## 📋 Prerequisites

Before installation, ensure you have:

- PHP 8.2 or higher
- Composer 2.5 or higher
- Node.js 18 or higher with npm
- Database server (MySQL 8.0+, PostgreSQL 15+, or SQLite 3.35+)
- Web server (Apache, Nginx, or built-in PHP server)

## 🚀 Installation Guide

### Option 1: All-in-One Script (Linux/macOS)

```bash
# Clone the repository
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi

# Run the installation script
chmod +x install.sh
./install.sh
```

The installation script will guide you through:
1. Environment configuration
2. Dependencies installation
3. Database setup
4. Initial data seeding
5. Permission configuration

### Option 2: Manual Installation

#### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi
```

#### Step 2: Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

#### Step 3: Install JavaScript Dependencies

```bash
npm install
npm run build
```

#### Step 4: Configure Environment

```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```env
APP_NAME=Hatsuhi
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hatsuhi
DB_USERNAME=root
DB_PASSWORD=

# For production, set to true
SESSION_SECURE_COOKIE=true
```

#### Step 5: Generate Application Key

```bash
php artisan key:generate
```

#### Step 6: Run Migrations and Seeders

```bash
php artisan migrate --seed
```

#### Step 7: Set Storage Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux only, adjust user as needed
```

#### Step 8: Link Storage (for uploaded files)

```bash
php artisan storage:link
```

#### Step 9: Configure Web Server

##### Using PHP Built-in Server (Development)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

##### Using Apache

Create a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName hatsuhi.local
    DocumentRoot /path/to/hatsuhi/public
    
    <Directory /path/to/hatsuhi/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/hatsuhi_error.log
    CustomLog ${APACHE_LOG_DIR}/hatsuhi_access.log combined
</VirtualHost>
```

##### Using Nginx

```nginx
server {
    listen 80;
    server_name hatsuhi.local;
    root /path/to/hatsuhi/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🪟 Windows Installation (Using Laragon)

Laragon provides a complete web development environment for Windows.

### Step 1: Install Laragon

1. Download Laragon from [laragon.org](https://laragon.org/download/)
2. Run the installer (choose "Full" version)
3. Start Laragon after installation

### Step 2: Configure Laragon

1. Right-click Laragon icon in system tray → **Apache → Version** → Choose **Apache 2.4**
2. Right-click Laragon icon → **PHP → Version** → Choose **PHP 8.2+**
3. Right-click Laragon icon → **Nginx → MySQL** → Ensure MySQL is selected

### Step 3: Install Hatsuhi

1. Open Laragon terminal:
   - Right-click Laragon icon → **Terminal**
   - Or press `Win + Alt + T`

2. Clone and setup Hatsuhi:

```bash
# Navigate to Laragon's www directory
cd c:/laragon/www

# Clone the repository
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi

# Install dependencies
composer install
npm install
npm run build

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file (use Laragon's MySQL)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hatsuhi
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seeders
php artisan migrate --seed

# Create virtual host in Laragon
# Right-click Laragon icon → Quick add → Virtual Host
# Name: hatsuhi
# Path: C:/laragon/www/hatsuhi/public
```

### Step 4: Access Hatsuhi

1. Restart Laragon
2. Open browser and navigate to: `http://hatsuhi.test`

## 🍎 macOS Installation

### Option A: Using Homebrew

```bash
# Install dependencies
brew install php@8.2 composer node mysql

# Start services
brew services start mysql

# Clone and setup (same as Linux instructions)
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate

# Configure .env with MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hatsuhi
# DB_USERNAME=root
# DB_PASSWORD=

php artisan migrate --seed
php artisan serve
```

### Option B: Using Laravel Herd (Simplest)

1. Install [Laravel Herd](https://herd.laravel.com)
2. Clone Hatsuhi into Herd's sites directory
3. Herd will automatically detect and configure the site

## 🐳 Docker Installation (All Platforms)

For containerized deployment:

```bash
# Clone repository
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi

# Copy docker environment
cp .env.docker.example .env

# Build and start containers
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build

# Generate key and migrate
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed

# Access at http://localhost:8000
```

## 🔧 Configuration

### Admin Access

After seeding, you'll have two default users:

1. **Admin User**
   - Email: `admin@hatsuhi.app`
   - Password: `password`

2. **Regular User**
   - Email: `user@hatsuhi.app`
   - Password: `password`

**Important**: Change these passwords immediately after first login!

### Email Configuration

For production, configure mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hatsuhi.app
MAIL_FROM_NAME="Hatsuhi"
```

### Currency Configuration

Hatsuhi comes pre-seeded with multiple currencies. To add more:

```bash
php artisan tinker
>>> \App\Models\Currency::create(['name' => 'Swedish Krona', 'short' => 'SEK', 'symbol' => 'kr'])
```

## 📊 Initial Setup

After installation:

1. **Log in** with admin credentials
2. **Configure your profile**:
   - Set your preferred currency
   - Update timezone if needed
3. **Set up Expectations** for the current year
4. **Create custom categories** that match your spending habits
5. **Start recording movements** (transactions)

## 🔄 Weekly Workflow

The recommended Hatsuhi workflow:

1. **Record Movements** - Add transactions throughout the week
2. **Sunday Evening Check-in** - Spend 15-20 minutes reviewing:
   - Dashboard overview
   - Budget vs actual spending
   - Category breakdowns
3. **Monthly Reflection** - Compare expectations vs reality
4. **Yearly Reset** - Set new expectations each new year

## 🚀 Production Deployment

### Security Checklist

1. **Change default passwords** immediately
2. **Set `APP_DEBUG=false`** in `.env`
3. **Use HTTPS** with valid SSL certificate
4. **Set secure session cookies**:
   ```env
   SESSION_SECURE_COOKIE=true
   ```
5. **Regular backups** of database and storage
6. **Keep software updated** (PHP, Laravel, dependencies)

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o

# Queue setup for better performance
php artisan queue:work
```

### Backup Script

Create a backup script at `/scripts/backup.sh`:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/hatsuhi"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u root -p hatsuhi > $BACKUP_DIR/hatsuhi_db_$DATE.sql

# Backup storage
tar -czf $BACKUP_DIR/hatsuhi_storage_$DATE.tar.gz storage/

# Keep only last 30 days of backups
find $BACKUP_DIR -type f -mtime +30 -delete
```

Schedule with cron:
```bash
0 2 * * * /scripts/backup.sh
```

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Development Setup

```bash
# Clone your fork
git clone https://github.com/yourusername/hatsuhi.git
cd hatsuhi

# Install dependencies
composer install
npm install

# Set up development environment
cp .env.example .env.testing
php artisan key:generate

# Run tests
php artisan test
```

## 🐛 Troubleshooting

### Common Issues

**Issue**: "Class not found" after composer install
**Solution**: Run `composer dump-autoload`

**Issue**: 500 error after installation
**Solution**: Check storage permissions and .env configuration

**Issue**: Migration errors
**Solution**: Clear cache: `php artisan cache:clear && php artisan config:clear`

**Issue**: Assets not loading
**Solution**: Run `npm run build` and check public directory permissions

### Logs

Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

### Debug Mode

For troubleshooting, temporarily enable debug mode:
```env
APP_DEBUG=true
```

## 📚 Documentation

- [User Guide](docs/USER_GUIDE.md) - Complete guide to using Hatsuhi
- [API Documentation](docs/API.md) - REST API endpoints (if applicable)
- [Admin Guide](docs/ADMIN.md) - System administration
- [Developer Guide](docs/DEVELOPER.md) - Contributing and development

## 📄 License

Hatsuhi is open-source software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

- **Laravel** - The PHP framework that powers Hatsuhi
- **Filament** - For the elegant admin interface
- **Chiyo-ni** - Whose haiku inspired the name and philosophy
- **All Contributors** - Who help make Hatsuhi better

## 🌐 Community & Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/yourusername/hatsuhi/issues)
- **Discord**: [Join our community](https://discord.gg/your-invite-link)
- **Documentation**: [Read the docs](https://docs.hatsuhi.app)

---

*"Cranes at play, meet in the clouds, first sunrise"* - Chiyo-ni (1703-1775)

May your financial journey find harmony with your intentions at each new beginning. 