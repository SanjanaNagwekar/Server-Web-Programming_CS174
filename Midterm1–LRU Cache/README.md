# README

## Midterm #1 - LRU Cache

### Overview

This project involves the creation of a PHP class implementing an LRU (Least Recently Used) cache. The class includes methods for adding, retrieving, and resetting cache values, as well as handling various edge cases. The web application allows users to upload a text file containing cache operations and displays the results.

### Files Included

1. `LRUCache.php`: PHP class implementing the LRU cache.
2. `test.txt`: A sample text file containing a sequence of cache operations.

### Functionality

1. **PHP Class Methods**:
    - **Constructor**: Initializes the LRU cache with a positive size `cache_size`.
    - **is_full()**: Checks if the cache is full. Can only be called in the `put()` function.
    - **get(key)**: Takes a parameter `key`. Returns the value of the `key` if it exists, otherwise returns -1.
    - **reset()**: Removes all data in the cache. Can only be called in the `put()` function.
    - **put(key, value, reset)**:
        - Calls `reset()` if `reset` is true.
        - Updates the value of the `key` if it exists. Otherwise, adds the key-value pair to the cache.
        - If the number of keys exceeds `cache_size`, evicts the least recently used key.
        - Ensures `value` is a positive integer. If `value` is negative/float, displays the current cache and prints “Not accept negative”. If `value` is of the wrong type, displays the current cache and prints “Not accept <type>”.
    - **tester_function()**: Asserts if the cache is correct after each `put()` call and prints the result.

2. **Web Application**:
    - Allows users to upload a text file containing cache operations.
    - Displays each line and the resulting cache state on the webpage.

### Example Operations

- **Constructor**:
    ```php
    $cache = new LRUCache(2); // {}
    ```
- **Put**:
    ```php
    $cache->put(1, 1, False); // {1: 1}
    $cache->put(2, 2, False); // {2: 2, 1: 1}
    ```
- **Get**:
    ```php
    $cache->get(1); // return 1, {1: 1, 2: 2}
    ```
- **Evict and Reset**:
    ```php
    $cache->put(3, 3, False); // evict key 2, {3: 3, 1: 1}
    $cache->put(4, 4, True);  // reset cache, {4: 4}
    ```

### Example Tester Output

```php
tester_function() {
    // Assertions and printed output
    echo "Is cache after calling put(1, 1, False) equal to {1: 1}? — Yes, test passed\n";
    echo "Is cache after calling put(2, 2, False) equal to {2: 2, 1: 1}? — Yes, test passed\n";
    // Additional test cases...
}
```

### How to Run (Using XAMPP)
1. **Set Up XAMPP:**
   - Download and install XAMPP from https://www.apachefriends.org/index.html. 
   - Start the Apache module from the XAMPP control panel.
2. **Set Up the Project Files:**
   - Copy the provided PHP file (LRUCache.php) and the test.txt file into the htdocs directory of your XAMPP installation (usually found at C:\xampp\htdocs on Windows or /Applications/XAMPP/htdocs on macOS). 
3. **Run the Application:**
   - Open your web browser and navigate to http://localhost/LRUCache.php. 
   - Ensure the PHP file reads from test.txt and processes the cache operations accordingly. 
   - The resulting cache state will be displayed on the webpage.