<?php
    session_start();
    include('header.html');
    require_once'../base/db1.php';
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
    $medecin_id = $_SESSION['id'];
    // Requête préparée pour récupérer les rendez-vous
    $sql = "
        SELECT 
            r.id, 
            r.date_rdv, 
            r.id_hopital,
            r.time_rdv, 
            r.id_patient,
            r.id_vaccin, 
            v.nom as nom_vaccin,
            p.nom AS nom_patient, 
            p.prenom AS prenom_patient
        FROM rendezvous r
        LEFT JOIN patient p ON r.id_patient = p.id
        LEFT JOIN vaccin v ON r.id_vaccin = v.id
        LEFT JOIN hopital h ON r.id_hopital = h.id
        WHERE r.id_medecin = ?
        ORDER BY r.date_rdv, r.time_rdv
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute(array($medecin_id));
    $rendezvous = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($rendezvous as $rdv) {
        @$statut=$_POST['etat'];
        @$ordonnance=$_POST['ordonnance'];
        @$valider=$_POST['valider'];
        if(isset($valider)){
            if($statut=='fait'){
                $sql1='insert into vaccination (id_patient,id_vaccin,date,statut,ordonnance) values (?,?,?,?,?)';
                $stmt1 = $conn->prepare($sql1);
                $stmt1->execute([$rdv['id_patient'],$rdv['id_vaccin'],$rdv['date_rdv'],$statut,$ordonnance]);
        }
        }
    }
    $rendezvousenattent=array();
    $sql3='select statut from vaccination where id_patient=? and id_vaccin=? and date=?';
    $stmt3 = $conn->prepare($sql3);
             foreach ($rendezvous as $rdv){
                $stmt3->execute([$rdv['id_patient'], $rdv['id_vaccin'], $rdv['date_rdv']]);
                $verification_statut=$stmt3->fetchColumn();
                if ($verification_statut != 'fait') {
                     $rendezvousenattent[] = $rdv;
                 }
            }
        
?>
<!DOCTYPE html>

<html>
<head lang="ar">
    <meta charset="UTF-8">
    <meta name="description" content="carnet de santé médicale">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MédicalEase</title>
	<link rel="icon" type="image/jpg" href="../images/logoapp.jpg">
    <style>
        body {
	font-style: policedeprojet, italic;
	background-color: #FFF;
	background-attachment: fixed;
}

header {
	display: flex;
	justify-content: space-around;
	border: solid 1px #007872;
	height: 30px;
	margin: 15px;
	background-color: #007872;
	text-align: center;
}

header>div {
	font-size: 20px;
	font-weight: bold;
	display: inline-bock;
	float: right;

}

header>div:hover {
	transform: scale(1.25);
	text-decoration: underline 2px;
}

audio {
	display: none;
}

.playPause {
	width: 50px;
	height: 50px;
	border-radius: 25px;
	outline: none;
	cursor: pointer;
	order: 1;
}

section {
	padding: 15px;
}

h1 {
	margin-bottom: 20px;
	text-align: center;
}

p {
	display: flex;
	font-size: 20px;
	display: inline-block;
	width: 800px;
	height: 300px;
	margin: 10px;
	order: 2;
	position: relative;
	right: 10px;
	top: 60px;
	float: right;
	justify-content: flex-end;
}

.img {
	margin: 10px;
	width: 200px;
	border-radius: 40px;
	order: 3;
	display: inline-block;
	box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
}

section>div {
	display: flex;
	align-items: center-right;
	justify-content: space-around;
	margin: 20px;
}

.playPause {
	position: relative;
	top: 110px;

}

a {
	text-decoration: none;
	color: #000;
}

/* Corps général */
body {
	background-color: #ffffff;
	color: #000000;
	margin: 0;
}

/* Titre */
h1 {
	text-align: center;
	color: #007872;
	font-size: 36px;
	margin-bottom: 40px;
}

/* Tableau */
table {
	width: 90%;
	border-collapse: collapse;
	background-color: #ffffff;
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
	border-radius: 12px;
	overflow: hidden;
	position: absolute;
	LEFT: 5%;
	top: 15%;
}

th {
	background-color: #007872;
	color: #ffffff;
	font-size: 15px;
	padding: 16px;
	border-bottom: 2px solid #000000;
}

td {
	padding: 14px;
	text-align: center;
	vertical-align: middle;
	border-bottom: 1px solid #ddd;
}

form label {
	display: block;
	margin-bottom: 6px;
	font-weight: bold;
	text-align: right;
}


/* Chaque ligne */
tr:hover {
	background-color: #f5f5f5;
}

/* Zone de texte bien à l'intérieur */
textarea {
	width: 100%;
	max-width: 200px;
	height: 80px;
	padding: 8px;
	font-size: 14px;
	border: 1px solid #000000;
	border-radius: 8px;
	resize: vertical;
	background-color: #ffffff;
	color: #000000;
	box-sizing: border-box;
}

textarea:focus {
	border-color: #007872;
	box-shadow: 0 0 6px rgba(0, 120, 114, 0.3);
	outline: none;
}

/* Bouton confirmé */
input[type="submit"] {
	background-color: #007872;
	color: #ffffff;
	padding: 8px 16px;
	border: none;
	border-radius: 20px;
	font-weight: bold;
	cursor: pointer;
	transition: all 0.3s ease;
	margin-top: 10px;
}

input[type="submit"]:hover {
	background-color: #005f59;
	transform: scale(1.05);
}

/* Radio boutons */
input[type="radio"] {
	accent-color: #007872;
	transform: scale(1.1);
}

label {
	font-size: 13px;
	color: #000000;
	margin-bottom: 5px;
}

/* Responsive */
@media screen and (max-width: 768px) {

	th,
	td {
		font-size: 13px;
		padding: 10px;
	}

	textarea,
	input[type="submit"] {
		width: 100%;
	}
}
    </style>
</head>

<body>
    <table border="1" width="50%" cellspacing="2px">
    <tr>
        <th>تأكيد </th>
        <th>الوصفة الطبية</th>
        <th>الحالة</th>
        <th>اسم اللقاح</th>
        <th>اسم المريض</th>
        <th>اليوم</th>
        <th>الساعة</th>
        
    </tr>
        <?php if ($rendezvousenattent): ?>
             <?php foreach ($rendezvousenattent as $rdv): ?>

            <tr>
                    <form method="POST" action="">
                    <td>
                        <input type="submit" name="valider" value="تأكيد ">
                    </td>
                    <td>
                        <textarea name="ordonnance" rows="4" placeholder="اكتب الوصفة هنا"></textarea>
                        <input type="hidden" name="id_rdv" value="<?= $rdv['id'] ?>">
                    </td>
                    <td>
                        <label>
                            <input type="radio" name="etat" value="fait"> Fait
                        </label>
                    </td>
                    <td><?= htmlspecialchars($rdv["nom_vaccin"]) ?></td>
                    <td><?= htmlspecialchars($rdv["prenom_patient"] . " " . $rdv["nom_patient"]) ?></td>
                    <td><?= htmlspecialchars($rdv["date_rdv"]) ?></td>
                    <td><?= htmlspecialchars($rdv["time_rdv"]) ?></td>
                </form>
                </tr>
            <?php endforeach;?>
        <?php else: ?>
            <tr><td colspan="7">لم يتم العثور على أي موعد</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>