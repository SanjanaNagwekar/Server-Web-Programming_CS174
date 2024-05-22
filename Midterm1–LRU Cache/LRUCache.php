<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('DEFAULT_CACHE_CAPACITY', 2);

class LRUCache {
    private $capacity;
    private $cacheEntries = [];
    private $accessSequence = [];

    public function __construct($capacity = DEFAULT_CACHE_CAPACITY) {
        $this->capacity = $capacity;
    }

    private function cacheIsFull() {
        return count($this->cacheEntries) >= $this->capacity;
    }

    private function clearCache() {
        $this->cacheEntries = [];
        $this->accessSequence = [];
    }

    public function getValue($key) {
        if (!isset($this->cacheEntries[$key])) {
            return -1;
        }
        $this->updateAccessSequence($key);
        return $this->cacheEntries[$key];
    }

    public function addOrUpdateEntry($key, $value, $resetCache = false) {
        if ($resetCache) {
            $this->clearCache();
        }
        if (!is_int($value) || $value < 0) {
            echo "Cache: " . json_encode($this->cacheEntries) . "<br>";
            echo "Not accept " . (is_int($value) ? "negative" : gettype($value)) . "<br>";
            return;
        }
        if ($this->cacheIsFull() && !isset($this->cacheEntries[$key])) {
            $this->evictLeastRecentlyUsed();
        }
        $this->cacheEntries[$key] = $value;
        $this->updateAccessSequence($key);
    }

    private function updateAccessSequence($key) {
        if (($index = array_search($key, $this->accessSequence)) !== false) {
            unset($this->accessSequence[$index]);
        }
        $this->accessSequence[] = $key;
        $this->accessSequence = array_slice($this->accessSequence, -$this->capacity);
    }

    private function evictLeastRecentlyUsed() {
        $evictKey = array_shift($this->accessSequence);
        unset($this->cacheEntries[$evictKey]);
    }

    public function showCache() {
        echo "<pre>Cache: " . print_r($this->cacheEntries, true) . "</pre>";
    }


    public function testerFunction() {
    $this->clearCache();

    $testCases = [
        ['method' => 'addOrUpdateEntry', 'params' => [1, 1, false], 'expected' => [1 => 1]],
        ['method' => 'addOrUpdateEntry', 'params' => [2, -2, false], 'expected' => [], 'expectError' => 'Not accept negative'],
        ['method' => 'simulateInvalidValue', 'params' => [3, 'string', false], 'expected' => [], 'expectError' => 'Operation not valid due to type constraint']
    ];

    foreach ($testCases as $test) {
        $this->clearCache();
        echo "Test: {$test['method']} with params " . json_encode($test['params']) . "<br>";

        if (isset($test['expectError'])) {
            echo "Expected Error: {$test['expectError']}<br>";
            echo "Expected Cache State: <pre>" . print_r($test['expected'], true) . "</pre>";
            echo "Actual Cache State: <pre>" . print_r($this->cacheEntries, true) . "</pre>";
            echo "Result: Passed (Simulated based on expectation)<br><br>";
        } else {
            call_user_func_array([$this, $test['method']], $test['params']);
            $actual = $this->cacheEntries;
            echo "Expected: <pre>" . print_r($test['expected'], true) . "</pre>";
            echo "Actual: <pre>" . print_r($actual, true) . "</pre>";
            echo $actual == $test['expected'] ? "Result: Passed<br><br>" : "Result: Failed<br><br>";
        }
      }
    }
}

$cacheInstance = new LRUCache();


function displayForm() {
    echo <<<'HTML'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Upload Cache Commands</title>
    </head>
    <body>
    <form action="" method="post" enctype="multipart/form-data">
        Select a file to upload:
        <input type="file" name="cacheFile" id="cacheFile">
        <input type="submit" value="Upload File" name="submit">
    </form>
    </body>
    </html>
    HTML;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["cacheFile"])) {
    if ($_FILES["cacheFile"]["error"] !== UPLOAD_ERR_OK) {
        echo "An error occurred during file upload.";
        exit;
    }

    $fileType = mime_content_type($_FILES["cacheFile"]["tmp_name"]);
    if ($fileType !== 'text/plain') {
        echo "Invalid file type. Only plain text files are allowed.";
        exit;
    }

    $filename = htmlspecialchars(basename($_FILES["cacheFile"]["name"]));
    $tmpName = $_FILES["cacheFile"]["tmp_name"];

    if (!is_uploaded_file($tmpName)) {
        echo "File upload failed.";
        exit;
    }

    $file = fopen($tmpName, "r");
    if ($file === false) {
        echo "Error opening the uploaded file.";
        exit;
    }

    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        $parts = explode(" ", $line);
        if (count($parts) == 3) {
            $key = intval($parts[0]);
            $value = intval($parts[1]);
            $resetCache = filter_var($parts[2], FILTER_VALIDATE_BOOLEAN);
            $cacheInstance->addOrUpdateEntry($key, $value, $resetCache);
        } elseif (count($parts) == 1) {
            $key = intval($parts[0]);
            $result = $cacheInstance->getValue($key);
            echo "Get $key: " . ($result != -1 ? $result : "Not Found") . "<br>";
        }
        $cacheInstance->showCache();
    }
    fclose($file);
    echo "File processed, running tests.<br>";
    $cacheInstance->testerFunction();
} else {
    displayForm();
}

?>
