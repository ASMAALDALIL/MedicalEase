<?php
session_start();
require_once '../base/db1.php';
require('../fpdf/fpdf.php');

// Vérifier que le patient est connecté
$id_patient = $_SESSION['id'] ;
$nom_vaccin= $_GET['nom_vaccin'];
if (!$id_patient) {
    die("Accès refusé.");
}
$sql1 = "
    SELECT id from vaccin where nom=?
";
$stmt1= $conn->prepare($sql1);
$stmt1->execute([$nom_vaccin]);
$id_vaccin= $stmt1->fetchcolumn();

// Requête pour récupérer les ordonnances
$sql = "
    SELECT vac.ordonnance, p.nom AS nom_patient
    FROM vaccination vac
    JOIN patient p ON vac.id_patient = p.id
    WHERE vac.id_patient = ? AND vac.statut = 'fait' AND id_vaccin=?
          AND vac.ordonnance IS NOT NULL AND TRIM(vac.ordonnance) <> ''
";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_patient,$id_vaccin]);
$ordonnances = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ordonnances) {
    die("Aucune ordonnance trouvée.");
}

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, " Liste des ordonnances", 0, 1, 'C');
$pdf->Ln(10);

// Informations du patient
$nom_patient = $ordonnances[0]['nom_patient'];
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, " Nom du patient : $nom_patient");
$pdf->Ln(5);

// Affichage de chaque ordonnance
foreach ($ordonnances as $row) {;
    $ordonnance = $row['ordonnance'];

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->MultiCell(0, 10, " Vaccin : $nom_vaccin");

    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, " Contenu de l'ordonnance :\n$ordonnance");
    $pdf->Ln(5);
}

// Téléchargement du PDF
$pdf->Output('D', 'ordonnances_patient_' . $nom_patient . '.pdf');
exit;
?>
