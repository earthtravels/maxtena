<?php
// Global entries for the <html> tag
define('HTML_PARAMS','lang="fr"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// footer text in includes/footer.php
define('FOOTER_TEXT_COPYRIGHT', 'Copyright &copy; 2010');

// Text in left panel of pages
define('LEFT_ONLINE_BOOKING', 'Réservation en ligne');
define('LEFT_CHECK_IN_DT', 'Date d\'arrivée');
define('LEFT_CHECK_OUT_DT', 'Date de départ');
define('LEFT_CAPACITY', 'Client / Chambre');
define('LEFT_TOTAL_NIGHT', 'Total des nuitées');
define('LEFT_TITLE_EXISTING_CUSTOMER', 'Déjà client?');
define('LEFT_TITLE_ENTER_EMAIl', 'Si vous êtes déjà client s\'il vous plaît, entrez votre adresse e-mail:');
define('LEFT_FETCH_DETAILS_BTN', 'Fetch Détails');
define('LEFT_CHILD_PER_ROOM', 'Enfant / chambre');
define('LEFT_EXTRA_BED_NEED', 'Besoin d\'un lit supplémentaire?');

// Text in index.php
define('INDEX_BOOKING_STATUS', 'Statut de la réservation');
define('INDEX_BOOKING_NUMBER_ENTER', 'Entrez votre numéro de réservation');
define('INDEX_SEARCH_BTN', 'RECHERCHE');
define('INDEX_STATUS_BTN', 'VÉRIFIER L\'ÉTAT');
define('INDEX_WELCOME_TITLE', 'Bienvenue à la');

define('INDEX_STATUS_BOOKING_NUMBER', 'Numéro de réservation');
define('INDEX_STATUS_BOOKING_DATE', 'Date de réservation');
define('INDEX_STATUS_GUEST_NAME', 'Nom du Client');
define('INDEX_STATUS_STATUS', 'statut');
define('INDEX_STATUS_CONFIRM', 'CONFIRMER');
define('INDEX_STATUS_CANCELLED', 'ANNULÉ');
define('INDEX_STATUS_COMPLETED', 'REMPLI');
// Text in booking-search.php
define('SEARCH_TITLE', 'Résultat de la recherche');
define('SEARCH_GUEST_PER_ROOM', 'Adultes / chambre');
define('SEARCH_DETAILS_PRICE', 'Détail Prix / chambre');
define('SEARCH_EXTRA_BED', 'Besoin d\'un lit supplémentaire?');
define('SEARCH_AVAILABLE_ROOM', 'Chambres disponibles');
define('SEARCH_NIGHTS', 'Nuit(s)');
define('SEARCH_TOTAL_PRICE', 'Prix total');
define('SEARCH_ADDITIONAL_COST', 'Coût supplémentaire');
define('SEARCH_BOOK_NOW_BTN', 'Réservez dès maintenant');
define('SEARCH_EXTRA_BED_YES', 'Oui');
define('SEARCH_EXTRAS_TILTE', 'Hôtel Extras');
define('SEARCH_EXTRAS_SERVICES', 'Services disponibles');
define('SEARCH_EXTRAS_PRICE', 'Prix / Service');
define('SEARCH_EXTRAS_REQUIRED', 'Requis');
define('SEARCH_EXTRAS_NO', 'N');
define('SEARCH_ADULT', 'Adultes');
define('SEARCH_BOOKING_TURN_OFF', 'Désolé réservation en ligne actuellement pas disponible. S\'il vous plaît réessayer plus tard.');
define('SEARCH_INVALID_INPUT', 'Désolé que vous avez entré un critère valide de recherche. S\'il vous plaît essayez avec invalides critères de recherche.');
define('SEARCH_MIN_NIGHT_PART1', 'Nombre minimum de nuit ne devrait pas être inférieure à');
define('SEARCH_MIN_NIGHT_PART2', 'S\'il vous plaît modifier vos critères de recherche.');
define('SEARCH_NOT_AVAILABLE', 'Désolé, pas de chambre disponible à vos critères de recherche. S\'il vous plaît essayez avec fente date différente.');
define('SEARCH_TIMEZONE_PART1', 'Réservation pas possible pour date d\'arrivée:');
define('SEARCH_TIMEZONE_PART2', 'S\'il vous plaît modifier vos critères de recherche selon les hôtels date et heure. Hôtels <br> Date Heure actuelle:');
// Text in booking-details.php
define('BOOKING_DETAILS_TITLE', 'Détails de la réservation');
define('BOOKING_DETAILS_ROOM_NUMBER', 'Nombre de chambre');
define('BOOKING_DETAILS_ROOM_TYPE', 'Type de chambre');
define('BOOKING_DETAILS_GROSS_TOTAL', 'Brute totale');
define('BOOKING_DETAILS_COMULATIVE_TOTAL', 'Total cumulatif');
define('BOOKING_DETAILS_TAX', 'Impôt');
define('BOOKING_DETAILS_GRAND_TOTAL', 'Grand Total');
define('BOOKING_DETAILS_CLIENT_TITLE', 'Titre');
define('BOOKING_DETAILS_FNAME', 'Prénom');
define('BOOKING_DETAILS_LNAME', 'Nom de famille');
define('BOOKING_DETAILS_STR_ADDR', 'Street Adresse');
define('BOOKING_DETAILS_CITY', 'City');
define('BOOKING_DETAILS_STATE', 'État');
define('BOOKING_DETAILS_ZIP', 'Code postal');
define('BOOKING_DETAILS_COUNTRY', 'Pays');
define('BOOKING_DETAILS_PHONE', 'Numéro de téléphone');
define('BOOKING_DETAILS_FAX', 'Télécopieur');
define('BOOKING_DETAILS_EMAIL', 'Email');
define('BOOKING_DETAILS_PAYMENT_OPTION', 'Paiement par');
define('BOOKING_DETAILS_ADDITIONAL_REQUEST', 'Toute autre demande');
define('BOOKING_DETAILS_AGREE_TEXT', 'Je suis d\'accord avec le');
define('BOOKING_DETAILS_TERMS_LINK', 'Termes & Conditions');
define('BOOKING_DETAILS_CHECKOUT_BTN', 'Confirmer & Commander');

define('BOOKING_DETAILS_TOTAL_ROOMS', 'Nombre total de chambres');
define('BOOKING_DETAILS_DISCOUNT_SCHEME', 'Système de rabais mensuel');
define('BOOKING_DETAILS_DEPOSIT_SCHEME', 'Montant du paiement anticipé');
define('BOOKING_DETAILS_DISCOUNT_COUPON', 'Coupon de réduction');
define('BOOKING_DETAILS_COUPON_CODE', 'Code Promo');
define('BOOKING_DETAILS_COUPON_DESC', 'Entrez Coupon de réduction Code si vous avez:');
define('BOOKING_DETAILS_BTN_APPY', 'APPLIQUER');
define('BOOKING_DETAILS_COUPON_PART1', 'Le coupon de réduction ');
define('BOOKING_DETAILS_COUPON_PART2', 'est appliquée.');

define('BOOKING_DETAILS_EXISTING_CUSTOMER', 'Désolé! Vous n\'êtes pas déjà client! s\'il vous plaît remplir le formulaire.');
define('BOOKING_DETAILS_VALID_COUPON', 'S\'il vous plaît entrer un coupon valable Discount et réessayez.');
define('BOOKING_DETAILS_FILL_EMAIL', 'S\'il vous plaît remplir vos données personnelles avec Identification d\'email.');
define('BOOKING_DETAILS_EXPIRED_COUPON', 'Valide ou expiré coupon de réduction. S\'il vous plaît entrer coupon de réduction valable et réessayez.');
define('BOOKING_DETAILS_ALREADY_USE', 'déjà utilisé utilisé.');
define('BOOKING_DETAILS_NOT_CUSTOMER', 'Vous n\'êtes pas un client existant ou que vous avez inscrits expiré coupon de réduction. S\'il vous plaît entrer coupon de réduction valable et réessayez.');
define('BOOKING_DETAILS_NOT_VALID_PART1', 'Coupon de réduction non valable pour un montant d\'achat de');
define('BOOKING_DETAILS_NOT_VALID_PART2', 'Montant minimum d\'achat');

// Text in contact.php
define('CONTACT_TITLE', 'Besoin d\'entrer en contact avec nous?');
define('CONTACT_SUB_DESC', 'Remplissez simplement le formulaire ci-dessous et nous reviendrons vers vous dès que possible.');
define('CONTACT_NAME', 'Nom');
define('CONTACT_SUBJECT', 'Sous réserve');
define('CONTACT_MESSAGE', 'Message');
define('CONTACT_RIGHT_TITLE', 'Contact Details');
define('CONTACT_RIGHT_SUB_DESC', 'Besoin de réponses maintenant?');
define('CONTACT_PHONE', 'Téléphone');
define('CONTACT_ADDR', 'Notre Adresse');
define('CONTACT_SEND_BTN', 'Envoyer');
define('CONTACT_SEND_SUCCESS_MSG', '<b> Merci! </ b> <br> Nous reviendrons vers vous dès que possible.');
define('CONTACT_SEND_FAILURE', 'Désolé! Votre email ne pouvez pas envoyer. s\'il vous plaît contacter le webmaster.');
define('CONTACT_EMAIL_SUBJ', 'Contact formulaire de message.');

// Text in rooms-tariff.php
define('TARIFF_TITLE', 'Tarif chambre');
define('TARIFF_REGULAR_PRICE', 'Prix régulier');
define('TARIFF_OFFERS_PRICE', 'Offrir des prix');
define('TARIFF_FROM', 'De');
define('TARIFF_TO', 'Pour');

// Text in gallery.php
define('GALLERY_PAGE', 'Galerie Page');

// booking failure
define('BOOKING_FAILURE_TITLE', 'Réservation non');
define('BOOKING_FAILURE_ERROR_9', 'Accès direct à cette page est restreint.');
define('BOOKING_FAILURE_ERROR_13', 'Quelqu\'un a déjà acquérir le verrou de réservation sur les chambres que vous avez spécifiée. Verrouiller la réservation sera automatiquement remis en liberté après quelques minutes lors de la réservation d\'achèvement ou de l\'échec par l\'autre personne. S\'il vous plaît modifier vos critères de recherche et essayez à nouveau.');
define('BOOKING_FAILURE_ERROR_22', 'Mode de paiement choisi Undefined. S\'il vous plaît contacter l\'administrateur.');
define('BOOKING_FAILURE_ERROR_25', 'Impossible d\'envoyer une notification par courrier électronique. S\'il vous plaît contacter le support technique.');

//booking confirm
define('BOOKING_CONFIRM_TITLE', 'Réservation Terminé');
define('BOOKING_CONFIRM_MSG', '<h4> Merci! </ h4> Votre réservation est confirmée. Facture envoyée à votre adresse e-mail.');

//javascript alert  text messages*****************
define('INDEX_JAVASCRIPT_BOOKING_NUMBER', 'Entrez numéro de réservation.');
define('INDEX_JAVASCRIPT_CHECK_IN', 'Entrez date de arrivée.');
define('INDEX_JAVASCRIPT_CHECK_OUT', 'Entrez date de départ.');
define('INDEX_JAVASCRIPT_DIGIT_ONLY', 'Seuls des chiffres');
define('INDEX_JAVASCRIPT_BOOKING_SEARCH', 'Sélectionnez au moins une salle de procéder.');

define('DETAILS_JAVASCRIPT_FNAME', 'Entrez votre prénom');
define('DETAILS_JAVASCRIPT_LNAME', 'Entrez votre nom de famille');
define('DETAILS_JAVASCRIPT_STR_ADDR', 'Entrez votre adresse');
define('DETAILS_JAVASCRIPT_CITY', 'Entrez votre ville');
define('DETAILS_JAVASCRIPT_STATE', 'Entrez votre état');
define('DETAILS_JAVASCRIPT_ZIP', 'Entrez votre code postal post ');
define('DETAILS_JAVASCRIPT_COUNTRY', 'Entrez votre pays');
define('DETAILS_JAVASCRIPT_PHONE', 'Entrez votre numéro de téléphone');
define('DETAILS_JAVASCRIPT_EMAIL', 'Entrez une adresse email valide');
define('DETAILS_JAVASCRIPT_PAYMENT', 'Choisissez méthode de paiement');
define('DETAILS_JAVASCRIPT_TOS', 'S\'il vous plaît accepter les termes et conditions');
define('DETAILS_JAVASCRIPT_COUPON_DISPLAY', 'Appliquer Coupon de réduction si vous avez');
define('CONTACT_JAVASCRIPT_NAME', 'Entrez votre nom complet');
?>
