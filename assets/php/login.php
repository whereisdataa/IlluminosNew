
<?php
session_start();
include('config.php');

$error_message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Запрос для получения данных пользователя
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка, существует ли пользователь и верен ли пароль
    if ($user && password_verify($password, $user['password'])) {
        if ($user['banned']) {
            $error_message = "Ваш аккаунт заблокирован. Доступ на сайт запрещён.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Перенаправление в зависимости от роли
            if ($user['role'] == 'admin') {
                header('Location: admin_panel.php');
            } else {
                $_SESSION['login_success'] = true;
    header('Location: /main.html');
            }
            exit();
        }
    } else {
        $error_message = "Неверный email или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Вход</h2>

        <!-- Вывод сообщения об ошибке, если оно есть -->
        <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <!-- Форма входа -->
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email адрес</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Введите email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Войти</button>
        </form>
    </div>

    <!-- Bootstrap JS и зависимости -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
