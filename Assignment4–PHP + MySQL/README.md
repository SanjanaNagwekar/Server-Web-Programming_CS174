## Assignment 4: PHP + MySQL

### Overview

This project involves the creation of a web application using HTML, PHP, and MySQL. The application allows users to upload a text file containing a sequence of transactions and input their email address to update their balance. The webpage displays the balance of all users from the database every time it loads.

### Files Included

1. `fetchrow.php`: PHP script to fetch data from the database.
2. `login.php`: PHP script for handling user login and processing the uploaded transactions.
3. `example_transactions.txt`: A sample text file containing a sequence of transactions for testing.

### Functionality

1. **Webpage**:
    - Allows users to upload a `.txt` file containing transaction sequences.
    - Users can input their email address to identify themselves in the database.
    - Displays the balance of all users every time the page loads.

2. **PHP**:
    - A function reads the user's email input and stores it in a table (field: `Email`).
    - Reads the content of the uploaded file, updates the balance for the associated email, and stores it in the table (field: `Balance`).
    - Handles edge cases such as non-existing emails by adding them to the database with an initial balance of zero dollars.
    - Informs users of invalid transactions and skips them while processing the rest.

3. **MySQL**:
    - Create a database with a table to store emails and their associated balances.
    - Necessary SQL queries (`INSERT`, `SELECT`) are prepared within the PHP application to handle data storage and retrieval.

### How to Run (Using XAMPP)

1. **Set Up XAMPP**:
    - Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
    - Start the Apache and MySQL modules from the XAMPP control panel.

2. **Set Up the Database**:
    - Open your web browser and navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
    - Create a new database named `user_transactions`.
    - Run the provided SQL queries in the SQL tab to create the necessary table:

      ```sql
      CREATE DATABASE IF NOT EXISTS user_transactions;

      USE user_transactions;

      CREATE TABLE IF NOT EXISTS users (
          id INT AUTO_INCREMENT PRIMARY KEY,
          email VARCHAR(255) UNIQUE NOT NULL,
          balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00
      );
      ```
3. **Set Up the Project Files**:
    - Copy the provided PHP files (`fetchrow.php`, `login.php`) and the `example_transactions.txt` file into the `htdocs` directory of your XAMPP installation (usually found at `C:\xampp\htdocs` on Windows or `/Applications/XAMPP/htdocs` on macOS).

4. **Run the Application**:
    - Open your web browser and navigate to [http://localhost/fetchrow.php](http://localhost/fetchrow.php) to fetch and display user balances.

