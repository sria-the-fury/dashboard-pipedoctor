<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ./');
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login - PipeDoctor Dashboard</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="index.css" />

</head>

<body>
    <!-- login page -->
    <div id="login-container" style="display: block;">
        <div class="login-area">
            <div class="login-form-area">
                <div class="login-card backdrop-blur-m">
                    <h3>Dashboard Login</h3>

                    <?php
                    // show error messages
                    if (isset($_GET['error'])) {
                        $error_msg = $_GET['error'] == 'invalid' ? 'Invalid email or password.' : 'Please fill in all fields.';
                        echo '<p style="color: red; text-align: center;">' . $error_msg . '</p>';
                    }
                    if (isset($_GET['logout'])) {
                        echo '<p style="color: green; text-align: center;">You have been logged out.</p>';
                    }
                    ?>

                    <form id="login-form" action="php/login_process.php" method="POST">
                        <div class="form-group">
                            <label for="email">Registered Email:</label>
                            <input type="email" id="email" name="email" required />
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required />
                        </div>

                        <div class="button-and-text">
                            <button type="submit">Login</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>