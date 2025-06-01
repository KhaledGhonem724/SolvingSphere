#!/usr/bin/env python3

import subprocess
import sys
import os

def main():
    """Start the HackerEarth scraper service"""
    print("Starting HackerEarth Profile Scraper API...")
    print("Service will be available at: http://localhost:8000")
    print("Health check endpoint: http://localhost:8000/health")
    print("API documentation: http://localhost:8000/docs")
    print("\nPress Ctrl+C to stop the service\n")

    try:
        # Run the scraper service
        subprocess.run([
            sys.executable, 
            "hackerearth_scraper.py"
        ], check=True)
    except KeyboardInterrupt:
        print("\nStopping HackerEarth scraper service...")
    except subprocess.CalledProcessError as e:
        print(f"Error starting scraper service: {e}")
        return 1
    except FileNotFoundError:
        print("Error: Python not found. Please ensure Python is installed and in your PATH.")
        return 1

    return 0

if __name__ == "__main__":
    sys.exit(main())