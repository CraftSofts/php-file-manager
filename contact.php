<?php
include '' . __DIR__ . '/inc/core.php';
printHeader('Contact - File Manager');
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$message = $_REQUEST['message'];
?>
<h4>Contact Admin</h4>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($name) || empty($email) || empty($message)) {
        $error[] = 'All fields are required';
    } else {
        if (strlen($name) < 3 || strlen($name) > 20) {
            $error[] = 'Your Name is invalid';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Your email address is invalid';
        } elseif (strlen($message) < 15 || strlen($message) > 1000) {
            $error[] = 'Your message is in invalid length';
        } else {
            $form = 'no';
			$to = base64_decode('bWVyYWpiZDdAZ21haWwuY29t');
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
            $subject = "Feedback for SM File Manager from $name <$email>";

            if (mail($to, $subject, $message, $headers)) {
                echo '<p><span class="green-text"><i class="material-icons">check_circle</i> Your message has been sent successfuly!</span>';
            } else {
                echo '<p><span class="red-text"><i class="material-icons">error</i> Your message can\'t be sent! Please check if your server supports <code> <a href="https://www.php.net/manual/en/function.mail.php">mail()</a></code> function and try again.</span>';
            }
        }
    }
}
if ($form != 'no') {
    if (!empty($error)) {
        foreach ($error as $error) {
            echo '<div class="red-text bold-text"><i class="material-icons">error</i> ' . $error . '</div>';
        }

    }
    ?>
<p><i class="material-icons">info</i> If you have any query, bug report, suggestions, feature request or anything related to this software, feel free to contact me. You can also send me wishes, thanks or just "hi". I will be glad to hear from you. In case of bug reports, please include details of the bugs you encountered. Also, include additional details which can be helpful (e.g.: php version, server os etc).</p>
<form action="contact.php" method="post">
	<div class="input-field"><input type="text" name="name" required><label for="name">Your name</label></div>
	<div class="input-field"><input type="email" name="email" required><label for="email">Your email</label></div>
	<div class="input-field"><textarea class="materialize-textarea" name="message" required></textarea><label for="message">Your message</label></div>
	<button type="submit" class="btn waves-effect waves-light green">Send</button>
</form>
<?php
}
echo '<br/>';
printFooter();
?>