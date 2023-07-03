<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the string value sent from Java
    $message = isset($_POST["message"]) ? $_POST["message"] : "";

    // Process the string as needed
    if (!empty($message)) {
        // Read the existing messages from the file
        $messages = file("message.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        // Check if the player's name is already present in the messages
        $playerExists = false;
        foreach ($messages as $index => $existingMessage) {
            $existingValues = explode("|", $existingMessage);
            $existingPlayer = isset($existingValues[0]) ? trim($existingValues[0]) : "";
            
            if ($existingPlayer === getPlayerName($message)) {
                // Update the existing message with the new message
                $messages[$index] = $message;
                $playerExists = true;
                break;
            }
        }
        
        // If the player's name doesn't exist, append the new message
        if (!$playerExists) {
            $messages[] = $message;
        }
        
        // Save the updated messages to the file
        file_put_contents("message.txt", implode(PHP_EOL, $messages));

        echo $message;
    } else {
        echo "No message received.";
    }
} else {
    // Check if the message file exists
    if (file_exists("message.txt")) {
        // Read the messages from the file
        $messages = file("message.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!empty($messages)) {
            // Display each message
            foreach ($messages as $message) {
                // Split the message by pipe (|) to extract individual values
                $values = explode("|", $message);

                // Process each value and apply CSS class if necessary
                $formattedValues = [];
                $threshold = 50; // Adjust the threshold as needed
                foreach ($values as $value) {
                    // Check if the value is a number
                    if (is_numeric(trim($value))) {
                        // Apply CSS class based on the value
                        $cssClass = (trim($value) > $threshold) ? "high-value" : "normal-value";

                        // Wrap the value in a span element with the CSS class
                        $formattedValues[] = '<span class="' . $cssClass . '">' . trim($value) . '</span>';
                    } else {
                        $formattedValues[] = trim($value);
                    }
                }

                // Echo the formatted values joined by pipe (|)
                echo implode(" | ", $formattedValues);
                echo "<br>";
                echo "<br>";
            }
        } else {
            echo "No messages received.";
        }
    } else {
        echo "No message received.";
    }
}

// Function to extract the player's name from the message
function getPlayerName($message) {
    $values = explode("|", $message);
    return isset($values[0]) ? trim($values[0]) : "";
}
?>
