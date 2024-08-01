<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Commande Validée</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f8f9fa;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border: 1px solid #ddd;">
    <div style="text-align: center; background-color: #dc3545; padding: 10px; color: white;">
        <h3 style="margin: 0;">**** CINAYE BURGER ****</h3>
    </div>
    <div style="padding: 20px;">
        <h3>Bonjour {{ $ligneCommande->prenom }},</h3>
        <p>Votre commande a été validée avec succès.</p>
        <p>Détails de la commande :</p>
        <ul style="padding-left: 20px;">
            <li>Burger : {{ $ligneCommande->burger_nom }}</li>
            <li>Prix : {{ $ligneCommande->burger_prix }} FCFA</li>
            <li>Description : {{ $ligneCommande->burger_description }}</li>
        </ul>
        <p><strong>CINAYE BURGER!</strong> Merci de votre confiance.</p>
    </div>
</div>
</body>
</html>
