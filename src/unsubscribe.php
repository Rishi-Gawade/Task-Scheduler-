<?php
if (isset($_GET['email'])) {
    $decoded = base64_decode(urldecode($_GET['email']));
    $email = trim($decoded);

    $file = "subscribers.txt";
    $new_list = "";

    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (trim($line) !== $email) {
                $new_list .= $line . "\n";
            }
        }
        file_put_contents($file, $new_list);
    }

    echo "<h2>You have been unsubscribed from task reminders.</h2>";
} else {
    echo "<h2>Invalid request.</h2>";
}
?>
