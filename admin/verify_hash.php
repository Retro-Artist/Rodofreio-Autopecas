<?php
// verify_hash.php
$password = 'admin';
$hash = '$2y$10$8zUUpRNylGwEuIXZPJOWwu9uRafFtTIJKGWFxT2IwcBIjRfTY05x.';

if (password_verify($password, $hash)) {
    echo "Password verification successful!\n";
} else {
    echo "Password verification failed!\n";
}
?>