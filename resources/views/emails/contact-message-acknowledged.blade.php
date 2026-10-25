<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Message bien reçu</title>
</head>

<body>
    <p>Bonjour {{ $contactMessage->name }},</p>
    <p>Nous avons bien reçu votre message « {{ $contactMessage->subject }} ».</p>
    <p>Notre équipe vous répondra dans les meilleurs délais.</p>
    <p>Merci d’avoir contacté Afrique Artisanat.</p>
</body>

</html>
