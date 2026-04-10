<?php
require 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $desc = $_POST['description'];
    $stat = $_POST['statut'];
    $prio = $_POST['priorite'];
    $date = $_POST['date_limite'] ?: null;

    $sql = "INSERT INTO taches (titre, description, statut, priorite, date_limite) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titre, $desc, $stat, $prio, $date]);
    
    echo "Donnée enregistrée avec succès.";
}

if (isset($_GET['suppr'])) {
    $id = $_GET['suppr'];
    $sql_suppr = "DELETE FROM taches WHERE id = ?";
    $stmt_suppr = $pdo->prepare($sql_suppr);
    $stmt_suppr->execute([$id]);
    
    header("Location: index.php");
    exit();
}

$f = isset($_GET['f']) ? $_GET['f'] : 'toutes';

if ($f == "a_faire") {
    $sql_liste = "SELECT * FROM taches WHERE statut = 'a_faire' ORDER BY id DESC";
} elseif ($f == "terminees") {
    $sql_liste = "SELECT * FROM taches WHERE statut = 'termine' ORDER BY id DESC";
} else {
    $sql_liste = "SELECT * FROM taches ORDER BY id DESC";
}

$resultat = $pdo->query($sql_liste);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TP BTS SIO - Todo Liste</title>
    <link rel="stylesheet" href="style.css">
    
    <script>
        // JavaScript 
        function verifier() {
            var t = document.getElementById("titre").value;
            if (t.trim() == "") {
                alert("Erreur : Le titre est obligatoire !");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <h1>Todo Liste</h1>

    <div class="form-bloc">
        <form method="post" onsubmit="return verifier()">
            <strong>Ajouter une tâche :</strong><br><br>
            
            Titre : <input type="text" name="titre" id="titre"><br>
            
            Description : <br>
            <textarea name="description" rows="2" cols="40"></textarea><br>
            
            Statut : 
            <select name="statut">
                <option value="a_faire">A faire</option>
                <option value="termine">Terminé</option>
            </select><br>

            Priorité : 
            <select name="priorite">
                <option value="basse">Basse</option>
                <option value="normale" selected>Normale</option>
                <option value="haute">Haute</option>
            </select><br>

            Date limite : <input type="date" name="date_limite"><br><br>
            
            <input type="submit" name="ajouter" value="Enregistrer la tâche">
        </form>
    </div>

    <p>
        <strong>Filtres :</strong> 
        <a href="index.php?f=toutes">Toutes</a> | 
        <a href="index.php?f=a_faire">A faire</a> | 
        <a href="index.php?f=terminees">Terminées</a>
    </p>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Priorité</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php foreach($resultat as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['titre']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo $row['statut']; ?></td>
            <td><?php echo $row['priorite']; ?></td>
            <td><?php echo $row['date_limite']; ?></td>
            <td>
                <a href="index.php?suppr=<?php echo $row['id']; ?>" onclick="return confirm('Voulez-vous supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>