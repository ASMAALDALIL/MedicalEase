<?php
session_start();
require_once '../base/db1.php';
require_once 'header.html';

$id_patient = $_SESSION['id'];

if (!$id_patient) {
    echo "Aucun rendez-vous sélectionné.";
    header('Location: connexionhanane.php');
    exit;
}

$sql = "
    SELECT 
        v.nom AS nom_vaccin,
        h.nom AS nom_hopital,
        m.nom AS nom_medecin,
        r.date_rdv,
        r.time_rdv,
        vac.statut AS statut_vaccination
    FROM rendezvous r
    JOIN vaccin v ON r.id_vaccin = v.id
    JOIN hopital h ON r.id_hopital = h.id
    JOIN medecin m ON r.id_medecin = m.id
    LEFT JOIN vaccination vac 
        ON vac.id_patient = r.id_patient 
        AND vac.id_vaccin = r.id_vaccin 
        AND vac.date = r.date_rdv
    WHERE r.id_patient = ?
";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_patient]);
$donnees = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$donnees) {
    echo "لم يتم العثور على أي موعد.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" type="image/jpg" href="../images/logoapp.jpg">
    <title id="title">MEDICAL EASE</title>
    <meta charset="UTF-8">
    <meta name="description" content="carnet de santé médicale">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .carte-vaccin {
            background-color: #d2f0f5;
            padding: 15px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            width: 300px;
            font-family: Arial, sans-serif;
            text-align: right;
            position: relative;
        }

        .checkmark {
            position: absolute;
            top: 10px;
            left: 10px;
            color: green;
            font-size: 24px;
            font-weight: bold;
        }

        .not-done {
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php foreach ($donnees as $data): ?>
    <div class="carte-vaccin">
        <p><strong>اللقاح :</strong> <?= htmlspecialchars($data['nom_vaccin']) ?></p>
        <p><strong>الطبيب :</strong> <?= htmlspecialchars($data['nom_medecin']) ?></p>
        <p><strong>المستوصف :</strong> <?= htmlspecialchars($data['nom_hopital']) ?></p>
        <p><strong>تاريخ الموعد :</strong> <?= htmlspecialchars($data['date_rdv']) ?> ⏰ <?= htmlspecialchars($data['time_rdv']) ?></p>

        <?php if (!empty($data['statut_vaccination']) && $data['statut_vaccination'] === 'fait'): ?>
            <div class="checkmark">✔</div>
            <!-- Lien vers l’ordonnance (optionnel) -->
            <p><a href="telecharger_ordonnance.php?nom_vaccin=<?= urlencode($data['nom_vaccin']) ?>" target="_blank">تحميل الوصفة</a></p>        <?php else: ?>
            <?php
                $dateTimeRdv = new DateTime($data['date_rdv'] . ' ' . $data['time_rdv']);
                $now = new DateTime();
                if ($dateTimeRdv > $now) {
                    $diff = $now->diff($dateTimeRdv);
                    $jours = $diff->days;
                    $heures = $diff->h + ($diff->days * 24);
                    $minutes = $diff->i;
                    $tempsRestant = "$jours أيام، $heures ساعة، $minutes دقيقة";
                } else {
                    $tempsRestant = "مر الموعد.";
                }
            ?>
            <p><strong class="not-done">الحالة :</strong> لم يتم بعد </p>
            <p><strong>الوقت المتبقي :</strong> <?= $tempsRestant ?></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</body>
</html>
