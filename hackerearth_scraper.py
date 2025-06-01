from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
import time
import json

class BotUser:
    def __init__(self, driver, username, password):
        self.driver = driver
        self.username = username
        self.password = password

    def login(self, sleep_time=2):
        self.driver.get("https://www.hackerearth.com/login/")
        time.sleep(sleep_time)
        self.driver.find_element(By.ID, "id_login").send_keys(self.username)
        self.driver.find_element(By.ID, "id_password").send_keys(self.password)
        print("Logging in...")
        self.driver.find_element(By.NAME, "signin").click()
        time.sleep(sleep_time)

        errors_list = self.driver.find_elements(By.CSS_SELECTOR, "ul[class='errorlist nonfield']")
        if len(errors_list) > 0:
            errors = errors_list[0].find_elements(By.TAG_NAME, 'li')
            message = [error.text for error in errors]
            print("\n".join(message))
            return {"is_done": False, "message": message}
        
        print("Successfully logged in!")
        return {"is_done": True, "message": ["Successfully logged in!"]}

class UserProfile:
    def __init__(self):
        self.username = None
        self.points = None
        self.contest_rating = None
        self.problems_solved = None
        self.solutions_submitted = None

    def to_json(self):
        """Convert profile data to JSON-serializable dictionary"""
        return {
            "username": self.username,
            "points": self.points,
            "contest_rating": self.contest_rating,
            "problems_solved": self.problems_solved,
            "solutions_submitted": self.solutions_submitted
        }

    def __str__(self):
        return json.dumps(self.to_json(), indent=2)

class HackerEarthProfileScrapper:
    def __init__(self, username, password):
        chrome_options = Options()
        chrome_options.add_argument("--headless")  # Run in headless mode for production
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")
        self.driver = webdriver.Chrome(options=chrome_options)
        self.bot = BotUser(self.driver, username, password)
        self.profile = UserProfile()

    def login_and_scrape(self, profile_url):
        """Login to HackerEarth and scrape profile data"""
        # First login
        login_result = self.bot.login()
        if not login_result["is_done"]:
            return {"success": False, "error": "Login failed", "message": login_result["message"]}
        
        # Then scrape profile
        profile_data = self.scrap_profile(profile_url)
        return {"success": True, "data": profile_data}

    def scrap_profile(self, profile_url):
        """Returns profile data as JSON"""
        self.driver.get(profile_url)
        time.sleep(5)  # Wait for page to load
        
        # Extract username from URL or page
        self.profile.username = profile_url.split('/')[-2] if profile_url.endswith('/') else profile_url.split('/')[-1]
        
        try:
            # Wait for metrics container to load
            metrics_container = self.driver.find_element(By.CLASS_NAME, "metrics-container")
            
            # Points
            try:
                points_section = self.driver.find_element(By.XPATH, "//div[contains(text(), 'Points')]/following-sibling::*")
                points_text = points_section.text.strip()
                self.profile.points = int(points_text) if points_text.isdigit() else 0
            except:
                try:
                    points_element = self.driver.find_element(By.XPATH, "//div[@class='points item']//following-sibling::div")
                    self.profile.points = int(points_element.text.strip())
                except:
                    self.profile.points = 0
            
            # Contest Rating
            try:
                contest_section = self.driver.find_element(By.XPATH, "//div[contains(text(), 'Contest rating')]/following-sibling::*")
                contest_text = contest_section.text.strip()
                self.profile.contest_rating = int(contest_text) if contest_text.isdigit() else 0
            except:
                try:
                    contest_element = self.driver.find_element(By.XPATH, "//div[@class='contest-ratings item']//following-sibling::div")
                    self.profile.contest_rating = int(contest_element.text.strip())
                except:
                    self.profile.contest_rating = 0
            
            # Problems Solved
            try:
                problems_section = self.driver.find_element(By.XPATH, "//div[contains(text(), 'Problem solved')]/following-sibling::*")
                problems_text = problems_section.text.strip()
                self.profile.problems_solved = int(problems_text) if problems_text.isdigit() else 0
            except:
                try:
                    problems_element = self.driver.find_element(By.XPATH, "//div[@class='problems-solved item']//following-sibling::div")
                    self.profile.problems_solved = int(problems_element.text.strip())
                except:
                    self.profile.problems_solved = 0
            
            # Solutions Submitted
            try:
                solutions_section = self.driver.find_element(By.XPATH, "//div[contains(text(), 'Solutions submitted')]/following-sibling::*")
                solutions_text = solutions_section.text.strip()
                self.profile.solutions_submitted = int(solutions_text) if solutions_text.isdigit() else 0
            except:
                try:
                    solutions_element = self.driver.find_element(By.XPATH, "//div[@class='submissions item']//following-sibling::div")
                    self.profile.solutions_submitted = int(solutions_element.text.strip())
                except:
                    self.profile.solutions_submitted = 0
                
        except Exception as e:
            # Set default values if container not found
            self.profile.points = 0
            self.profile.contest_rating = 0
            self.profile.problems_solved = 0
            self.profile.solutions_submitted = 0

        return self.profile.to_json()

    def close(self):
        self.driver.quit()

# FastAPI
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel

app = FastAPI(title="HackerEarth Profile Scraper", version="1.0.0")

# Add CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Allows all origins
    allow_credentials=True,
    allow_methods=["*"],  # Allows all methods
    allow_headers=["*"],  # Allows all headers
)

class HackerEarthCredentials(BaseModel):
    username: str
    password: str
    profile_url: str = None

@app.post("/api/hackerearth-connect")
async def connect_hackerearth(credentials: HackerEarthCredentials):
    """Connect to HackerEarth and scrape profile data"""
    if not credentials.username or not credentials.password:
        raise HTTPException(status_code=400, detail="Username and password are required")
    
    # If no profile URL provided, construct it from username
    if not credentials.profile_url:
        credentials.profile_url = f"https://www.hackerearth.com/@{credentials.username}"
    
    scrapper = HackerEarthProfileScrapper(credentials.username, credentials.password)
    try:
        result = scrapper.login_and_scrape(credentials.profile_url)
        if not result["success"]:
            raise HTTPException(status_code=401, detail=result.get("message", "Login failed"))
        return result["data"]
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
    finally:
        scrapper.close()

@app.get("/api/profile")
async def get_profile(url: str = Query(..., description="Profile URL to scrape")):
    """Get profile data without authentication (deprecated - use POST /api/hackerearth-connect instead)"""
    if not url:
        raise HTTPException(status_code=400, detail="Missing profile URL")

    # This would require existing credentials - for now, return error
    raise HTTPException(status_code=400, detail="Please use POST /api/hackerearth-connect with credentials")

@app.get("/health")
async def health_check():
    """Health check endpoint"""
    return {"status": "healthy", "service": "HackerEarth Profile Scraper"}

# Example usage
if __name__ == "__main__":
    import uvicorn
    
    print("Starting HackerEarth Profile Scraper API...")
    uvicorn.run(app, host="0.0.0.0", port=8000) 
