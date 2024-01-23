<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CipherAccess</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url("login2.png");
            background-size: cover;
            background-position: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            margin-top: -130px;
        }

        form {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5em;
        }

        input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(100% - 20px);
            box-sizing: border-box;
        }

        button,
        .guest-button,
        .register-button {
            padding: 12px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: calc(100% - 20px);
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        button:hover,
        .guest-button:hover,
        .register-button:hover {
            background-color: #2980b9;
        }

        .register-button {
            padding: 8px; /* smaller padding for "Registrieren" button */
            margin-top: 10px;
        }

    </style>
</head>

<body>

    <div class="container">
        <form id="cipherAccessForm" action="login.php" method="post">
            <h2>Login Daten!</h2>
            <input type="text" id="username" name="username" placeholder="Benutzername" required autocomplete="off">
            <input type="password" id="password" name="password" placeholder="Passwort" required>
            <button type="submit">Sicherer Zugriff</button>
            <br>
            <button class="guest-button" onclick="location.href='ask.php'">Ohne Anmeldung</button>
            <button class="register-button" onclick="location.href='register.php'">Registrieren</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $file = fopen('login.csv', 'r');

        while (($line = fgetcsv($file)) !== false) {
            if ($line[0] === $username && $line[1] === $password) {
                if ($line[2] === 'admin') {
                    fclose($file);
                    header('Location: Edit2.php');
                    exit();
                } elseif ($line[2] === 'user') {
                    fclose($file);
                    header('Location: ask.php');
                    exit();
                }
            }
        }

        fclose($file);

        // Redirect to login page if login fails
        header('Location: login.php');
        exit();
    }
    ?>
</body>

</html>
