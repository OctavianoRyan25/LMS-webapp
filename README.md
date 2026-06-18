# Laravel LMS Admin Application

Laravel-based Learning Management System (LMS) admin application for managing courses, chapters, lessons, quizzes, assignments, and exams.

## Features

- Role-based authentication (Admin, Tutor, Student)
- Course management with chapters and lessons
- Lesson files (PDF, images, videos)
- Quizzes, assignments, and exams
- Student progress tracking
- RESTful API with Laravel Sanctum

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Redis
- Composer

## Installation

1. Install dependencies:
```bash
composer install
```

2. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Update `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_admin
DB_USERNAME=root
DB_PASSWORD=your_password

CACHE_STORE=redis
REDIS_CLIENT=predis
```

4. Create database:
```bash
mysql -u root -p -e "CREATE DATABASE lms_admin"
```

5. Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

6. Start the development server:
```bash
php artisan serve
```

## Default Users

- **Admin**: admin@example.com / password
- **Tutor**: tutor@example.com / password

## API Endpoints

### Authentication
- POST `/api/login` - Login
- POST `/api/logout` - Logout (authenticated)
- GET `/api/me` - Get current user (authenticated)

### Courses
- GET `/api/courses` - List all courses
- POST `/api/courses` - Create course
- GET `/api/courses/{course}` - Show course
- PUT `/api/courses/{course}` - Update course
- DELETE `/api/courses/{course}` - Delete course

### Chapters
- GET `/api/courses/{course}/chapters` - List chapters
- POST `/api/courses/{course}/chapters` - Create chapter
- GET `/api/chapters/{chapter}` - Show chapter
- PUT `/api/chapters/{chapter}` - Update chapter
- DELETE `/api/chapters/{chapter}` - Delete chapter

### Lessons
- GET `/api/chapters/{chapter}/lessons` - List lessons
- POST `/api/chapters/{chapter}/lessons` - Create lesson
- GET `/api/lessons/{lesson}` - Show lesson
- PUT `/api/lessons/{lesson}` - Update lesson
- DELETE `/api/lessons/{lesson}` - Delete lesson

### Quizzes
- GET `/api/lessons/{lesson}/quizzes` - List quizzes
- POST `/api/lessons/{lesson}/quizzes` - Create quiz
- GET `/api/quizzes/{quiz}` - Show quiz
- PUT `/api/quizzes/{quiz}` - Update quiz
- DELETE `/api/quizzes/{quiz}` - Delete quiz

## Project Structure

```
app/
├── Actions/              # Single-purpose use cases
├── Http/
│   ├── Controllers/Api/  # API controllers
│   ├── Requests/         # Form request validation
│   └── Resources/        # API resources
├── Models/               # Eloquent models
├── Policies/             # Authorization policies
└── Services/             # Coordinating domain services

database/
├── migrations/           # Database migrations
└── seeders/              # Database seeders

routes/
└── api.php              # API routes
```

## Database Schema

- **roles**: User roles (admin, tutor, student)
- **users**: System users
- **courses**: Course information
- **chapters**: Course chapters
- **lessons**: Chapter lessons (with prev/next links)
- **lesson_files**: Lesson attachments (PDF, images, videos)
- **quizzes**: Lesson quizzes
- **assignments**: Lesson assignments
- **exams**: Course exams
- **student_progress**: Student lesson completion tracking

## Testing

Run tests:
```bash
php artisan test
```

## License

MIT License
