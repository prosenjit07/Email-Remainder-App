# Laravel 11 Todo Reminder Application

A comprehensive Laravel 11 application that allows users to manage a Todo List with advanced email features and background job processing.

## 🚀 Features

### Todo List Management (CRUD)
- ✅ Create, Read, Update, Delete todos
- ✅ Each todo includes: Title, Description, DateTime (Reminder time)
- ✅ Modern responsive UI with Bootstrap 5
- ✅ Real-time updates with AJAX

### Email Reminder System
- ✅ Sends email reminders 10 minutes before due time
- ✅ Uses Laravel Queues for background processing
- ✅ Fetches data from external API (jsonplaceholder.typicode.com/posts)
- ✅ Attaches CSV file with API data to emails
- ✅ Laravel Scheduler runs every minute to check for pending reminders

### Email Logging System
- ✅ Comprehensive email logging with status tracking
- ✅ Logs recipient, subject, content, status (Success/Failed), timestamp
- ✅ Beautiful dashboard to view email logs
- ✅ Statistics and filtering capabilities

### Todo Tag After Email Sent
- ✅ Automatically marks todos with `email_notification_sent = true`
- ✅ Visual indicators in UI for sent reminders
- ✅ Prevents duplicate email sending

## 🛠️ Technology Stack

- **Laravel 11** - PHP Framework
- **MySQL** - Database
- **Laravel Queues** - Background job processing
- **Laravel Scheduler** - Automated task scheduling
- **Bootstrap 5** - Frontend UI
- **Font Awesome** - Icons
- **Guzzle HTTP** - API requests

## 📋 Requirements

- PHP 8.2+
- Composer
- MySQL
- Laravel 11

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-todo-reminder
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_reminder_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

## 🔧 Configuration

### Email Configuration
Update your `.env` file with email settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration
The application uses database queues by default. Make sure to run:
```bash
php artisan queue:table
php artisan migrate
```

## 🚀 Running the Application

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Start the queue worker** (in a separate terminal)
   ```bash
   php artisan queue:work
   ```

3. **Start the scheduler** (in another terminal)
   ```bash
   php artisan schedule:work
   ```

4. **Visit the application**
   - Main Todo List: `http://localhost:8000/todos`
   - Email Logs: `http://localhost:8000/email-logs`
   - API Endpoints: `http://localhost:8000/api/todos`

## 📚 API Endpoints

### Todos
- `GET /api/todos` - List all todos
- `POST /api/todos` - Create a new todo
- `GET /api/todos/{id}` - Get a specific todo
- `PUT /api/todos/{id}` - Update a todo
- `DELETE /api/todos/{id}` - Delete a todo

### Email Logs
- `GET /api/email-logs` - List all email logs
- `GET /api/email-logs/{id}` - Get a specific email log

## 🧪 Testing

### Manual Testing
1. **Create a todo** with a due time 10+ minutes in the future
2. **Wait for the reminder** or use the test command:
   ```bash
   php artisan todos:test-reminder
   ```
3. **Check email logs** at `/email-logs`
4. **Verify todo is marked** as notification sent

### Test Commands
```bash
# Test reminder for all pending todos
php artisan todos:test-reminder

# Test reminder for specific todo
php artisan todos:test-reminder 1

# Check for pending reminders
php artisan todos:check-reminders
```

## 📁 Project Structure

```
laravel-todo-reminder/
├── app/
│   ├── Console/Commands/
│   │   ├── CheckTodoReminders.php
│   │   └── TestEmailReminder.php
│   ├── Http/Controllers/
│   │   ├── TodoController.php
│   │   └── EmailLogController.php
│   ├── Jobs/
│   │   └── SendTodoReminderEmail.php
│   └── Models/
│       ├── Todo.php
│       └── EmailLog.php
├── database/
│   ├── migrations/
│   │   ├── create_todos_table.php
│   │   └── create_email_logs_table.php
│   └── seeders/
│       └── TodoSeeder.php
├── resources/views/
│   ├── todos/
│   │   └── index.blade.php
│   └── email-logs/
│       └── index.blade.php
└── routes/
    ├── api.php
    └── web.php
```

## 🔄 Background Jobs

### Email Reminder Job
- **File**: `app/Jobs/SendTodoReminderEmail.php`
- **Purpose**: Sends reminder emails with CSV attachments
- **Features**:
  - Fetches data from external API
  - Generates CSV with API data
  - Sends email with attachment
  - Logs email status
  - Marks todo as notified

### Scheduler Command
- **File**: `app/Console/Commands/CheckTodoReminders.php`
- **Schedule**: Runs every minute
- **Purpose**: Checks for todos needing reminders and queues jobs

## 📊 Email Logging System

### Features
- ✅ Logs every email sent from the application
- ✅ Tracks success/failure status
- ✅ Stores recipient, subject, content, timestamp
- ✅ Beautiful dashboard with statistics
- ✅ Detailed view of individual emails

### Database Schema
```sql
email_logs:
- id (primary key)
- to_email (string)
- subject (string)
- body (longtext, nullable)
- status (enum: success/failed)
- sent_at (timestamp)
- created_at, updated_at
```

## 🏷️ Todo Tagging System

### Features
- ✅ Automatic tagging after successful email delivery
- ✅ Visual indicators in UI
- ✅ Prevents duplicate email sending
- ✅ Database flag: `email_notification_sent`

### Database Schema
```sql
todos:
- id (primary key)
- title (string)
- description (text, nullable)
- due_datetime (datetime)
- email_notification_sent (boolean, default: false)
- created_at, updated_at
```

## 🎯 Key Features Implementation

### 1. Route Model Binding
All Todo operations use Laravel's Route Model Binding for automatic model resolution.

### 2. Laravel Queues
Email sending is handled asynchronously using Laravel Queues for better performance.

### 3. Laravel Scheduler
Automated checking for pending reminders every minute using Laravel's task scheduler.

### 4. API Integration
Fetches data from `jsonplaceholder.typicode.com/posts` and creates CSV attachments.

### 5. Modern UI
Responsive design with Bootstrap 5, Font Awesome icons, and AJAX interactions.

## 🚨 Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check email configuration in `.env`
   - Ensure queue worker is running
   - Check email logs for errors

2. **Reminders not triggering**
   - Ensure scheduler is running: `php artisan schedule:work`
   - Check for pending todos: `php artisan todos:check-reminders`

3. **Database connection issues**
   - Verify database credentials in `.env`
   - Run migrations: `php artisan migrate`

### Debug Commands
```bash
# Check queue status
php artisan queue:work --verbose

# Check scheduled tasks
php artisan schedule:list

# Test email configuration
php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📞 Support

For support, email prosenjitbiswas983@gmail.com or create an issue in the repository.

---

**Built with ❤️ using Laravel 11**