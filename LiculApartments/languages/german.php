<?php
// Global entries for the <html> tag
define('HTML_PARAMS','lang="de"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// footer text in includes/footer.php
define('FOOTER_TEXT_COPYRIGHT', 'Copyright &copy; 2010');

// Text in left panel of pages
define('LEFT_ONLINE_BOOKING', 'Online Buchung');
define('LEFT_CHECK_IN_DT', 'Anreise am ');
define('LEFT_CHECK_OUT_DT', 'Abreisetag');
define('LEFT_CAPACITY', 'Gast / Zimmer');
define('LEFT_TOTAL_NIGHT', 'Insgesamt Nights');
define('LEFT_TITLE_EXISTING_CUSTOMER', 'Bereits Kunde?');
define('LEFT_TITLE_ENTER_EMAIl', 'Sind Sie bereits Kunde geben Sie bitte Ihre E-Mail Adresse:');
define('LEFT_FETCH_DETAILS_BTN', 'Fetch Details');
define('LEFT_CHILD_PER_ROOM', 'Kinder-/ Zimmer');
define('LEFT_EXTRA_BED_NEED', 'Brauchen Zustell?');

// Text in index.php
define('INDEX_BOOKING_STATUS', 'Buchung Status ');
define('INDEX_BOOKING_NUMBER_ENTER', 'Geben Sie Buchungsnummer ');
define('INDEX_SEARCH_BTN', 'SUCHE');
define('INDEX_STATUS_BTN', 'CHECK  STATUS');
define('INDEX_WELCOME_TITLE', 'Willkommen auf der');

define('INDEX_STATUS_BOOKING_NUMBER', 'Buchungsnummer');
define('INDEX_STATUS_BOOKING_DATE', 'Datum der Buchung');
define('INDEX_STATUS_GUEST_NAME', 'Gast Name');
define('INDEX_STATUS_STATUS', 'Status');
define('INDEX_STATUS_CONFIRM', 'CONFIRM');
define('INDEX_STATUS_CANCELLED', 'ABGESAGT');
define('INDEX_STATUS_COMPLETED', 'ABGESCHLOSSEN');

// Text in booking-search.php
define('SEARCH_TITLE', 'Suchergebniss');
define('SEARCH_GUEST_PER_ROOM', 'Gast(Erwachsene)/Zimmer');
define('SEARCH_DETAILS_PRICE', 'Detail Preis / Zimmer');
define('SEARCH_EXTRA_BED', 'Benötigen Sie zusätzliche Bett?');
define('SEARCH_AVAILABLE_ROOM', 'Verfügbare Zimmer');
define('SEARCH_NIGHTS', 'Übernachtung (en)');
define('SEARCH_TOTAL_PRICE', 'Gesamtpreis');
define('SEARCH_ADDITIONAL_COST', 'Regieabrechnungen');
define('SEARCH_BOOK_NOW_BTN', 'Buchen Sie jetzt');
define('SEARCH_EXTRA_BED_YES', 'Ja');
define('SEARCH_EXTRAS_TILTE', 'Hotel Extras');
define('SEARCH_EXTRAS_SERVICES', 'Verfügbare Services');
define('SEARCH_EXTRAS_PRICE', 'Preis / Service');
define('SEARCH_EXTRAS_REQUIRED', 'Erforderliche');
define('SEARCH_EXTRAS_NO', 'Nein');
define('SEARCH_ADULT', 'Erwachsene');
define('SEARCH_BOOKING_TURN_OFF', 'Sorry online buchen derzeit nicht verfügbar. Bitte versuchen Sie es später.');
define('SEARCH_INVALID_INPUT', 'Leider haben Sie eine ungültige Benutzer eingegebenen Kriterien. Bitte versuchen Sie es mit ungültigen Suchkriterien.');
define('SEARCH_MIN_NIGHT_PART1', 'Minimal Anzahl der Nacht sollte nicht kleiner sein als');
define('SEARCH_MIN_NIGHT_PART2', 'Bitte ändern Sie Ihre Suchkriterien.');
define('SEARCH_NOT_AVAILABLE', 'Leider kein Platz zur Verfügung als Ihre Suchkriterien. Bitte versuchen Sie es mit anderen Termin-Steckplatz.');

define('SEARCH_TIMEZONE_PART1', 'Buchung nicht möglich Check-in Datum:');
define('SEARCH_TIMEZONE_PART2', 'Bitte ändern Sie Ihre Suchkriterien nach Hotels Datum Zeit. <br> Hotels Aktuelles Datum Uhrzeit:');

// Text in booking-details.php
define('BOOKING_DETAILS_TITLE', 'Buchung Details');
define('BOOKING_DETAILS_ROOM_NUMBER', 'Zimmer Nummer');
define('BOOKING_DETAILS_ROOM_TYPE', 'Zimmer Typ');
define('BOOKING_DETAILS_GROSS_TOTAL', 'Gesamtüberdruck');
define('BOOKING_DETAILS_COMULATIVE_TOTAL', 'Kum Total');
define('BOOKING_DETAILS_TAX', 'MwSt.');
define('BOOKING_DETAILS_GRAND_TOTAL', 'Gesamtsumme');
define('BOOKING_DETAILS_CLIENT_TITLE', 'Titel');
define('BOOKING_DETAILS_FNAME', 'Vorname');
define('BOOKING_DETAILS_LNAME', 'Nachname');
define('BOOKING_DETAILS_STR_ADDR', 'Straße');
define('BOOKING_DETAILS_CITY', 'Stadt');
define('BOOKING_DETAILS_STATE', 'Zustand');
define('BOOKING_DETAILS_ZIP', 'Pfosten Code');
define('BOOKING_DETAILS_COUNTRY', 'Land');
define('BOOKING_DETAILS_PHONE', 'Telefonnummer');
define('BOOKING_DETAILS_FAX', 'Telefax-Zahl');
define('BOOKING_DETAILS_EMAIL', 'EMail');
define('BOOKING_DETAILS_PAYMENT_OPTION', 'Zahlungsmethode');
define('BOOKING_DETAILS_ADDITIONAL_REQUEST', 'Irgendwelche zusätzlichen<br>Anträge');
define('BOOKING_DETAILS_AGREE_TEXT', 'Ich stimme mit der');
define('BOOKING_DETAILS_TERMS_LINK', 'Allgemeine Geschäftsbedingungen');
define('BOOKING_DETAILS_CHECKOUT_BTN', 'Bestätigen und Kasse');

define('BOOKING_DETAILS_TOTAL_ROOMS', 'Anzahl der Zimmer');
define('BOOKING_DETAILS_DISCOUNT_SCHEME', 'Monatliche Discount Schema');
define('BOOKING_DETAILS_DEPOSIT_SCHEME', 'Vorauszahlungsbetrages');
define('BOOKING_DETAILS_DISCOUNT_COUPON', 'Rabatt-Coupon');
define('BOOKING_DETAILS_COUPON_CODE', 'Gutschein-Code');
define('BOOKING_DETAILS_COUPON_DESC', 'Geben Sie Rabatt Gutschein-Code, wenn Sie:');
define('BOOKING_DETAILS_BTN_APPY', 'APPLY');
define('BOOKING_DETAILS_COUPON_PART1', 'Der Rabatt-Coupon ');
define('BOOKING_DETAILS_COUPON_PART2', 'angewandt wird.');

define('BOOKING_DETAILS_EXISTING_CUSTOMER', 'Sorry! Sie sind nicht bestehenden Kunden! Bitte füllen Sie das Formular aus.');
define('BOOKING_DETAILS_VALID_COUPON', 'Bitte geben Sie eine gültige Rabatt-Coupon und erneut versuchen.');
define('BOOKING_DETAILS_FILL_EMAIL', 'Bitte füllen Sie Ihre persönlichen Daten mit E-Mail-ID.');
define('BOOKING_DETAILS_EXPIRED_COUPON', 'Ungültige oder abgelaufene Discount Coupon. Bitte geben Sie eine gültige Rabatt-Coupon und erneut versuchen.');
define('BOOKING_DETAILS_ALREADY_USE', 'bereits verwendet.');
define('BOOKING_DETAILS_NOT_CUSTOMER', 'Sie sind nicht bereits Kunde sind oder Ihnen eingegebene Rabatt Gutschein abgelaufen. Bitte geben Sie eine gültige Rabatt-Coupon und erneut versuchen.');
define('BOOKING_DETAILS_NOT_VALID_PART1', 'Rabatt-Coupon gilt nicht für einen Kauf Höhe von');
define('BOOKING_DETAILS_NOT_VALID_PART2', 'Mindestabnahmemenge');

// Text in contact.php
define('CONTACT_TITLE', 'Müssen Sie in Kontakt mit uns aufnehmen?');
define('CONTACT_SUB_DESC', 'Füllen Sie einfach das folgende Formular aus und wir melden uns bei Ihnen so bald wie möglich zu erhalten.');
define('CONTACT_NAME', 'Name');
define('CONTACT_SUBJECT', 'Betrifft');
define('CONTACT_MESSAGE', 'Nachricht');
define('CONTACT_RIGHT_TITLE', 'Impressum');
define('CONTACT_RIGHT_SUB_DESC', 'Antworten brauchen jetzt?');
define('CONTACT_PHONE', 'Telefon');
define('CONTACT_ADDR', 'Unsere Adresse');
define('CONTACT_SEND_BTN', 'Senden');
define('CONTACT_SEND_SUCCESS_MSG', '<b> Danke! </ b> <br> Wir melden uns so bald wie möglich.');
define('CONTACT_SEND_FAILURE', 'Sorry! Ihre E-Mail kann nicht senden. kontaktieren Sie bitte webmaster.');
define('CONTACT_EMAIL_SUBJ', 'Kontaktformular Nachricht.');

// Text in rooms-tariff.php
define('TARIFF_TITLE', 'Raumtarif');
define('TARIFF_REGULAR_PRICE', 'Reguläre Preise');
define('TARIFF_OFFERS_PRICE', 'Angebot Preise');
define('TARIFF_FROM', 'Von');
define('TARIFF_TO', 'Um');

// Text in gallery.php
define('GALLERY_PAGE', 'Galerie Seite');

// booking failure
define('BOOKING_FAILURE_TITLE', 'Buchung Failure');
define('BOOKING_FAILURE_ERROR_9', 'Direkter Zugang zu dieser Seite ist eingeschränkt.');
define('BOOKING_FAILURE_ERROR_13', 'Jemand anderes bereits erwerben die Reservierung Sperre für Räume von Ihnen angegeben. Reservierung Sperre automatisch nach wenigen Minuten bei der Buchung eine erfolgreiche oder misslungene von der anderen Person veröffentlicht werden. Bitte ändern Sie Ihre Suchkriterien und versuchen Sie es erneut.');
define('BOOKING_FAILURE_ERROR_22', 'Undefined Zahlungsmethode ausgewählt. Bitte kontaktieren Sie Administrator.');
define('BOOKING_FAILURE_ERROR_25', 'Konnte Email-Benachrichtigung zu senden. Bitte kontaktieren Sie den technischen Support.');

//booking confirm
define('BOOKING_CONFIRM_TITLE', 'Buchung abgeschlossen');
define('BOOKING_CONFIRM_MSG', '<h4> Danke! </ h4> Ihre Buchung bestätigt. Rechnung geschickt in Ihrer E-Mail-Adresse.');

//javascript alert  text messages*****************
define('INDEX_JAVASCRIPT_BOOKING_NUMBER', 'Bitte geben Sie Buchungsnummer.');
define('INDEX_JAVASCRIPT_CHECK_IN', 'Bitte geben Sie Check-in Datum.');
define('INDEX_JAVASCRIPT_CHECK_OUT', 'Bitte geben Sie Abreisetag.');
define('INDEX_JAVASCRIPT_DIGIT_ONLY', 'Nur Ziffern');
define('INDEX_JAVASCRIPT_BOOKING_SEARCH', 'Bitte wählen Sie mindestens ein Zimmer, um fortzufahren.');

define('DETAILS_JAVASCRIPT_FNAME', 'Geben Sie Ihren Vornamen');
define('DETAILS_JAVASCRIPT_LNAME', 'Geben Sie Ihren Nachnamen');
define('DETAILS_JAVASCRIPT_STR_ADDR', 'Geben Sie Ihre Anschrift');
define('DETAILS_JAVASCRIPT_CITY', 'Geben Sie Ihre Stadt');
define('DETAILS_JAVASCRIPT_STATE', 'Geben Sie Ihren Stand');
define('DETAILS_JAVASCRIPT_ZIP', 'Geben Sie Ihre Postleitzahl');
define('DETAILS_JAVASCRIPT_COUNTRY', 'Geben Sie Ihr Land');
define('DETAILS_JAVASCRIPT_PHONE', 'Geben Sie Ihre Telefonnummer');
define('DETAILS_JAVASCRIPT_EMAIL', 'Geben Sie eine gültige E-Mail');
define('DETAILS_JAVASCRIPT_PAYMENT', 'Wählen Zahlungsmethode');
define('DETAILS_JAVASCRIPT_TOS', 'Bitte akzeptieren AGB');
define('DETAILS_JAVASCRIPT_COUPON_DISPLAY', 'Bewerben Rabatt Gutschein wenn Sie');
define('CONTACT_JAVASCRIPT_NAME', 'Geben Sie Ihren Namen');
?>
