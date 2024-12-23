<?php 
// Pour se connecter à la base de données
include("config.php");

// Connexion à la base de données
try {
    $dsn = 'mysql:host=' . $hote . ';port=' . $port . ';dbname=' . $nom_bd . ';charset=' . $encodage;
    $dbh = new PDO($dsn, $identifiant, $mot_de_passe, $options);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Échec lors de la connexion : ' . $e->getMessage();
    exit;
}

/* Récupération des utilisateurs */
try {
    $requete = 'SELECT * FROM inscription ORDER BY nom, prenom';
    $resultats = $dbh->query($requete);
    $tableauUtilisateurs = $resultats->fetchAll(PDO::FETCH_ASSOC);
    $resultats->closeCursor();
    $nbUtilisateurs = count($tableauUtilisateurs);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Liste des Utilisateurs</title>
  <meta name="description" content=""/>

  <link rel="stylesheet" media="screen" href="css/normalize.css">
</head>

<body>

    <div class="container">
        <h1 class="text-center text-primary my-4">Visualiser les Inscriptions</h1>

        <?php if (count($inscriptions) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'Inscription</th>
                        <th>Événement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscriptions as $inscription): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscription['nom']) ?></td>
                        <td><?= htmlspecialchars($inscription['email']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($inscription['date_inscription']))) ?></td>
                        <td><?= htmlspecialchars($inscription['evenement_titre'] ?? 'Aucun') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">Aucune inscription trouvée.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <h1>Liste des utilisateurs</h1>
  <h2 id="intituleUtilisateur"></h2>
  <div id="divUtilisateurs"></div>
  
  <h2>Utilisateurs</h2>

  <!-- Liste des utilisateurs -->
  <form method="GET">
    <select id="selectUtilisateur" name="id_utilisateur" size="5">
      <?php
        for($i = 0; $i < $nbUtilisateurs; $i++){
          echo '<option value="'.$tableauUtilisateurs[$i]["id_inscription"].'">'.$tableauUtilisateurs[$i]["prenom"].' '.$tableauUtilisateurs[$i]["nom"].'</option>'."\n";
        }
      ?>
    </select></br>
  </form>

<!-- Template Mustache pour afficher les informations détaillées -->
<script id="templateUtilisateur" type="text/html">
  <ul>
  {{ #. }}
    <li>
      <strong>{{prenom}} {{nom}}</strong><br>
      <em>{{email}}</em><br>
      <p><strong>Adresse:</strong> {{adresse}}, {{ville}} ({{code_postal}}), {{departement}}</p>
      <p><strong>Téléphone:</strong> {{telephone}}</p>
      <p><strong>Niveau:</strong> {{niveau}}</p>
      <p><strong>Date d'inscription:</strong> {{date_inscription}}</p>
    </li>
  {{ /. }}
  </ul>
</script>

  <!-- Mustache -->
  <script src="js/mustache.min.js"></script>
  <script src="js/script.js"></script>

</body>

</html>