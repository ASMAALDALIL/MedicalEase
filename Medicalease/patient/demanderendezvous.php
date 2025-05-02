<?php
session_start();
include_once("../base/db1.php");
require_once'header.html';

$message = "";

if (!isset($_SESSION['id'])) {
    echo "Veuillez vous connecter.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_patient = $_SESSION['id'];
    $nom_vaccin = $_POST['vaccine'];
    $date_rdv = $_POST['rdv-date'];
    $time_rdv = $_POST['rdv-time'];
    $nom_hopital = $_POST['hopital'];
    $nom_medecin = $_POST['medecin'];
    $heure = strtotime($time_rdv);

    $today = date('Y-m-d');
    if ($date_rdv < $today) {
        $message = "⚠ لا يمكن حجز موعد في تاريخ سابق ❌";
    } else {
        $heure_min = strtotime("08:30");
        $heure_max = strtotime("12:30");

        if ($heure < $heure_min || $heure > $heure_max) {
            $message = "⚠ يجب أن يكون الموعد بين الساعة 08:30 و 12:30 ❌";
        } else {
            // Récupération des IDs
            $stmt = $conn->prepare("SELECT id FROM vaccin WHERE nom = ?");
            $stmt->execute([$nom_vaccin]);
            $id_vaccin = $stmt->fetchColumn();
            $stmt1 = $conn->prepare("SELECT id FROM hopital WHERE nom = ?");
            $stmt1->execute([$nom_hopital]);
            $id_hopital = $stmt1->fetchColumn();

            $stmt2 = $conn->prepare("SELECT id FROM medecin WHERE nom = ?");
            $stmt2->execute([$nom_medecin]);
            $id_medecin = $stmt2->fetchColumn();

            // Vérification de disponibilité
            $check = $conn->prepare("SELECT id FROM rendezvous WHERE date_rdv = ? AND time_rdv = ? AND id_hopital = ? AND id_medecin = ?");
            $check->execute([$date_rdv, $time_rdv, $id_hopital, $id_medecin]);
            $check1 = $check->fetchColumn();

            if ($check1) {
                $message = "هذا الوقت محجوز بالفعل من قبل مريض آخر ❌";
            } else {
                $stmt3 = $conn->prepare("INSERT INTO rendezvous (id_patient, id_vaccin, date_rdv, time_rdv, id_hopital, id_medecin) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt3->execute([$id_patient, $id_vaccin, $date_rdv, $time_rdv, $id_hopital, $id_medecin]);
                header("Location: tableaudesvaccin.php?success=1");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MEDICAL EASE</title>
    <link rel="icon" type="image/jpg" href="../images/logoapp.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        .floating-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            color: #007872;
            font-size: 20px;
            animation: floatUp 8s linear infinite;
            opacity: 0.1;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) scale(1);
                opacity: 0.1;
            }
            50% {
                opacity: 0.15;
            }
            100% {
                transform: translateY(-10vh) scale(1.2);
                opacity: 0;
            }
        }

        .appointment-form {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 120, 114, 0.2);
            width: 320px;
            text-align: right;
            position: absolute;
            right:500px;
            z-index: 2;
        }

        .appointment-form h1 {
            text-align: center;
            font-size: 1.2em;
        }

        .appointment-form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .appointment-form input,
        .appointment-form select,
        .appointment-form button {
            width: 100%;
            padding: 8px;
            border: 1px solid #000000;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .appointment-form button {
            margin-top: 20px;
            background-color: #007872;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .appointment-form button:hover {
            background-color: #005f58;
        }

        .confirmation {
            text-align: center;
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }

        input, select {
            direction: rtl;
            text-align: right;
        }

        .icon-animation {
            font-size: 40px;
            text-align: center;
            margin-bottom: 10px;
            animation: float 2.5s ease-in-out infinite;
        }

        @keyframes float {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="floating-bubbles">
        <div class="bubble" style="left: 10%; animation-delay: 0s;">🩺</div>
        <div class="bubble" style="left: 30%; animation-delay: 2s;">💉</div>
        <div class="bubble" style="left: 50%; animation-delay: 4s;">💊</div>
        <div class="bubble" style="left: 70%; animation-delay: 1s;">🩹</div>
        <div class="bubble" style="left: 90%; animation-delay: 3s;">🧬</div>
    </div>

    <div class="appointment-form">
        <div class="icon-animation">💉</div>
        <h1>حجز موعد التطعيم</h1>

        <?php if (!empty($message)): ?>
            <div class="confirmation"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="vaccine">اختر اللقاح</label>
            <select name="vaccine" id="vaccine" required>
                <option value="">-- اختر اللقاح --</option>
                <option value="BCG">BCG</option>
                <option value="POLIO">POLIO</option>
                <option value="HB1">HB1</option>
                <option value="HB2">HB2</option>
            </select>

            <label for="rdv-date">تاريخ الموعد</label>
            <input type="date" id="rdv-date" name="rdv-date" required>

            <label for="rdv-time">الساعة</label>
            <input type="time" id="rdv-time" name="rdv-time" min="08:30" max="12:30" step="900" required>

            <label for="hopital">اختر المستوصف</label>
            <select name="hopital" id="hopital" required>
                <option value="">-- اختر المستوصف --</option>
                <option value="razi">RAZI</option>
                <option value="IBN TOUFIL">IBN TOUFIL</option>
            </select>

            <label for="medecin">اختر الطبيب</label>
            <select name="medecin" id="medecin" required>
                <option value="">-- اختر الطبيب --</option>
            </select>

            <button type="submit">احجز موعد</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const medecinsParHopital = {
                'razi': ['Dr. Ali', 'Dr. Leila'],
                'IBN TOUFIL': ['Dr. Omar', 'Dr. Sana']
            };

            const hopitalSelect = document.getElementById('hopital');
            const medecinSelect = document.getElementById('medecin');

            hopitalSelect.addEventListener('change', function () {
                const hopital = hopitalSelect.value;
                medecinSelect.innerHTML = '<option value="">-- اختر الطبيب --</option>';
                if (medecinsParHopital[hopital]) {
                    medecinsParHopital[hopital].forEach(med => {
                        const option = document.createElement('option');
                        option.value = med;
                        option.textContent = med;
                        medecinSelect.appendChild(option);
                    });
                }
            });

            // Fix: date min = today
            const dateInput = document.getElementById('rdv-date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        });
    </script>
</body>
</html>
