# # Event Management System Setup

## Prerequisites
Ensure you have the following installed on your system:
- [Git](https://git-scm.com/downloads)
- [Laragon](https://laragon.org/download/)
- PHP 8.2 (included in Laragon or install separately)
- MySQL 8.0 or higher

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/Farukcoder/event_managment.git
```

### 2. Install Laragon
Download and install [Laragon](https://laragon.org/download/).

### 3. Configure PHP & MySQL
- Ensure PHP version 8.2 is set in Laragon.
- Ensure MySQL 8.0 or higher is installed and running.

### 4. Set Up Database
- Open MySQL and create a new database:
  ```sql
  CREATE DATABASE event_managment_system;
  ```
### 5. View Landing Page
- Open `http://localhost/your-path/event_managment` in your browser to see the event listing.
- The landing page will display all available events.
- Example Screenshot:

  ![Landing Page Screenshot](screenshot.png)

---
**Note:** Replace `your/path/event_managment` with the actual path where you cloned the repository.

