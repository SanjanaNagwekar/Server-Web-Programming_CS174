## Assignment 5: Authentication and Cookies

### Overview

This project involves the creation of a web application using HTML, PHP, and MySQL. The application allows users to sign up, log in, create posts, and view their posts. The application uses cookies for user authentication and personalization.

### Files Included

1. `config.php`: PHP script for database configuration.
2. `functions.php`: PHP script containing functions for handling user authentication and post management.
3. `index.php`: The main webpage displaying user greeting and posts.
4. `login.php`: PHP script for handling user login.
5. `signup.php`: PHP script for handling user sign-up.

### Functionality

1. **Webpage**:
    - Greets the user with "Hello!" if no user is logged in.
    - Greets the logged-in user with "Hello [user's name]!" using a cookie to get the user's name.
    - Allows users to sign up with a name, username, and password.
    - Allows users to log in with their credentials.
    - Once logged in, users can create posts with a title and content.
    - Displays the logged-in user's posts (title and content) from the database.
    - If no posts exist for the user, nothing is shown.

2. **PHP**:
    - Function to read and store the user's name in a cookie.
    - Function to read a title and content of a post and store it in the database (only if the user is logged in).
    - Handles user sign-up and login, ensuring exclusive access to each user's posts.
    - Securely stores passwords in the database.

3. **MySQL**:
    - Create a database with at least two tables: one for user credentials and one for user posts.
    - The credentials table contains fields for username and password.
    - The password is stored securely using hashing.

### How to Run (Using XAMPP)

1. **Set Up XAMPP**:
    - Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
    - Start the Apache and MySQL modules from the XAMPP control panel.

2. **Set Up the Database**:
    - Open your web browser and navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
    - Create a new database named `assignment5db`.
    - Run the following SQL queries in the SQL tab to create the necessary tables:

      ```sql
      CREATE DATABASE IF NOT EXISTS assignment5db;
 
      USE assignment5db;
 
      CREATE TABLE IF NOT EXISTS users (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL,
          username VARCHAR(255) UNIQUE NOT NULL,
          password VARCHAR(255) NOT NULL
      );
 
      CREATE TABLE IF NOT EXISTS posts (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT NOT NULL,
          title VARCHAR(255) NOT NULL,
          content TEXT NOT NULL,
          FOREIGN KEY (user_id) REFERENCES users(id)
      );
      ```

3. **Set Up the Project Files**:
    - Copy the provided PHP files (`config.php`, `functions.php`, `index.php`, `login.php`, `signup.php`) into the `htdocs` directory of your XAMPP installation (usually found at `C:\xampp\htdocs` on Windows or `/Applications/XAMPP/htdocs` on macOS).
    - Ensure the `example_transactions.txt` file is also placed in the `htdocs` directory if needed for testing.

4. **Run the Application**:
    - Open your web browser and navigate to [http://localhost/index.php](http://localhost/index.php) to view the greeting and user posts.
    - Navigate to [http://localhost/signup.php](http://localhost/signup.php) to create a new user account.
    - Navigate to [http://localhost/login.php](http://localhost/login.php) to log in with the created user account and create posts.
