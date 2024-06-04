## Midterm #2 - Authentication is the Key

### Overview

This project involves the creation of a web application using HTML, PHP, and MySQL. The application includes user authentication, file uploading, and exclusive access to user content. The application consists of a login page, sign-up page, and a home page for displaying user content.

### Files Included

1. `login.php`: PHP script for handling user login.
2. `signup.php`: PHP script for handling user sign-up.
3. `home.php`: The home page displaying user content and allowing file uploads.
4. `upload.php`: PHP script for handling file uploads.
5. `get_content.php`: PHP script for retrieving and displaying file content.
6. `functions.php`: PHP script containing functions for user authentication and database queries.
7. `config.php`: PHP script for database configuration.
8. `SQL Commands`: SQL file containing the database schema.

### Functionality

1. **Webpage**:
    - **Login Page (login.php)**:
        - Allows users to input their credentials for logging in.
        - Redirects authenticated users to the home page.
    - **Sign-up Page (signup.php)**:
        - Allows users to input their name, username, and password for sign-up.
        - Validates username to ensure uniqueness in the database.
        - Redirects users to the login page after successful sign-up.
    - **Home Page (home.php)**:
        - Allows authenticated users to upload text files and input a thread name.
        - Displays the user's uploaded content, showing only the first 2 lines of each file initially.
        - Provides a button to expand and view the remaining lines of each file.
        - Redirects unauthenticated users to the login page.

2. **PHP**:
    - Functions to read inputs from the forms and store them in the database.
    - Functions to authenticate users and manage sessions.
    - Functions to upload files and store their content in the database.
    - Functions to retrieve and display user content, with the ability to expand and show the full content.

3. **MySQL**:
    - A database with at least two tables: a credentials table and a content table.
    - The credentials table contains fields for name, username, and password.
    - The content table contains fields for thread name, file content, and associated user.

### How to Run (Using XAMPP)
1. **Set Up XAMPP:**
   - Download and install XAMPP from https://www.apachefriends.org/index.html. 
   - Start the Apache and MySQL modules from the XAMPP control panel.
2. **Set Up the Database:**
   - Open your web browser and navigate to http://localhost/phpmyadmin.
   - Run the provided SQL queries in the SQL tab to create the database.
   ```sql
    CREATE DATABASE IF NOT EXISTS user_content;
    USE user_content;
   ```
   - Enter and run the contents of the `SQL Commands` file
3. **Set Up the Project Files:**
   - Copy the provided PHP files (login.php, signup.php, home.php, upload.php, get_content.php, functions.php, config.php) into the htdocs directory of your XAMPP installation (usually found at C:\xampp\htdocs on Windows or /Applications/XAMPP/htdocs on macOS).
4. Run the Application:
   - Open your web browser and navigate to http://localhost/signup.php to create a new user account. 
   - Navigate to http://localhost/login.php to log in with the created user account. 
   - Upon successful login, you will be redirected to http://localhost/home.php to upload files and view your content.

