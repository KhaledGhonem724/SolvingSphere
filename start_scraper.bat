@echo off
echo Starting HackerEarth Profile Scraper API...
echo Service will be available at: http://localhost:8000
echo Health check endpoint: http://localhost:8000/health
echo API documentation: http://localhost:8000/docs
echo.
echo Press Ctrl+C to stop the service
echo.

python hackerearth_scraper.py

pause 