# Good-weather-Agro🌤️
 

A full-stack weather app that displays current weather and a 7-day forecast for any city. Includes features such as search history, user authentication, and caching.

---

## Features

### Current Weather
- **Temperature:** Current and feels-like temperature.
- **Weather description:** Clear, rainy, etc.
- **Wind speed & direction**
- **Humidity**
- **Air quality index (AQI)**
- **City and country details**
- **Timestamp of observation**

### 7-Day Forecast
- **Date**
- **Temperature range:** Max and Min.
- **Weather conditions**
- **Precipitation levels**
- **UV index**
- **Wind speed and direction**

### Other Features
- Search history stored for authenticated users (MySQL) and unauthenticated users (local storage).
- Responsive design for mobile, tablet, and desktop.
- Caching weather data for cities for 10 minutes to optimize API usage.
- Authentication system for enhanced user experience.

---

## Technologies Used
- **Frontend:** Vue.js 3, Tailwind CSS, CSS
- **Backend:** Laravel 11
- **Database:** MySQL
- **API:** Weatherbit

---

## Installation and Setup

### Prerequisites
- PHP >= 8.1
- Node.js >= 16
- Composer
- MySQL
 
### Backend Setup (Laravel)
1. Clone the repository:
   ```bash
   git clone  https://github.com/Mafumu12/Good-weather-Agro.git
   cd Good-Weather
2. Install Laravel dependencies:
   composer install
3. Copy .env file:
   cp .env.example .env
4. Set environment variables in .env:
   Database configuration: Update DB_DATABASE, DB_USERNAME, and DB_PASSWORD.
5. Run database migrations:
   php artisan migrate
6. Start the Laravel development server:
   php artisan serve

### Frontend Setup (Vue.js)

1. npm install
2. npm run dev

### Usage
http://127.0.0.1:8000








