<?php
session_start();
require_once'../base/db1.php';
require_once'header.html';

// Fonction pour vérifier le mot de passe
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Récupération de l'identifiant
$identifiant=$_SESSION['identifiant'];

if (!$identifiant) {
    die("Aucun identifiant fourni.");
}

$sql = "SELECT * FROM patient WHERE identifiant = :identifiant";
$stmt = $conn->prepare($sql); //Prépare la requête SQL sans l'exécuter immédiatement.

// Liaison des paramètres
$stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR); // Associe le paramètre nommé :identifiant à la variable PHP $identifiant.
// Exécution de la requête
$stmt->execute();//Exécute la requête préparée avec la valeur de $identifiant.Si l'identifiant existe, la requête retourne le patient correspondant.
$patient = $stmt->fetch(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <link rel="icon" type="image/jpg" href="images/logoapp.jpg">
    <title id="title">MEDICAL EASE</title>
    <meta charset="UTF-8">
    <meta name="description" content="carnet de santé médicale">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .main-content {
            flex: 1;
            padding: 30px;
        }
        .section {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        .page-title {
            font-size: 24px;
            margin-bottom: 30px;
            color:rgb(47, 143, 140);
            padding-bottom: 10px;
            border-bottom: 2px solid rgb(22, 31, 37);
            text-align: right;
        }
        .info-sections{
            font-size: 18px;
            margin: 15px 0;
            color: #2c3e50;
            text-align: right;
        }
        .subsection-title {
            font-size: 18px;
            margin: 15px 0;
            color: #007872;
            text-align: right;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 15px;
            text-align: right;
        }
        
        .divider {
            height: 1px;
            background-color: #ecf0f1;
            margin: 20px 0;
        }
        .info-value {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #ecf0f1;
}

.edit-password-btn {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    margin-right: 8px;
    padding: 4px;
    font-size: 14px;
    transition: color 0.3s;
}

.edit-password-btn:hover {
    color: #0056b3;
    transform: scale(1.1);
}   
        body{
            background-color:white;
            font-family: Arial, sans-serif;
            direction: rtl; 
            
        
        }
    </style>
<body>
    <?php if ($patient): ?>
        <main class="main-content">
            <h1 class="page-title">معلومات المريض</h1>

            <div class="section">
                <h2 class="subsection-title">المعلومات الشخصية</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">الاسم</div>
                        <div class="info-value"><?=htmlspecialchars($patient["nom"])?></div>
                    </div>
        
                    <div class="info-item">
                        <div class="info-label">اللقب</div>
                        <div class="info-value"><?=htmlspecialchars($patient["prenom"])?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">تاريخ الميلاد</div>
                        <div class="info-value"><?=htmlspecialchars($patient["date_de_naissance"])?></div>
                    </div> 
                </div>

                <div class="divider"></div>
                <h2 class="subsection-title">معلومات إضافية</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">رقم التسجيل</div>
                        <div class="info-value"><?=htmlspecialchars($patient["identifiant"])?></div>
                    </div>
        
                    <div class="info-item">
                        <div class="info-label">كلمة المرور</div>
                        <div class="info-value">
                        <span style="letter-spacing: 2px;"></span>
                        <button class="edit-password-btn" onclick="window.location.href='change_password.php?id=<?=urlencode($patient['identifiant'])?>'" title="تعديل كلمة المرور">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>

                </div>   
            </div>   



        </main>
    


    


    <?php else: ?>
        <p>❌ لا يوجد مريض بهذا المعرف.</p>
    <?php endif; ?>

</body>
</html>
