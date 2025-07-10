<?php
require 'functions.php';

$subscribers = json_decode(file_get_contents('subscribers.json'), true);
$tasks = get_pending_tasks();

foreach ($subscribers as $email => $info) {
    if ($info['verified']) {
        $taskList = "";
        foreach ($tasks as $task) {
            $taskList .= "- " . $task['task'] . "\n";
        }

        $unsubscribe_link = "http://yourdomain.com/src/unsubscribe.php?email=" . urlencode($email);
        $message = "Your pending tasks:\n$taskList\n\nUnsubscribe: $unsubscribe_link";

        mail($email, "Hourly Task Reminder", $message);
    }
}
