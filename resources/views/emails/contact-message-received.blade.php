<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
</head>

<body>
    <h1>Nouveau message de contact</h1>
    <p><strong>Nom :</strong> {{ $contactMessage->name }}</p>
    <p><strong>E-mail :</strong> {{ $contactMessage->email }}</p>
    <p><strong>Objet :</strong> {{ $contactMessage->subject }}</p>
    <p><strong>Message :</strong></p>
    <p>{!! nl2br(e($contactMessage->message)) !!}</p>
</body>

</html>
