## Assignment #6 - Authentication and Sessions with Client-side Validation

### Overview

This project involves the creation of a web application using HTML, PHP, and MySQL. The application includes user authentication, client-side validation, and secure data handling. The application consists of two main web pages: a login/signup page and a main page for displaying student advisor information.

### Files Included

1. `signup.php`: PHP script for handling user sign-up.
2. `login.php`: PHP script for handling user login.
3. `main.php`: The main page displaying student advisor information.
4. `functions.php`: PHP script containing functions for user authentication and database queries.
5. `config.php`: PHP script for database configuration.
6. `validate_functions.js`: JavaScript file for client-side validation.
7. `DBschema.sql`: SQL file containing the database schema.

### Functionality

1. **Webpage**:
    - **First Page (main.php)**:
        - Contains a form with text boxes for inputting a student's name and student ID code.
        - Allows the user to search the database for the advisor's name, email, and phone number using the student ID.
        - Accessible only by registered users; unregistered users are redirected to the login/sign-up page.
        - Users are redirected to the login page upon logging out.
    - **Second Page (login.php and signup.php)**:
        - Allows users to sign up by providing their name, student ID, email, and password.
        - Allows users to log in with their credentials.
        - Implements client-side validation for sign-up using JavaScript.

2. **PHP**:
    - Functions to read inputs from the form on the main page and query the database for the advisor's information.
    - Functions to authenticate registered users and manage sessions.
    - Functions to sign up users by adding their credentials to the database.
    - Functions to verify client-side validation before storing information in the database.

3. **Database**:
    - A database with at least two tables: a credentials table and an advisors' information table.
    - The credentials table contains fields for name, ID, email, and password.
    - The advisors' information table contains fields for name, phone number, email, lower-bound ID number, and upper-bound ID number.

### How to Run (Using XAMPP)
1. **Set Up XAMPP:**
   - Download and install XAMPP from https://www.apachefriends.org/index.html. 
   - Start the Apache and MySQL modules from the XAMPP control panel.
2. **Set Up the Database:**
   - Open your web browser and navigate to http://localhost/phpmyadmin. 
   - Enter the contents of the `DBschema` file
   - Run the provided SQL queries in the SQL tab to create the necessary tables.
3. **Set Up the Project Files:**
   - Copy the provided PHP files (signup.php, login.php, main.php, functions.php, config.php, validate_functions.js) into the htdocs directory of your XAMPP installation (usually found at C:\xampp\htdocs on Windows or /Applications/XAMPP/htdocs on macOS).
4. **Run the Application:**
   - Open your web browser and navigate to http://localhost/signup.php to create a new user account. 
   - Navigate to http://localhost/login.php to log in with the created user account. 
   - Upon successful login, you will be redirected to http://localhost/main.php to search for advisor information.
