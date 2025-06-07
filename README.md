# Enterprise Management System

A robust enterprise management system built with Laravel and Vue.js, designed to streamline task management, project collaboration, and departmental organization within your company.

## 🚀 Features

- **User Management**
  - Role-based access control
  - Department-based organization
  - User profiles and authentication

- **Project Management**
  - Create and manage multiple projects
  - Project member assignments
  - Real-time project chat functionality
  - Project progress tracking

- **Task Management**
  - Create, assign, and track tasks
  - Task status history
  - Task comments and discussions
  - Task reminders and notifications
  - Support for repetitive tasks
  - Daily task reporting system

- **Department Organization**
  - Department-based team structure
  - Inter-departmental collaboration
  - Department-specific project management

- **Reporting System**
  - Daily activity reports
  - Task completion analytics
  - Project progress tracking
  - Custom report generation

- **Communication Tools**
  - Email reminders
  - In-app notifications
  - Project chat messaging
  - Task comments and discussions

## 🛠️ Technology Stack

- **Backend**
  - Laravel (PHP Framework)
  - MySQL Database
  - Laravel Livewire for dynamic interfaces

- **Frontend**
  - Vue.js
  - Tailwind CSS
  - PostCSS

- **Development Tools**
  - Vite for asset bundling
  - PHPUnit for testing
  - Composer for PHP dependencies
  - NPM for JavaScript dependencies

## 📋 Prerequisites

- PHP >= 8.0
- Composer
- Node.js & NPM
- MySQL

## 🔧 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/enterprise-management.git
   cd enterprise-management
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Create environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your database in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

7. Run database migrations:
   ```bash
   php artisan migrate
   ```

8. Build assets:
   ```bash
   npm run dev
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## 📁 Project Structure

- `/app` - Core application logic
  - `/Models` - Database models and relationships
  - `/Http/Controllers` - Request handlers
  - `/Services` - Business logic services
  - `/Events` - Event classes
  - `/Mail` - Email templates and logic
  - `/Providers` - Service providers

- `/resources` - Frontend assets
  - `/js` - Vue.js components and logic
  - `/css` - Stylesheets
  - `/views` - Blade templates

- `/database` - Database migrations and seeders
- `/routes` - Application routes
- `/tests` - Application tests
- `/config` - Configuration files

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- Alan Horvitz - Initial work
- Zakaria Ait Ali - Core Developer 

## 🙏 Acknowledgments

- Laravel Team
- Vue.js Team
- All contributors who have helped this project grow
