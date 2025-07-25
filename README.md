# Laravel 11 Todo Reminder Application

A comprehensive Laravel 11 application that allows users to manage a Todo List with advanced email features and background job processing.

## 🖼️ Application Overview

![Todo List Manager](assets/screenshot/Home_Page.png)
*Main dashboard with modern UI, recipient email functionality, and real-time todo management.*

## 🚀 Features

### Todo List Management (CRUD)
- ✅ Create, Read, Update, Delete todos
- ✅ Each todo includes: Title, Description, **Recipient Email**, DateTime (Reminder time)
- ✅ Modern responsive UI with Bootstrap 5
- ✅ Real-time updates with AJAX
- ✅ **Email validation** - Ensures proper email format

### Email Reminder System
- ✅ Sends email reminders 10 minutes before due time
- ✅ Uses Laravel Queues for background processing
- ✅ Fetches data from external API (jsonplaceholder.typicode.com/posts)
- ✅ Attaches CSV file with API data to emails
- ✅ Laravel Scheduler runs every minute to check for pending reminders
- ✅ **Dynamic recipient emails** - Each todo can have its own recipient email
- ✅ **Email validation** - Ensures proper email format in forms

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

**For Testing (Log Driver):**
```env
MAIL_MAILER=log  # Emails will be logged to storage/logs/laravel.log
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

## 📸 Application Screenshots

### 🏠 Home Page - Todo List Manager
![Todo List Manager](assets/screenshot/Home_Page.png)
*Main dashboard showing todo list with recipient email field, add/edit functionality, and email status indicators.*

### 📧 Email Logs Dashboard
![Email Logs](assets/screenshot/email_log.png)
*Comprehensive email logging system with statistics, status tracking, and detailed email history.*

### 🔍 Email Details Modal
![Email Details](assets/screenshot/Email_details.png)
*Detailed view of individual email logs showing recipient, subject, content, and status information.*

### ✏️ Edit Todo Modal
![Edit Todo](assets/screenshot/Edit_todo.png)
*Edit todo form with recipient email field, description, and due date/time management.*

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

# Test todo with recipient email
php artisan todos:test-reminder 14  # Uses recipient_email from todo

# Check for pending reminders
php artisan todos:check-reminders
```

### Email Testing Examples
```bash
# Create a todo with recipient email
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Todo",
    "description": "Test description",
    "recipient_email": "test@example.com",
    "due_datetime": "2025-07-13 10:00:00"
  }'

# Test the email reminder
php artisan todos:test-reminder [todo_id]

# Check email logs
curl http://localhost:8000/api/email-logs
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
│   │   ├── create_email_logs_table.php
│   │   └── add_recipient_email_to_todos_table.php  # NEW
│   └── seeders/
│       └── TodoSeeder.php
├── resources/views/
│   ├── todos/
│   │   └── index.blade.php  # Updated with recipient email field
│   └── email-logs/
│       └── index.blade.php
└── routes/
    ├── api.php
    └── web.php
```

## 🎨 Frontend Email Features

### 🖥️ User Interface Components

#### **Add Todo Form**
- **Recipient Email Field**: Required email input with validation
- **Title Field**: Todo title with character limit
- **Description Field**: Optional detailed description
- **Due Date/Time**: DateTime picker for reminder scheduling
- **Add Button**: Creates todo with all fields

#### **Edit Todo Modal**
- **Pre-populated Fields**: All existing data loaded
- **Recipient Email**: Editable email field
- **Validation**: Real-time form validation
- **Save/Cancel**: Modal actions for updates

#### **Todo List Display**
- **Email Status**: Visual indicators for sent reminders
- **Recipient Email**: Shows target email address
- **Due Date**: Formatted date/time display
- **Action Buttons**: Edit and delete functionality

### 📱 Responsive Design
- **Bootstrap 5**: Modern, mobile-friendly UI
- **Font Awesome Icons**: Visual indicators and buttons
- **AJAX Updates**: Real-time data without page refresh
- **Modal Dialogs**: Clean edit interface

### 🔧 Technical Implementation

#### **Add Todo Form**
```html
<!-- Recipient Email Field -->
<input type="email" id="recipient_email" class="form-control" placeholder="Recipient email" required>
```

#### **Edit Todo Modal**
```html
<!-- Edit form includes recipient email -->
<input type="email" id="edit_recipient_email" class="form-control" required>
```

#### **Todo Display**
```javascript
// Shows recipient email in todo list
To: ${todo.recipient_email || 'No email'}
```

