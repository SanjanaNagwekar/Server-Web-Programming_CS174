# Final Project - User Authentication and File Upload System

## Overview

This project is a web application that provides user authentication and allows users to upload text files containing questions. The uploaded questions are stored in a database, and users can retrieve a random question. The application includes the following features:
- User Registration
- User Login
- File Uploading (.txt files)
- Session Management
- Client-side Validation
- Server-side Validation
- Secure Password Management

## Requirements

- XAMPP (for PHP and MySQL)
- Web Browser

## Setup Instructions

### Step 1: Install XAMPP
Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/index.html).

### Step 2: Start Apache and MySQL
Open the XAMPP Control Panel and start the Apache and MySQL services.

### Step 3: Configure MySQL
1. Open the XAMPP Control Panel and click on the "Shell" button to open the terminal.
2. Log in to MySQL:
    ```sh
    mysql -u root -p
    ```
3. Create the database and user:
    ```sql
    CREATE DATABASE QuestionsDB;
    CREATE USER 'user4'@'localhost' IDENTIFIED BY 'pass123';
    GRANT ALL PRIVILEGES ON QuestionsDB.* TO 'user4'@'localhost';
    FLUSH PRIVILEGES;
    ```

### Step 4: Create Database Tables
1. Open phpMyAdmin by navigating to `http://localhost/phpmyadmin` in your web browser.
2. Select the `QuestionsDB` database.
3. Execute the SQL statements in the provided `DBschema.sql` file to create the necessary tables:
    ```sql
    CREATE TABLE Users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL
    );

    CREATE TABLE Questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        question TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES Users(id),
        UNIQUE (user_id, question(255))
    );
    ```

### Step 5: Deploy the Application
1. Download and extract the project files.
2. Copy the project folder to the `htdocs` directory of your XAMPP installation (e.g., `C:\xampp\htdocs\FinalProject`).

### Step 6: Update Configuration
1. Open the `config.php` file in the project directory.
2. Ensure the database connection settings are correct:
    ```php
    <?php
    $dbHost = "localhost";
    $dbUser = "user4";
    $dbPassword = "pass123";
    $dbName = "QuestionsDB";
    $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($dbConnection->connect_error) {
        die("Connection failed: " . $dbConnection->connect_error);
    }
    ?>
    ```

### Step 7: Access the Application
1. Open your web browser and navigate to `http://localhost/FinalProject/signup.php` to register a new account.
2. Log in using the created account via `http://localhost/FinalProject/login.php`.

## File Descriptions

### PHP Files
- `signup.php`: Handles user registration.
- `login.php`: Handles user login.
- `home.php`: Main page for logged-in users, handles file upload and question retrieval.
- `upload.php`: Processes file uploads and stores questions in the database.
- `get_content.php`: Retrieves a random question from the database.
- `config.php`: Contains database connection settings.
- `functions.php`: Contains various helper functions for user management, validation, and database operations.

### JavaScript Files
- `validate_functions.js`: Contains client-side validation functions for the forms.

### SQL File
- `DBschema.sql`: Contains SQL statements to create the necessary database tables.

## Security Practices Implemented
- **User Authentication**: Secure login and registration system with password hashing.
- **Input Sanitization**: All user inputs are sanitized to prevent SQL injection.
- **Session Management**: Uses PHP sessions to manage user login state.
- **File Handling**: Validates file types and sizes before processing.
- **Client-side Validation**: Validates user inputs on the client side using JavaScript.
