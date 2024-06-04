## Assigment 2: Classes and File Uploading

### Overview

This project involves the creation of a web application using HTML and PHP that allows users to upload a `.txt` file containing a 20x20 grid of numbers. The application processes the file to find the largest product of 5 adjacent numbers in the grid and calculates the sum of the factorials of the digits in that product.

### Files Included

1. `FileUpload.html`: The HTML form that allows users to upload a `.txt` file.
2. `ProcessUpload.php`: The PHP script that processes the uploaded file.
3. `Test.txt`: A sample text file containing a 20x20 grid of numbers.

### Functionality

1. **File Upload**:
    - The user uploads a `.txt` file via an HTML form.
    - Only `.txt` files are allowed.
    - The file is uploaded using `POST` method to `ProcessUpload.php`.

2. **File Processing**:
    - The PHP script checks if the uploaded file contains exactly 400 numbers.
    - If the file is incorrectly formatted, the user is notified.
    - Any non-numeric characters in the file are replaced with `0` to maintain the order.
    - The script finds the 5 adjacent numbers that produce the largest product.
    - The sum of the factorials of the digits in this product is calculated and displayed.

3. **Error Handling**:
    - The script informs the user if the uploaded file does not contain exactly 400 numbers.
    - Invalid characters in the file are handled by replacing them with `0`.

4. **Testing**:
    - A tester function is included to verify the behavior of the `largest_product()` function with good, bad, and edge cases.

### Example

For example, if the product is `50476`, the calculation will be:

Largest product: 5 * 0 * 4 * 7 * 6 = 50476

Sum of Factorials: 5! + 0! + 4! + 7! + 6! = 726000

### How to Run

1. **Set Up XAMPP**:
   - Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   - Start the Apache module from the XAMPP control panel.

2. **Set Up the Project Files**:
   - Copy the provided files (`FileUpload.html`, `ProcessUpload.php`, `Test.txt`) into the `htdocs` directory of your XAMPP installation (usually found at `C:\xampp\htdocs` on Windows or `/Applications/XAMPP/htdocs` on macOS).

3. **Run the Application**:
   - Open your web browser and navigate to [http://localhost/FileUpload.html](http://localhost/FileUpload.html).
   - Use the form to upload a `.txt` file containing a 20x20 grid of numbers.
   - The results will be processed and displayed by `ProcessUpload.php`.