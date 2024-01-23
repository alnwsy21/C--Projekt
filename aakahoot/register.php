<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Öffne die CSV-Datei zum Schreiben (falls nicht vorhanden, wird sie erstellt)
    $file = fopen('login.csv', 'a');

    // Schreibe die neuen Benutzerdaten in die CSV-Datei
    fputcsv($file, array($username, $password, 'user'));

    // Schließe die Datei
    fclose($file);

    // Optional: Weiterleitung zu einer Erfolgsseite oder zur Login-Seite
    // header('Location: registration_success.php');  // Weiterleitung zu Erfolgsseite
    header('Location: login.php');  // Weiterleitung zur Login-Seite
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzer erstellen</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #99ccff, #ffccff); /* Hintergrund mit sanftem Verlauf */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
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

        button {
            padding: 12px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: calc(100% - 20px);
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="registerForm" action="register.php" method="post">
            <h2>Benutzer erstellen</h2>
            <input type="text" id="username" name="username" placeholder="Benutzername" required>
            <input type="password" id="password" name="password" placeholder="Passwort" required>
            <button type="submit">Benutzer erstellen</button>
        </form>
    </div>
</body>
</html>
