# **Welcome To Smart Dhaka-A Smart City Management System**

## Team Name: NSU_MATES
Department of Computer Science & Engineering, North South University, Bangladesh

### Team Members:
1. Md. Sabbir Hossain Tamim - 2222037642
2. Tanjim Salam - 2021481042
3. MD. Fazla Rabbi- 2111104642
4. Md. Sabbir Hoshen Khan- 2122188042

### Overview

The Smart City Management System is a web-based application built with PHP, HTML, and CSS, using a MySQL database. It allows citizens to register, file complaints, request emergency services, manage utility bills, and more, with an admin dashboard for managing various city services. The project is developed using Visual Studio Code and runs on XAMPP for Apache and MySQL.

### Prerequisites

XAMPP: Version with Apache and MySQL (e.g., XAMPP with PHP 8.2.12 and MariaDB 10.4.32).

Visual Studio Code: For editing and managing code.

Web Browser: For accessing the application (e.g., Chrome, Firefox).

Composer: For managing PHP dependencies (optional for PHPDoc documentation).

# Installation and Setup

### 1. Install XAMPP
- Download and install XAMPP from https://www.apachefriends.org/.

- Ensure Apache and MySQL modules are enabled in the XAMPP Control Panel.

### 2. Import the Database

- Start the MySQL module in XAMPP Control Panel.

- Open phpMyAdmin by navigating to http://localhost/phpmyadmin in your browser.

- Create a new database named smartcitydb:

- Click New in the left sidebar, enter smartcitydb, and click Create.

### Import the database schema:

- Select the smartcitydb database.

- Click the Import tab.

- Choose the smartcitydb (5).sql file from the project folder.

- Click Go to import the database structure and sample data.

## 3. Set Up the Project Files

- Copy the project folder (SmartCity2) to XAMPP's htdocs directory:

- Default location: C:\xampp\htdocs\SmartCity2.

- Ensure the folder contains all project files, including:

- config.php (database connection).

- complaints.php, admin_dashboard.php (PHP backend files).

- complaints.html, admin_dashboard.html, educational_institutes.html (HTML templates).

- css/ folder with complaints.css, admin_dashboard.css, educational_institutes.css.

- Other files like login.html (assumed based on access URL).

- Verify the folder structure in C:\xampp\htdocs\SmartCity2 matches the project’s layout.

## 4. Configure Database Connection

- Open config.php in VS Code.

- Ensure the database connection settings match your XAMPP setup:

      $pdo = new PDO("mysql:host=localhost;dbname=smartcitydb", "root", "");

    - Host: localhost

    - Database: smartcitydb

    - Username: root (default for XAMPP)

    - Password: "" (empty by default in XAMPP)

- Save any changes.

## 5. Run the Application

- Start Apache and MySQL in the XAMPP Control Panel.

- Open a web browser and navigate to:

      http://localhost/SmartCity2/login.html
# Features

- User Authentication: Register and log in as a citizen or admin.

- Complaint Management: File, edit, and delete complaints (admin can update status).

- Admin Dashboard: View pending complaints, emergencies, bills, and violations with notification badges.

- Educational Institutes: Manage institute data (add, edit, delete).

- Other Modules: Supports emergency requests, events, transport, utility bills, waste management, and traffic violations.

## Development Environment

- Code Editor: Visual Studio Code.

- Recommended extensions:

   - PHP Intelephense: For PHP code intelligence and formatting.

   - PHP DocBlocker: For generating PHPDoc comments.

- Database Management: phpMyAdmin (included with XAMPP).

- Server: XAMPP (Apache for serving PHP, MySQL for database).

### Running in VS Code

- Open the SmartCity2 folder in VS Code:

      File > Open Folder > C:\xampp\htdocs\SmartCity2

- Edit files as needed, using PHP Intelephense for autocompletion and error checking.

## Troubleshooting

- Database Connection Error:

     - Verify MySQL is running in XAMPP.

     - Check config.php credentials.

     - Ensure smartcitydb is imported correctly in phpMyAdmin.

- Page Not Found:

     - Confirm the project folder is in C:\xampp\htdocs\SmartCity2.

     - Check Apache is running and the URL is correct (http://localhost/SmartCity2/login.html).

- PHP Errors:

     - Enable error reporting in config.php:

      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);

- Check the Apache error log in C:\xampp\apache\logs.

