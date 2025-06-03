<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Projet XXX' ?></title>
</head>
<body>
<h1>Enseignants</h1>

<h2>Infos Générales/Enseignants</h2>
<div>
    <label for="numMatricule">Num Matricule:</label>
    <input type="text" id="numMatricule" name="numMatricule">
</div>
<div>
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom">
</div>
<div>
    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom">
</div>
<div>
    <label for="dateNaiss">Date naiss:</label>
    <input type="date" id="dateNaiss" name="dateNaiss">
</div>
<div>
    <label>Genre:</label>
    <input type="radio" id="genreM" name="genre" value="M">
    <label for="genreM">M</label>
    <input type="radio" id="genreF" name="genre" value="F">
    <label for="genreF">F</label>
    <input type="radio" id="genreND" name="genre" value="ND">
    <label for="genreND">N.D</label>
</div>

<h2>Carrière/Enseiggnants</h2>
<div>
    <label for="grade">Grade:</label>
    <input type="text" id="grade" name="grade">
</div>
<div>
    <label for="Fonction">Fonction:</label>
    <input type="date" id="Fonction" name="Fonction">
</div>
<div>
    <label for="dategrade">Date:</label>
    <input type="date" id="dategrade" name="dategrade">
</div>

<div>
    <label for="datefonction">Date:</label>
    <input type="date" id="datefonction" name="datefonction">
</div>

<!--<h2>Contact</h2>
<div>
    <label for="numTel">Numéro Téléphone:</label>
    <input type="tel" id="numTel" name="numTel">
</div>-->

<h2>Liste des Enseignants</h2>
<button>Valider</button>
<table>
    <thead>
    <tr>
        <th>Num Mat</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date naiss</th>
        <th>...</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
<button>Rechercher</button>
<button>Imprimer</button>
<button>Exporter dans Excel</button>
<button>Modifier</button>
<button>Supprimer</button>
</body>
</html>
</body>
</html>