<?php
session_start();
include_once("base/db1.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    // Rechercher dans la table "patient"
    $sql_patient = "SELECT * FROM patient WHERE identifiant=? AND password=?";
    $stmt = $conn->prepare($sql_patient);
    $stmt->execute([$identifiant, $password]);
    $result_patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result_patient) {
        $_SESSION['role'] = "patient";
        $_SESSION['identifiant'] = $result_patient['identifiant'];
        $_SESSION['id'] = $result_patient['id'];
        header("Location: patient/page3deprojet.html");
        exit();
    }

    // Rechercher dans la table "medecin"
    $sql_medecin = "SELECT * FROM medecin WHERE identifiant=? AND password=?";
    $stmt = $conn->prepare($sql_medecin);
    $stmt->execute([$identifiant, $password]);
    $result_medecin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result_medecin) {
        $_SESSION['role'] = "medecin";
        $_SESSION['identifiant'] = $result_medecin['identifiant'];
        $_SESSION['id'] = $result_medecin['id'];
        header("Location: medecin/pageresption.php");
        exit();
    }

    $error = "رقم التسجيل أو كلمة المرور غير صحيحة";
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <link rel="icon" type="image/jpg" href="images/logoapp.jpg">
    <title id="title">MEDICAL EASE</title>
    <meta charset="UTF-8">
    <meta name="description" content="carnet de santé médicale">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
            direction: rtl;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        form {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            z-index: 1;
        }
        form:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border: solid 2px rgb(47, 143, 140)
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            background-color: white;
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .botton {
            background-color: #007872;
            width: 100%;
            height: 30px;
            border-radius: 4px;
            color: white;
            border: none;
            cursor: pointer;
        }

       
    </style>
</head>
<body>



<form method="post">
    <h3>تسجيل الدخول</h3>
    <?php if (!empty($error)): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>
    <label for="utilisateur">رقم التسجيل</label><br>
    <input type="text" name="identifiant" required><br>

    <label for="password">كلمة المرور</label><br>
    <input type="password" name="password" required><br><br>

    <input class="botton" type="submit" value="الدخول">
</form>

</body>
</html>
