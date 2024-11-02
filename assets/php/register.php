<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'user'; // Роль по умолчанию - пользователь

    // Проверяем, существует ли пользователь
    $checkUser = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $checkUser->execute([$email]);
    if ($checkUser->rowCount() > 0) {
        echo "Электронная почта уже зарегистрирована";
    } else {
        // Добавляем нового пользователя в базу данных
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $password, $role])) {
            echo "Регистрация успешна";
            header("Location: login.php");
        } else {
            echo "Регистрация не удалась";
        }
    }
}
?>
<html>
<head>
    <title>Регистрация</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Регистрация</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>
