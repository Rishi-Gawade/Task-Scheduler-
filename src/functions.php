<?php

function getAllTasks() {
    $tasks = [];
    if (file_exists("tasks.txt")) {
        $lines = file("tasks.txt", FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $parts = explode("|", $line);
            if (count($parts) !== 3) continue;
            list($id, $name, $completed) = $parts;
            $tasks[] = [
                'id' => $id,
                'name' => $name,
                'completed' => $completed
            ];
        }
    }
    return $tasks;
}

function addTask($task_name) {
    $task_name = trim($task_name);
    if ($task_name === "") return false;

    $tasks = getAllTasks();
    foreach ($tasks as $task) {
        if (strcasecmp($task['name'], $task_name) === 0) {
            return false; // duplicate
        }
    }

    $id = time();
    $new_line = "$id|$task_name|0\n";
    $existing = file_exists("tasks.txt") ? file_get_contents("tasks.txt") : "";
    file_put_contents("tasks.txt", $new_line . $existing);

    return true;
}

function markTaskAsCompleted($task_id, $is_completed) {
    $tasks = getAllTasks();
    $new_data = "";

    foreach ($tasks as $task) {
        if ($task['id'] == $task_id) {
            $task['completed'] = $is_completed;
        }
        $new_data .= "{$task['id']}|{$task['name']}|{$task['completed']}\n";
    }

    file_put_contents("tasks.txt", $new_data);
}

function deleteTask($task_id) {
    $tasks = getAllTasks();
    $new_data = "";

    foreach ($tasks as $task) {
        if ($task['id'] != $task_id) {
            $new_data .= "{$task['id']}|{$task['name']}|{$task['completed']}\n";
        }
    }

    file_put_contents("tasks.txt", $new_data);
}

function generateVerificationCode() {
    return rand(100000, 999999);
}

function subscribeEmail($email) {
    $email = strtolower(trim($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    // Check if already subscribed
    if (file_exists("subscribers.txt")) {
        $subs = file("subscribers.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($email, $subs)) {
            return "You're already subscribed.";
        }
    }

    // Check if already pending
    $lines = file_exists("pending_subscriptions.txt")
        ? file("pending_subscriptions.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
        : [];

    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) !== 2) continue;
        if (trim($parts[0]) === $email) {
            return "You've already requested verification. Check your email.";
        }
    }

    $code = generateVerificationCode();
    $entry = "$email|$code\n";
    file_put_contents("pending_subscriptions.txt", $entry, FILE_APPEND);

    $verify_link = "http://localhost/task-scheduler/src/verify.php?email=" . urlencode($email) . "&code=" . $code;

    $subject = "Verify your email for Task Manager";
    $message = "Hi,\n\nClick below to verify your email:\n$verify_link\n\nThanks!";
    $log = "TO: $email\nSUBJECT: $subject\n\n$message\n----------------\n";

    file_put_contents("email_log.txt", $log, FILE_APPEND);

    return "Verification email sent to $email.";
}

function verifySubscription($email, $code) {
    $lines = file_exists("pending_subscriptions.txt")
        ? file("pending_subscriptions.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
        : [];

    $new_lines = "";
    $verified = false;

    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) !== 2) continue;

        list($e, $c) = $parts;
        if ($e === $email && $c == $code) {
            $verified = true;
            continue; // skip this line from new pending file
        }
        $new_lines .= $line . "\n";
    }

    if ($verified) {
        file_put_contents("pending_subscriptions.txt", $new_lines);
        file_put_contents("subscribers.txt", $email . "\n", FILE_APPEND);
        return "✅ Subscription verified successfully!";
    } else {
        return "❌ Invalid verification code or email.";
    }
}

function sendTaskReminders() {
    if (!file_exists("subscribers.txt")) return;

    $subscribers = file("subscribers.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $pending_tasks = array_filter(getAllTasks(), fn($task) => $task['completed'] == 0);

    if (empty($pending_tasks)) return;

    foreach ($subscribers as $email) {
        sendTaskEmail($email, $pending_tasks);
    }
}

function sendTaskEmail($email, $pending_tasks) {
    $subject = "⏰ Your Pending Task Reminders";
    $message = "Hi,\n\nHere are your pending tasks:\n";

    foreach ($pending_tasks as $task) {
        $message .= "- " . $task['name'] . "\n";
    }

    $encodedEmail = urlencode(base64_encode($email));
    $unsubscribeLink = "http://localhost/task-scheduler/src/unsubscribe.php?email=$encodedEmail";
    $message .= "\nTo unsubscribe from reminders, click here:\n$unsubscribeLink";

    $log = "TO: $email\nSUBJECT: $subject\n\n$message\n----------------\n";
    file_put_contents("email_log.txt", $log, FILE_APPEND);
}
