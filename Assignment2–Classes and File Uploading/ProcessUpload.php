<?php

class NumberFileProcessor {
    public static function displayForm() {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Upload Number File</title>
        </head>
        <body>

        <form action="" method="post" enctype="multipart/form-data">
            Select a text file to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload File" name="submit">
        </form>

        </body>
        </html>';
    }

    public static function handleUpload() {
        if (!isset($_FILES['fileToUpload'])) {
            echo "No file uploaded.";
            return;
        }

        $fileName = $_FILES['fileToUpload']['tmp_name'];
        $content = file_get_contents($fileName);
        $digitContent = preg_replace("/\s+/", "", $content); // Remove all whitespace first

        if (strlen($digitContent) != 400) {
            echo "File is not formatted correctly.";
            return;
        }

        // The content is already validated for digits, directly use $digitContent for calculations
        echo self::calculateLargestProductAndFactorials($digitContent);
    }

    private static function calculateLargestProductAndFactorials($numberSequence) {
        $matrix = self::formatNumbersIntoMatrix($numberSequence);
        list($maxProduct, $sequence) = self::findLargestProduct($matrix);

        $factorialsSum = array_sum(array_map('self::computeFactorial', str_split($sequence)));
        return "Largest Product: $maxProduct — Sum of Factorial = $factorialsSum";
    }

    private static function formatNumbersIntoMatrix($content) {
        $matrix = [];
        foreach (str_split($content, 20) as $row) {
            $matrix[] = str_split($row);
        }
        return $matrix;
    }

    private static function findLargestProduct($matrix) {
        $directions = [
            [[0, 1]], // Right
            [[1, 0]], // Down
            [[1, 1]], // Diagonal right down
            [[-1, 1]] // Diagonal left down
        ];

        $maxProduct = 0;
        $bestSequence = '';

        foreach ($directions as $direction) {
            for ($row = 0; $row < 20; $row++) {
                for ($col = 0; $col < 20; $col++) {
                    foreach ($direction as $step) {
                        $product = 1;
                        $sequence = '';

                        for ($i = 0; $i < 5; $i++) {
                            $newRow = $row + $step[0] * $i;
                            $newCol = $col + $step[1] * $i;

                            if ($newRow >= 0 && $newRow < 20 && $newCol >= 0 && $newCol < 20) {
                                $product *= $matrix[$newRow][$newCol];
                                $sequence .= $matrix[$newRow][$newCol];
                            } else {
                                // Out of bounds; break the current direction
                                $product = 0;
                                break;
                            }
                        }

                        if ($product > $maxProduct) {
                            $maxProduct = $product;
                            $bestSequence = $sequence;
                        }
                    }
                }
            }
        }

        return [$maxProduct, $bestSequence];
    }

    private static function computeFactorial($digit) {
        $factorial = 1;
        for ($i = 2; $i <= $digit; $i++) {
            $factorial *= $i;
        }
        return $factorial;
    }
    public static function initiateTests() {
    echo "<br><br>Testing Calculate Largest Product Method<br><br>";

    // Good Test Case
    echo "Good Test Case: ";
    $goodSequence = str_repeat("1234567890", 40); // Correctly formatted 400-digit sequence
    echo self::calculateLargestProductAndFactorials($goodSequence) . "<br><br>";

    // Bad Test Case (Less than 400 digits)
    echo "Bad Test Case: ";
    $badSequence = str_repeat("12345", 80); // Incorrectly assumes bad formatting based on length; adjust as needed
    echo self::calculateLargestProductAndFactorials($badSequence) . "<br><br>";

    // Ugly Test Case (Non-digits included)
    echo "Ugly Test Case: ";
    $uglySequence = str_repeat("1a2b3c4d5e6f7g8h9i0j", 20); // Non-digit characters will be sanitized to '0', maintaining length
    echo self::calculateLargestProductAndFactorials($uglySequence) . "<br><br>";
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    NumberFileProcessor::handleUpload();
} else {
    NumberFileProcessor::displayForm();
}
NumberFileProcessor::initiateTests();

?>
