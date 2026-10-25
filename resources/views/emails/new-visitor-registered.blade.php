<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle inscription</title>
</head>

<body>
    <h1>Nouvelle inscription sur Afrique Artisanat</h1>
    <p>Un nouveau visiteur s'est inscrit sur le site.</p>
    <p><strong>Nom :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Telephone :</strong> {{ $user->phone ?: 'Non renseigné' }}</p>
    <p>Le compte a été créé le {{ $user->created_at->format('d/m/Y à H:i') }}</p>
</body>

</html>