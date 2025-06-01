# HackerEarth Integration Setup Guide

This guide explains how to set up and use the HackerEarth integration feature in your SolvingSphere application.

## Overview

The HackerEarth integration allows users to connect their HackerEarth accounts to display their coding achievements directly in their profile. The system consists of three main components:

1. **Python Scraper Service** - Handles HackerEarth login and profile data scraping
2. **Laravel Backend** - Manages API endpoints and database operations
3. **React Frontend** - Provides the user interface for connecting accounts

## Prerequisites

- Python 3.7+ with pip
- Chrome or Chromium browser (for Selenium WebDriver)
- ChromeDriver (automatically managed by Selenium)
- Laravel application with authentication
- Node.js and npm (for the React frontend)

## Installation

### 1. Install Python Dependencies

```bash
pip install -r requirements.txt
```

### 2. Database Migration

Run the Laravel migration to add HackerEarth fields to the users table:

```bash
php artisan migrate
```

### 3. Start the Python Scraper Service

The Python service must be running before users can connect their HackerEarth accounts.

**Option 1: Using the startup script**
```bash
python start_scraper.py
```

**Option 2: Direct execution**
```bash
python hackerearth_scraper.py
```

The service will start on `http://localhost:8000` with the following endpoints:
- `POST /api/hackerearth-connect` - Connect and scrape HackerEarth account
- `GET /health` - Health check endpoint
- `GET /docs` - API documentation (FastAPI auto-generated)

### 4. Verify Service Health

Check if the scraper service is running:
```bash
curl http://localhost:8000/health
```

Expected response:
```json
{"status": "healthy", "service": "HackerEarth Profile Scraper"}
```

### 5. Laravel Backend:

# For Windows with XAMPP:
C:\xampp\php\php.exe artisan serve --port=8080
# Service runs on: http://localhost:8080

# For systems with PHP in PATH:
php artisan serve --port=8080

### 6.npm run dev
# Service runs on: http://localhost:5173

## Usage

### For Users

1. **Navigate to Profile Page**
   - Go to your profile page in the application
   - Scroll down to the "HackerEarth Integration" section (below the badges)

2. **Connect HackerEarth Account**
   - Enter your HackerEarth username and password
   - Click "Connect Account"
   - Wait for the system to authenticate and scrape your profile data

3. **View Your HackerEarth Stats**
   - Once connected, your HackerEarth statistics will be displayed:
     - Points
     - Contest Rating
     - Problems Solved
     - Solutions Submitted

4. **Refresh Data**
   - To update your HackerEarth data, enter your password and click "Refresh"
   - This will re-scrape your current profile statistics

5. **Disconnect Account**
   - Click the "Disconnect" button to remove the HackerEarth integration
   - This will clear all stored HackerEarth data from your profile

### For Developers

#### API Endpoints

**Laravel Backend Endpoints:**
- `POST /api/hackerearth/connect` - Connect HackerEarth account
- `POST /api/hackerearth/refresh` - Refresh profile data
- `DELETE /api/hackerearth/disconnect` - Disconnect account

**Python Scraper Service Endpoints:**
- `POST /api/hackerearth-connect` - Scrape HackerEarth profile
- `GET /health` - Service health check

#### Database Schema

The migration adds the following fields to the `users` table:
- `hackerearth_username` - HackerEarth username
- `hackerearth_points` - Points earned on HackerEarth
- `hackerearth_contest_rating` - Contest rating
- `hackerearth_problems_solved` - Number of problems solved
- `hackerearth_solutions_submitted` - Number of solutions submitted
- `hackerearth_connected_at` - When the account was first connected
- `hackerearth_updated_at` - When the data was last refreshed

## Security Considerations

1. **Credential Handling**
   - User passwords are only used for authentication with HackerEarth
   - Passwords are never stored in the database
   - All communication is over HTTPS in production

2. **Service Communication**
   - The Laravel backend communicates with the Python service via HTTP
   - In production, consider securing this communication with API keys or network isolation

3. **Error Handling**
   - Invalid credentials result in appropriate error messages
   - Service unavailability is handled gracefully
   - Rate limiting should be implemented for production use

## Troubleshooting

### Common Issues

1. **"Service temporarily unavailable" error**
   - Ensure the Python scraper service is running on port 8000
   - Check service health: `curl http://localhost:8000/health`

2. **"Failed to connect to HackerEarth" error**
   - Verify your HackerEarth credentials are correct
   - Check if HackerEarth is accessible from your server
   - Ensure ChromeDriver is properly installed

3. **Migration errors**
   - Make sure you're running `php artisan migrate` from the Laravel root directory
   - Check database connection settings

4. **Frontend component not loading**
   - Verify the React component is properly imported in the profile page
   - Check browser console for JavaScript errors
   - Ensure all UI components are available

### Development Tips

1. **Testing the Python Service**
   ```bash
   # Test health endpoint
   curl http://localhost:8000/health
   
   # Test connection endpoint (with valid credentials)
   curl -X POST http://localhost:8000/api/hackerearth-connect \
     -H "Content-Type: application/json" \
     -d '{"username": "your_username", "password": "your_password"}'
   ```

2. **Debug Mode**
   - Remove the `--headless` option in `hackerearth_scraper.py` to see the browser in action
   - Add more logging statements for debugging

3. **Production Deployment**
   - Use a process manager like `supervisor` or `systemd` to manage the Python service
   - Implement proper logging and monitoring
   - Consider using a reverse proxy for the Python service
   - Add rate limiting and authentication for the scraper endpoints

## Component Architecture

```
┌─────────────────┐    HTTP     ┌─────────────────┐    HTTP     ┌─────────────────┐
│  React Frontend │ ─────────> │ Laravel Backend │ ─────────> │ Python Scraper  │
│                 │  (Form)     │                 │ (API Call) │    Service      │
└─────────────────┘             └─────────────────┘             └─────────────────┘
                                          │                              │
                                          │                              │
                                          ▼                              ▼
                                 ┌─────────────────┐             ┌─────────────────┐
                                 │   MySQL DB      │             │   HackerEarth   │
                                 │   (User Data)   │             │    Website      │
                                 └─────────────────┘             └─────────────────┘
```

## Future Enhancements

1. **Caching**: Implement caching to reduce API calls to HackerEarth
2. **Background Jobs**: Use Laravel queues for scraping operations
3. **Multiple Platforms**: Extend to support other coding platforms
4. **Analytics**: Add analytics dashboard for HackerEarth statistics
5. **Notifications**: Notify users of achievements or rating changes

## Support

If you encounter any issues or need help with the setup, please:

1. Check the troubleshooting section above
2. Verify all prerequisites are met
3. Check the application logs for detailed error messages
4. Ensure all services are running and accessible

For development questions, refer to the codebase documentation and comments. 









pip install -r requirements.txt




3. Database Migration

# For Windows with XAMPP:
C:\xampp\php\php.exe artisan migrate

# For systems with PHP in PATH:
php artisan migrate








Terminal 1 - Python Scraper Service:

python start_scraper.py
# Service runs on: http://localhost:8000


Terminal 2 - Laravel Backend:

# For Windows with XAMPP:
C:\xampp\php\php.exe artisan serve --port=8080
# Service runs on: http://localhost:8080

# For systems with PHP in PATH:
php artisan serve --port=8080




npm run dev
# Service runs on: http://localhost:5173

5. Health Check:

curl http://localhost:8000/health