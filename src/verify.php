<?php
require 'functions.php';

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];

    $message = verifySubscription($email, $code);
    echo "<h2>$message</h2>";
} else {
    echo "<h2>Invalid verification request.</h2>";
}
?>
