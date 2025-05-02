<!DOCTYPE html>
<html lang="fr" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/jpg" href="../images/logoapp.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Médecin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: rgb(47, 143, 140);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        a{
            text-decoration: none;
            color:rgb(47, 143, 140);
        }

        .main-container {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
        }

        .services-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .service-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100px;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .service-card h2 {
            margin: 0;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-card i {
            margin-left: 10px;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .services-container {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 20px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="services-container">
            <a href="pageresption.php"><div class="service-card">
                <h2><i class="fas fa-home"></i> الرئيسية</h2>
            </div></a>
            <a href="desinfos.php"><div class="service-card">
                <h2><i class="fas fa-info-circle"></i> المعلومات</h2>
            </div></a>
            
            <a href="tableaudesrendez_vous.php"><div class="service-card">
                <h2><i class="fas fa-calendar-alt"></i> جدول المواعيد</h2>
            </div></a>

            <a href="../deconnexion.php"><div class="service-card">
                <h2><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</h2>    
            </div></a>
        </div>
    </div>
</body>
</html>
