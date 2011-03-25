<?php
// Global entries for the <html> tag
define('HTML_PARAMS','lang="fr"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// footer text in includes/footer.php
define('FOOTER_TEXT_COPYRIGHT', 'Copyright &copy; 2010');

// Text in left panel of pages
define('LEFT_ONLINE_BOOKING', 'R�servation en ligne');
define('LEFT_CHECK_IN_DT', 'Date d\'arriv�e');
define('LEFT_CHECK_OUT_DT', 'Date de d�part');
define('LEFT_CAPACITY', 'Client / Chambre');
define('LEFT_TOTAL_NIGHT', 'Total des nuit�es');
define('LEFT_TITLE_EXISTING_CUSTOMER', 'D�j� client?');
define('LEFT_TITLE_ENTER_EMAIl', 'Si vous �tes d�j� client s\'il vous pla�t, entrez votre adresse e-mail:');
define('LEFT_FETCH_DETAILS_BTN', 'Fetch D�tails');
define('LEFT_CHILD_PER_ROOM', 'Enfant / chambre');
define('LEFT_EXTRA_BED_NEED', 'Besoin d\'un lit suppl�mentaire?');

// Text in index.php
define('INDEX_BOOKING_STATUS', 'Statut de la r�servation');
define('INDEX_BOOKING_NUMBER_ENTER', 'Entrez votre num�ro de r�servation');
define('INDEX_SEARCH_BTN', 'RECHERCHE');
define('INDEX_STATUS_BTN', 'V�RIFIER L\'�TAT');
define('INDEX_WELCOME_TITLE', 'Bienvenue � la');

define('INDEX_STATUS_BOOKING_NUMBER', 'Num�ro de r�servation');
define('INDEX_STATUS_BOOKING_DATE', 'Date de r�servation');
define('INDEX_STATUS_GUEST_NAME', 'Nom du Client');
define('INDEX_STATUS_STATUS', 'statut');
define('INDEX_STATUS_CONFIRM', 'CONFIRMER');
define('INDEX_STATUS_CANCELLED', 'ANNUL�');
define('INDEX_STATUS_COMPLETED', 'REMPLI');
// Text in booking-search.php
define('SEARCH_TITLE', 'R�sultat de la recherche');
define('SEARCH_GUEST_PER_ROOM', 'Adultes / chambre');
define('SEARCH_DETAILS_PRICE', 'D�tail Prix / chambre');
define('SEARCH_EXTRA_BED', 'Besoin d\'un lit suppl�mentaire?');
define('SEARCH_AVAILABLE_ROOM', 'Chambres disponibles');
define('SEARCH_NIGHTS', 'Nuit(s)');
define('SEARCH_TOTAL_PRICE', 'Prix total');
define('SEARCH_ADDITIONAL_COST', 'Co�t suppl�mentaire');
define('SEARCH_BOOK_NOW_BTN', 'R�servez d�s maintenant');
define('SEARCH_EXTRA_BED_YES', 'Oui');
define('SEARCH_EXTRAS_TILTE', 'H�tel Extras');
define('SEARCH_EXTRAS_SERVICES', 'Services disponibles');
define('SEARCH_EXTRAS_PRICE', 'Prix / Service');
define('SEARCH_EXTRAS_REQUIRED', 'Requis');
define('SEARCH_EXTRAS_NO', 'N');
define('SEARCH_ADULT', 'Adultes');
define('SEARCH_BOOKING_TURN_OFF', 'D�sol� r�servation en ligne actuellement pas disponible. S\'il vous pla�t r�essayer plus tard.');
define('SEARCH_INVALID_INPUT', 'D�sol� que vous avez entr� un crit�re valide de recherche. S\'il vous pla�t essayez avec invalides crit�res de recherche.');
define('SEARCH_MIN_NIGHT_PART1', 'Nombre minimum de nuit ne devrait pas �tre inf�rieure �');
define('SEARCH_MIN_NIGHT_PART2', 'S\'il vous pla�t modifier vos crit�res de recherche.');
define('SEARCH_NOT_AVAILABLE', 'D�sol�, pas de chambre disponible � vos crit�res de recherche. S\'il vous pla�t essayez avec fente date diff�rente.');
define('SEARCH_TIMEZONE_PART1', 'R�servation pas possible pour date d\'arriv�e:');
define('SEARCH_TIMEZONE_PART2', 'S\'il vous pla�t modifier vos crit�res de recherche selon les h�tels date et heure. H�tels <br> Date Heure actuelle:');
// Text in booking-details.php
define('BOOKING_DETAILS_TITLE', 'D�tails de la r�servation');
define('BOOKING_DETAILS_ROOM_NUMBER', 'Nombre de chambre');
define('BOOKING_DETAILS_ROOM_TYPE', 'Type de chambre');
define('BOOKING_DETAILS_GROSS_TOTAL', 'Brute totale');
define('BOOKING_DETAILS_COMULATIVE_TOTAL', 'Total cumulatif');
define('BOOKING_DETAILS_TAX', 'Imp�t');
define('BOOKING_DETAILS_GRAND_TOTAL', 'Grand Total');
define('BOOKING_DETAILS_CLIENT_TITLE', 'Titre');
define('BOOKING_DETAILS_FNAME', 'Pr�nom');
define('BOOKING_DETAILS_LNAME', 'Nom de famille');
define('BOOKING_DETAILS_STR_ADDR', 'Street Adresse');
define('BOOKING_DETAILS_CITY', 'City');
define('BOOKING_DETAILS_STATE', '�tat');
define('BOOKING_DETAILS_ZIP', 'Code postal');
define('BOOKING_DETAILS_COUNTRY', 'Pays');
define('BOOKING_DETAILS_PHONE', 'Num�ro de t�l�phone');
define('BOOKING_DETAILS_FAX', 'T�l�copieur');
define('BOOKING_DETAILS_EMAIL', 'Email');
define('BOOKING_DETAILS_PAYMENT_OPTION', 'Paiement par');
define('BOOKING_DETAILS_ADDITIONAL_REQUEST', 'Toute autre demande');
define('BOOKING_DETAILS_AGREE_TEXT', 'Je suis d\'accord avec le');
define('BOOKING_DETAILS_TERMS_LINK', 'Termes & Conditions');
define('BOOKING_DETAILS_CHECKOUT_BTN', 'Confirmer & Commander');

define('BOOKING_DETAILS_TOTAL_ROOMS', 'Nombre total de chambres');
define('BOOKING_DETAILS_DISCOUNT_SCHEME', 'Syst�me de rabais mensuel');
define('BOOKING_DETAILS_DEPOSIT_SCHEME', 'Montant du paiement anticip�');
define('BOOKING_DETAILS_DISCOUNT_COUPON', 'Coupon de r�duction');
define('BOOKING_DETAILS_COUPON_CODE', 'Code Promo');
define('BOOKING_DETAILS_COUPON_DESC', 'Entrez Coupon de r�duction Code si vous avez:');
define('BOOKING_DETAILS_BTN_APPY', 'APPLIQUER');
define('BOOKING_DETAILS_COUPON_PART1', 'Le coupon de r�duction ');
define('BOOKING_DETAILS_COUPON_PART2', 'est appliqu�e.');

define('BOOKING_DETAILS_EXISTING_CUSTOMER', 'D�sol�! Vous n\'�tes pas d�j� client! s\'il vous pla�t remplir le formulaire.');
define('BOOKING_DETAILS_VALID_COUPON', 'S\'il vous pla�t entrer un coupon valable Discount et r�essayez.');
define('BOOKING_DETAILS_FILL_EMAIL', 'S\'il vous pla�t remplir vos donn�es personnelles avec Identification d\'email.');
define('BOOKING_DETAILS_EXPIRED_COUPON', 'Valide ou expir� coupon de r�duction. S\'il vous pla�t entrer coupon de r�duction valable et r�essayez.');
define('BOOKING_DETAILS_ALREADY_USE', 'd�j� utilis� utilis�.');
define('BOOKING_DETAILS_NOT_CUSTOMER', 'Vous n\'�tes pas un client existant ou que vous avez inscrits expir� coupon de r�duction. S\'il vous pla�t entrer coupon de r�duction valable et r�essayez.');
define('BOOKING_DETAILS_NOT_VALID_PART1', 'Coupon de r�duction non valable pour un montant d\'achat de');
define('BOOKING_DETAILS_NOT_VALID_PART2', 'Montant minimum d\'achat');

// Text in contact.php
define('CONTACT_TITLE', 'Besoin d\'entrer en contact avec nous?');
define('CONTACT_SUB_DESC', 'Remplissez simplement le formulaire ci-dessous et nous reviendrons vers vous d�s que possible.');
define('CONTACT_NAME', 'Nom');
define('CONTACT_SUBJECT', 'Sous r�serve');
define('CONTACT_MESSAGE', 'Message');
define('CONTACT_RIGHT_TITLE', 'Contact Details');
define('CONTACT_RIGHT_SUB_DESC', 'Besoin de r�ponses maintenant?');
define('CONTACT_PHONE', 'T�l�phone');
define('CONTACT_ADDR', 'Notre Adresse');
define('CONTACT_SEND_BTN', 'Envoyer');
define('CONTACT_SEND_SUCCESS_MSG', '<b> Merci! </ b> <br> Nous reviendrons vers vous d�s que possible.');
define('CONTACT_SEND_FAILURE', 'D�sol�! Votre email ne pouvez pas envoyer. s\'il vous pla�t contacter le webmaster.');
define('CONTACT_EMAIL_SUBJ', 'Contact formulaire de message.');

// Text in rooms-tariff.php
define('TARIFF_TITLE', 'Tarif chambre');
define('TARIFF_REGULAR_PRICE', 'Prix r�gulier');
define('TARIFF_OFFERS_PRICE', 'Offrir des prix');
define('TARIFF_FROM', 'De');
define('TARIFF_TO', 'Pour');

// Text in gallery.php
define('GALLERY_PAGE', 'Galerie Page');

// booking failure
define('BOOKING_FAILURE_TITLE', 'R�servation non');
define('BOOKING_FAILURE_ERROR_9', 'Acc�s direct � cette page est restreint.');
define('BOOKING_FAILURE_ERROR_13', 'Quelqu\'un a d�j� acqu�rir le verrou de r�servation sur les chambres que vous avez sp�cifi�e. Verrouiller la r�servation sera automatiquement remis en libert� apr�s quelques minutes lors de la r�servation d\'ach�vement ou de l\'�chec par l\'autre personne. S\'il vous pla�t modifier vos crit�res de recherche et essayez � nouveau.');
define('BOOKING_FAILURE_ERROR_22', 'Mode de paiement choisi Undefined. S\'il vous pla�t contacter l\'administrateur.');
define('BOOKING_FAILURE_ERROR_25', 'Impossible d\'envoyer une notification par courrier �lectronique. S\'il vous pla�t contacter le support technique.');

//booking confirm
define('BOOKING_CONFIRM_TITLE', 'R�servation Termin�');
define('BOOKING_CONFIRM_MSG', '<h4> Merci! </ h4> Votre r�servation est confirm�e. Facture envoy�e � votre adresse e-mail.');

//javascript alert  text messages*****************
define('INDEX_JAVASCRIPT_BOOKING_NUMBER', 'Entrez num�ro de r�servation.');
define('INDEX_JAVASCRIPT_CHECK_IN', 'Entrez date de arriv�e.');
define('INDEX_JAVASCRIPT_CHECK_OUT', 'Entrez date de d�part.');
define('INDEX_JAVASCRIPT_DIGIT_ONLY', 'Seuls des chiffres');
define('INDEX_JAVASCRIPT_BOOKING_SEARCH', 'S�lectionnez au moins une salle de proc�der.');

define('DETAILS_JAVASCRIPT_FNAME', 'Entrez votre pr�nom');
define('DETAILS_JAVASCRIPT_LNAME', 'Entrez votre nom de famille');
define('DETAILS_JAVASCRIPT_STR_ADDR', 'Entrez votre adresse');
define('DETAILS_JAVASCRIPT_CITY', 'Entrez votre ville');
define('DETAILS_JAVASCRIPT_STATE', 'Entrez votre �tat');
define('DETAILS_JAVASCRIPT_ZIP', 'Entrez votre code postal post ');
define('DETAILS_JAVASCRIPT_COUNTRY', 'Entrez votre pays');
define('DETAILS_JAVASCRIPT_PHONE', 'Entrez votre num�ro de t�l�phone');
define('DETAILS_JAVASCRIPT_EMAIL', 'Entrez une adresse email valide');
define('DETAILS_JAVASCRIPT_PAYMENT', 'Choisissez m�thode de paiement');
define('DETAILS_JAVASCRIPT_TOS', 'S\'il vous pla�t accepter les termes et conditions');
define('DETAILS_JAVASCRIPT_COUPON_DISPLAY', 'Appliquer Coupon de r�duction si vous avez');
define('CONTACT_JAVASCRIPT_NAME', 'Entrez votre nom complet');
?>