#### **JavaScript Validation**
```javascript
// Form data includes recipient email
const formData = {
    title: document.getElementById('title').value,
    recipient_email: document.getElementById('recipient_email').value,
    description: document.getElementById('description').value,
    due_datetime: document.getElementById('due_datetime').value
};
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
  - **Uses dynamic recipient email** from todo record

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
- recipient_email (string, nullable)  # NEW: Dynamic recipient email
- due_datetime (datetime)
- email_notification_sent (boolean, default: false)
- created_at, updated_at
```

## 📧 Email Functionality System - Complete Guide

### 🔄 **Email System Architecture**

The email system follows a sophisticated multi-layered architecture:

```
User Input → Form Validation → Database → Scheduler → Command → Job → Email Service → Logging
```

### 📋 **Step-by-Step Email Process**

#### **1. User Creates Todo with Email**
```html
<!-- Form includes recipient email field -->
<input type="email" id="recipient_email" class="form-control" placeholder="Recipient email" required>
```

#### **2. Form Validation**
```php
'recipient_email' => 'required|email'  // Ensures valid email format
```

#### **3. Database Storage**
```sql
todos table:
- recipient_email (VARCHAR) - Stores the email address
- email_notification_sent (BOOLEAN) - Tracks if reminder sent
```

#### **4. Scheduler Triggers (Every Minute)**
```php
// app/Console/Kernel.php
$schedule->command('todos:check-reminders')->everyMinute();
```

#### **5. Command Checks for Pending Reminders**
```php
// app/Console/Commands/CheckTodoReminders.php
$pendingTodos = Todo::pendingReminders()->get();
```

#### **6. Todo Model Scope**
```php
public function scopePendingReminders($query)
{
    return $query->where('email_notification_sent', false)
                ->where('due_datetime', '>', now())
                ->where('due_datetime', '<=', now()->addMinutes(10));
}
```

#### **7. Job Queuing**
```php
SendTodoReminderEmail::dispatch($todo);  // Queues job for background processing
```

#### **8. Email Job Processing**
```php
// app/Jobs/SendTodoReminderEmail.php
public function handle(): void
{
    // Step A: Fetch API Data
    $apiData = $this->fetchApiData();
    
    // Step B: Generate CSV
    $csvContent = $this->generateCsvContent($apiData);
    
    // Step C: Send Email with Attachment
    Mail::raw("Your todo '{$this->todo->title}' is due in 10 minutes!", 
        function ($message) use ($csvPath) {
            $message->to($this->todo->recipient_email)  // Dynamic recipient
                    ->subject("Reminder: {$this->todo->title}")
                    ->attach($csvPath, ['as' => 'api_data.csv']);
        });
    
    // Step D: Log Success
    EmailLog::create([...]);
    
    // Step E: Mark as Notified
    $this->todo->update(['email_notification_sent' => true]);
}
```

### 🎯 **Key Features Implementation**

#### **1. Dynamic Recipient Emails**
- Each todo can have its own recipient email
- No more hardcoded email addresses
- Email validation ensures proper format

#### **2. Route Model Binding**
All Todo operations use Laravel's Route Model Binding for automatic model resolution.

#### **3. Laravel Queues**
Email sending is handled asynchronously using Laravel Queues for better performance.

#### **4. Laravel Scheduler**
Automated checking for pending reminders every minute using Laravel's task scheduler.

#### **5. API Integration**
Fetches data from `jsonplaceholder.typicode.com/posts` and creates CSV attachments.

#### **6. Modern UI**
Responsive design with Bootstrap 5, Font Awesome icons, and AJAX interactions.

#### **7. Comprehensive Logging**
Every email is logged with recipient, status, and timestamp.

## 🚨 Troubleshooting

### Common Issues

1. **Emails not sending**
   - Check email configuration in `.env`
   - Ensure queue worker is running
   - Check email logs for errors
   - Verify recipient email is valid

2. **Recipient email not working**
   - Check if `recipient_email` column exists in database
   - Run migration: `php artisan migrate`
   - Verify email validation in forms

3. **Reminders not triggering**
   - Ensure scheduler is running: `php artisan schedule:work`
   - Check for pending todos: `php artisan todos:check-reminders`
   - Verify todos have valid recipient emails

4. **Database connection issues**
   - Verify database credentials in `.env`
   - Run migrations: `php artisan migrate`

5. **Frontend email field not showing**
   - Clear browser cache
   - Check if JavaScript is loading properly
   - Verify the form includes recipient email field

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