
<?php
// Подключение к базе данных
include('config.php');

// Проверка, вошел ли администратор
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Забанить или разбанить пользователя
if (isset($_GET['ban'])) {
    $user_id = $_GET['ban'];
    $stmt = $pdo->prepare("UPDATE users SET banned = 1 WHERE id = ?");
    $stmt->execute([$user_id]);
} elseif (isset($_GET['unban'])) {
    $user_id = $_GET['unban'];
    $stmt = $pdo->prepare("UPDATE users SET banned = 0 WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Получить список пользователей
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Администратора - Управление пользователями</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Стиль для адаптации строк таблицы на мобильных устройствах */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            .table tr {
                margin-bottom: 15px;
                border-bottom: 2px solid #ddd;
            }
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <!-- Bootstrap Navbar with burger menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Админ-Панель</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movie-details.html">Фильмы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subscription.html">Подписки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Пользователи</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Управление пользователями</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Имя пользователя</th>
                        <th scope="col">Email</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                    <tr>
                        <td data-label="Имя пользователя"><?php echo $user['username']; ?></td>
                        <td data-label="Email"><?php echo $user['email']; ?></td>
                        <td data-label="Роль"><?php echo $user['role']; ?></td>
                        <td data-label="Статус"><?php echo $user['banned'] ? '<span class="badge badge-danger">Забанен</span>' : '<span class="badge badge-success">Активен</span>'; ?></td>
                        <td data-label="Действие">
                            <?php if ($user['banned']) { ?>
                                <a href="admin_panel.php?unban=<?php echo $user['id']; ?>" class="btn btn-sm btn-success">Разбанить</a>
                            <?php } else { ?>
                                <a href="admin_panel.php?ban=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Забанить</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS и зависимости -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
