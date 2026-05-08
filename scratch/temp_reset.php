<?php
$pdo = new PDO('mysql:host=localhost;dbname=cms_saas','root','');
$hash = password_hash('06feb2024', PASSWORD_DEFAULT);
$pdo->prepare('UPDATE users SET password_hash = ? WHERE email = ?')->execute([$hash, 'gothxofficial@gmail.com']);
echo "Password reset for mohit (gothxofficial@gmail.com)\n";
