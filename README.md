# Smart Blood Inventory & Temperature Monitoring System

A comprehensive RESTful API for managing blood inventory and real-time refrigerator temperature monitoring in blood banks. Built with Laravel 11, featuring critical temperature alerts, blood bag inventory management, and user role-based access control.

## 🎯 Features

- **Blood Inventory Management**: Track blood bags by type, quantity, and expiration date
- **Real-time Temperature Monitoring**: Continuous temperature tracking for refrigerators with automatic alerts
- **Critical Alerts**: Automatic alerts for temperature anomalies, expiring blood, and low stock
- **User Management**: Role-based access control (Admin, Staff, Viewer)
- **Dashboard Analytics**: Comprehensive statistics and operational insights
- **Interactive API Documentation**: OpenAPI 3.0 / Swagger UI
- **Queue Processing**: Background jobs for alert notifications
- **Event-driven Architecture**: Custom events for critical temperature changes

## 🛠 Tech Stack

- **Backend**: Laravel 11.31
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum (token-based)
- **API Documentation**: OpenAPI 3.0 / Swagger UI
- **Task Scheduling**: Laravel Queue System
- **Frontend Build**: Vite, Tailwind CSS

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js 18+ (for frontend assets)
- Git

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd bloodbank
```

### 2. Setup Environment
```bash
cp .env.example .env
composer install
php artisan key:generate
```

### 3. Configure Database
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bloodbank
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate
php artisan db:seed  # Optional: populate demo data
```

### 5. Install Frontend Dependencies
```bash
npm install
npm run build
```

### 6. Start Development Environment
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start queue worker (for background jobs)
php artisan queue:work

# Terminal 3: Build frontend assets with hot reload
npm run dev
```

Access the application at `http://localhost:8000`

## 📚 API Documentation

### Interactive API Docs
Visit **`http://localhost:8000/docs`** to access the interactive Swagger UI.

The Swagger UI provides:
- ✅ Live endpoint documentation
- ✅ Request/response examples
- ✅ Try-it-out feature to test endpoints directly
- ✅ Authentication token management
- ✅ Model schema definitions

### OpenAPI Specification
- **Endpoint**: `GET /api/docs/openapi.json`
- **Format**: OpenAPI 3.0.0
- **Base URL**: `/api`

## 🔐 Authentication

All API endpoints (except `/api/login`) require authentication using Sanctum tokens.

### Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin"
  }
}
```

### Using the Token
Include the token in the Authorization header for all subsequent requests:
```bash
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

### Logout
```bash
POST /api/logout
Authorization: Bearer {token}
```

## 📖 API Endpoints

All endpoints are documented in the Swagger UI at `/docs`. Below is a quick reference:

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Authenticate and receive token |
| POST | `/api/logout` | Logout and invalidate token |

### Blood Bags
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/blood-bags` | List all blood bags (paginated) |
| POST | `/api/blood-bags` | Create a new blood bag |
| GET | `/api/blood-bags/{id}` | Get blood bag details |
| PUT | `/api/blood-bags/{id}` | Update blood bag |
| DELETE | `/api/blood-bags/{id}` | Delete blood bag |
| GET | `/api/blood-bags-expiring` | Get bags expiring within 24 hours |
| GET | `/api/blood-bags-expired` | Get expired blood bags |
| GET | `/api/blood-bags-near-risk-percentage` | Get risk percentage for expiring bags |

### Refrigerators
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/refrigerators` | List all refrigerators (paginated) |
| POST | `/api/refrigerators` | Create a new refrigerator |
| GET | `/api/refrigerators/{id}` | Get refrigerator details |
| PUT | `/api/refrigerators/{id}` | Update refrigerator |
| DELETE | `/api/refrigerators/{id}` | Delete refrigerator |
| POST | `/api/refrigerators/{id}/temperature-logs` | Record temperature reading |
| GET | `/api/refrigerators/{id}/temperature-stats` | Get temperature statistics |

### Blood Banks
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/blood-banks` | List all blood banks (paginated) |
| POST | `/api/blood-banks` | Create a new blood bank |
| GET | `/api/blood-banks/{id}` | Get blood bank details |
| PUT | `/api/blood-banks/{id}` | Update blood bank |
| DELETE | `/api/blood-banks/{id}` | Delete blood bank |

### Alerts
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/alerts` | List all alerts |
| GET | `/api/alerts/{id}` | Get alert details |
| POST | `/api/alerts/{id}/resolve` | Mark alert as resolved |

### Users
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/users` | List all users |
| POST | `/api/users` | Create a new user |
| POST | `/api/users/{id}/assign-bank` | Assign user to a blood bank |
| POST | `/api/users/{id}/remove-bank` | Remove user from a blood bank |

### Dashboard
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/dashboard` | Get dashboard statistics and insights |

