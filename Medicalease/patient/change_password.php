<?php
session_start();
require_once'../base/db1.php';
require_once'header.html';
$identifiant = $_SESSION['identifiant'];

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if ($new_password !== $confirm_password) {
        $error = "كلمة المرور الجديدة غير متطابقة";
    } else {
        // Récupération du patient
        $sql = "SELECT * FROM patient WHERE identifiant =? AND password=?" ;
        $stmt = $conn->prepare($sql);
        $stmt->execute([$identifiant,$current_password]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe actuel
        if (!$patient) {
            $error = "كلمة المرور الحالية غير صحيحة";
        } else {
            // Hachage du nouveau mot de passe
            //$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Mise à jour du mot de passe
            $update_sql = "UPDATE patient SET password =? WHERE identifiant =?";
            $update_stmt = $conn->prepare($update_sql);
            $success=$update_stmt->execute([$new_password,$identifiant]);
            if ($success) {
                header("Location: desinfos.php");
                exit();
            } else {
                $error = "حدث خطأ أثناء تحديث كلمة المرور";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <link rel="icon" type="image/jpg" href="../images/logoapp.jpg">
    <title id="title">MEDICAL EASE</title>
    <meta charset="UTF-8">
    <meta name="description" content="carnet de santé médicale">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            direction: rtl;
        }
        .password-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #007872;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            background-color: #007872;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
        }
        .btn:hover {
            background-color: #00635e;
        }
        .error {
            color: #d9534f;
            margin-bottom: 20px;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007872;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <h1><i class="fas fa-key"></i> تغيير كلمة المرور</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="identifiant" value="<?= htmlspecialchars($identifiant) ?>">
            
            <div class="form-group">
                <label for="current_password">كلمة المرور الحالية</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">كلمة المرور الجديدة</label>
                <input type="password" id="new_password" name="new_password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">تأكيد كلمة المرور الجديدة</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </div>
            
            <button type="submit" class="btn">تحديث كلمة المرور</button>
        </form>
        
        <a href="patient_info.php?id=<?= urlencode($identifiant) ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> العودة إلى صفحة المعلومات
        </a>
    </div>
</body>
</html>