## 📋 Request/Response Examples

### Create a Blood Bag
```bash
POST /api/blood-bags
Authorization: Bearer {token}
Content-Type: application/json

{
  "bag_number": "BB-2026-001",
  "blood_type": "O+",
  "collection_date": "2026-07-04",
  "expiry_date": "2026-08-04",
  "refrigerator_id": 1
}
```

**Response (201 Created):**
```json
{
  "id": 1,
  "bag_number": "BB-2026-001",
  "blood_type": "O+",
  "collection_date": "2026-07-04",
  "expiry_date": "2026-08-04",
  "refrigerator_id": 1,
  "created_at": "2026-07-04T10:30:00Z"
}
```

### Record Temperature Log
```bash
POST /api/refrigerators/1/temperature-logs
Authorization: Bearer {token}
Content-Type: application/json

{
  "temperature": 4.5,
  "humidity": 65
}
```

**Response (201 Created):**
```json
{
  "id": 1,
  "refrigerator_id": 1,
  "temperature": 4.5,
  "humidity": 65,
  "status": "normal",
  "created_at": "2026-07-04T10:35:00Z"
}
```

### Get Temperature Statistics
```bash
GET /api/refrigerators/1/temperature-stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "avg_temp": 4.2,
  "min_temp": 3.8,
  "max_temp": 5.1
}
```

## 👥 User Roles

The system supports three user roles with different permission levels:

### Admin
- ✅ Full system access
- ✅ User management
- ✅ Blood bank management
- ✅ View all data

### Staff
- ✅ Limited to assigned blood banks
- ✅ Blood bag management
- ✅ Temperature monitoring
- ✅ View reports

### Viewer
- ✅ Read-only access
- ✅ View blood inventory
- ✅ View statistics and alerts

## 📁 Project Structure

```
bloodbank/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/      # API controllers
│   │   ├── Middleware/           # Custom middleware
│   │   ├── Requests/             # Form validation requests
│   │   └── Resources/            # API response transformers
│   ├── Models/                   # Eloquent models
│   ├── Events/                   # Event classes
│   ├── Listeners/                # Event listeners
│   ├── Jobs/                     # Queue jobs
│   └── Notifications/            # Notification classes
├── database/
│   ├── migrations/               # Database migrations
│   ├── factories/                # Model factories
│   └── seeders/                  # Database seeders
├── routes/
│   ├── api.php                   # API routes
│   └── web.php                   # Web routes
├── docs/
│   └── openapi.yaml              # OpenAPI specification
├── public/
│   └── swagger.html              # Swagger UI
├── tests/                        # Test suites
└── storage/
    └── logs/                     # Application logs
```

## 🔄 Database Schema

### Users
- id, name, email, password, role, created_at, updated_at

### Blood Banks
- id, name, location, phone, email, created_at, updated_at

### Refrigerators
- id, name, model, blood_bank_id, min_temp, max_temp, created_at, updated_at

### Blood Bags
- id, bag_number, blood_type, collection_date, expiry_date, refrigerator_id, created_at, updated_at

### Temperature Logs
- id, refrigerator_id, temperature, humidity, status, created_at

### Alerts
- id, type, status, message, severity, created_at, updated_at

### Blood Bank User (Pivot)
- id, blood_bank_id, user_id, created_at, updated_at

## 🚨 Error Handling

All error responses follow a consistent format:

```json
{
  "message": "Error description",
  "errors": {
    "field_name": ["Error message for field"]
  }
}
```

### HTTP Status Codes
- `200 OK` - Successful GET/PUT request
- `201 Created` - Successful POST request
- `400 Bad Request` - Invalid request data
- `401 Unauthorized` - Missing or invalid authentication
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test file:
```bash
php artisan test tests/Feature/AuthTest.php
```

## 📝 Logging

Application logs are stored in `storage/logs/`. View real-time logs:
```bash
php artisan pail
```

## 🐛 Troubleshooting

### Port Already in Use
```bash
php artisan serve --port=8001
```

### Queue Not Processing
```bash
# Check if queue worker is running
php artisan queue:work --verbose

# Clear failed jobs
php artisan queue:failed
php artisan queue:retry all
```

### Database Connection Error
- Verify `.env` database settings
- Ensure MySQL is running
- Check database credentials

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

## 🤝 Contributing

1. Create a feature branch
2. Make your changes
3. Run tests
4. Submit a pull request

## 📄 License

This project is licensed under the MIT License.

## 📞 Support

For issues and support:
- Check the Swagger documentation at `/docs`
- Review error messages in `storage/logs/`
- Contact the development team

---

**Last Updated**: July 4, 2026  
**API Version**: 1.0.0  
**Status**: Production Ready ✅
