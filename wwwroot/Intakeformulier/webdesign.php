<?php
// OPTIONS - PLEASE CONFIGURE THESE BEFORE USE!

$yourEmail = "info@gaab.nl"; // the email address you wish to receive these mails through
$yourWebsite = "Designstudio Gaab"; // the name of your website
$thanksPage = ''; // URL to 'thanks for sending mail' page; leave empty to keep message on the same page 
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "voornaam,achternaam,email"; // names of the fields you'd like to be required as a minimum, separate each field with a comma


// DO NOT EDIT BELOW HERE
$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
	$data = trim(stripslashes(strip_tags($data)));
	return $data;
}
function isBot() {
	$bots = array("Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz");

	foreach ($bots as $bot)
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
			return true;

	if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " ")
		return true;
	
	return false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isBot() !== false)
		$error_msg[] = "No bots please! UA reported as: ".$_SERVER['HTTP_USER_AGENT'];
		
	// lets check a few things - not enough to trigger an error on their own, but worth assigning a spam score.. 
	// score quickly adds up therefore allowing genuine users with 'accidental' score through but cutting out real spam :)
	$points = (int)0;
	
	$badwords = array("adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript");

	foreach ($badwords as $word)
		if (
			strpos(strtolower($_POST['comments']), $word) !== false || 
			strpos(strtolower($_POST['name']), $word) !== false
		)
			$points += 2;
	
	if (strpos($_POST['comments'], "http://") !== false || strpos($_POST['comments'], "www.") !== false)
		$points += 2;
	if (isset($_POST['nojs']))
		$points += 1;
	if (preg_match("/(<.*>)/i", $_POST['comments']))
		$points += 2;
	if (strlen($_POST['name']) < 3)
		$points += 1;
	if (strlen($_POST['comments']) < 15 || strlen($_POST['comments'] > 1500))
		$points += 2;
	if (preg_match("/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['comments']))
		$points += 1;
	// end score assignments

	foreach($requiredFields as $field) {
		trim($_POST[$field]);
		
		if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in all the required fields and submit again.\r\n")
			$error_msg[] = "Please fill in all the required fields and submit again.";
	}

	if (!empty($_POST['name']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['name'])))
		$error_msg[] = "The name field must not contain special characters.\r\n";
	if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
		$error_msg[] = "That is not a valid e-mail address.\r\n";
	if (!empty($_POST['url']) && !preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $_POST['url']))
		$error_msg[] = "Invalid website url.\r\n";
	
	if ($error_msg == NULL && $points <= $maxPoints) {
		$subject = "Automatic Form Email";
		
		$message = "You received this e-mail message through your website: \n\n";
		foreach ($_POST as $key => $val) {
			if (is_array($val)) {
				foreach ($val as $subval) {
					$message .= ucwords($key) . ": " . clean($subval) . "\r\n";
				}
			} else {
				$message .= ucwords($key) . ": " . clean($val) . "\r\n";
			}
		}
		$message .= "\r\n";
		$message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
		$message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";
		$message .= 'Points: '.$points;

		if (strstr($_SERVER['SERVER_SOFTWARE'], "Win")) {
			$headers   = "From: $yourEmail\r\n";
		} else {
			$headers   = "From: $yourWebsite <$yourEmail>\r\n";	
		}
		$headers  .= "Reply-To: {$_POST['email']}\r\n";

		if (mail($yourEmail,$subject,$message,$headers)) {
			if (!empty($thanksPage)) {
				header("Location: $thanksPage");
				exit;
			} else {
				$result = 'Your mail was successfully sent.';
				$disable = true;
			}
		} else {
			$error_msg[] = 'Your mail could not be sent this time. ['.$points.']';
		}
	} else {
		if (empty($error_msg))
			$error_msg[] = 'Your mail looks too much like spam, and could not be sent this time. ['.$points.']';
	}
}
function get_data($var) {
	if (isset($_POST[$var]))
		echo htmlspecialchars($_POST[$var]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Intake formulier Webdesign</title>
	
	<style type="text/css">
		h1 {
		font-size: 1.8em;
		font-family:'Open Sans';
		}
		label {
		font-family: 'Verdana';
		line-height: 2em;
		}
		p {
		font-family: 'Verdana';
		font-size: 0.8em;
		color: #747474;
		}
		p.error, p.success {
			font-weight: bold;
			padding: 10px;
			border: 1px solid;
		}
		p.error {
			background: #ffc0c0;
			color: #900;
		}
		p.success {
			background: #b3ff69;
			color: #4fa000;
		}
	</style>
</head>
<body>

<!--
	Free PHP Mail Form v2.4.4 - Secure single-page PHP mail form for your website
	Copyright (c) Jem Turner 2007-2014
	http://jemsmailform.com/

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	To read the GNU General Public License, see http://www.gnu.org/licenses/.
-->

<?php
if (!empty($error_msg)) {
	echo '<p class="error">ERROR: '. implode("<br />", $error_msg) . "</p>";
}
if ($result != NULL) {
	echo '<p class="success">'. $result . "</p>";
}
?>



<form action="<?php echo basename(__FILE__); ?>" method="post">
<noscript>
		<p><input type="hidden" name="nojs" id="nojs" /></p>
</noscript>
<div>
<div style="max-width:900px; margin: 0 auto;">
<h1>Designstudio Gaab: Intake formulier Webdesign</h1>
<p>Om u zo goed mogelijk van dienst te zijn bij de ontwikkeling van een nieuwe website, wil ik u graag enkele vragen stellen. Mede door uw antwoorden kan er een goed ontwerp gemaakt worden. Mocht u naar aanleiding van deze vragenlijst toch nog vragen hebben, neem dan gerust contact met mij op. De vragenlijst duurt 5-10 minuten.
</p>
<p>* = verplicht invullen
</p>
<p>	<label>Bedrijfsnaam</label><br> 
		<input type="text" name="bedrijfsnaam" id="bedrijfsnaam" /></p>
<p>	<label>Voornaam *</label><br> 
		<input type="text" name="voornaam" id="voornaam" /></p>
<p>	<label>Achternaam *</label><br> 
		<input type="text" name="achternaam" id="achternaam" /></p>	
<p>	<label>E-mail: *</label><br> 
		<input type="text" name="email" id="email" /></p>
<p>	<label>1. Bent u al in het bezit van een website?</label><br>
		<input type="radio" name="website" value="ja"> ja 
		<input type="radio" name="website" value="nee"> nee</p>	
<p>	<label>Zo ja</label><br> 
		<input type="text" name="site" id="site" /></p>
<p>	<label>2. Waarom wilt u een website laten maken?</label><br>
		<input type="radio" name="klant_wil" value="Presenteren"> Ik wil iets presenteren op internet <br>
		<input type="radio" name="klant_wil" value="Verkopen"> Ik wil iets verkopen op internet <br>
		<input type="radio" name="klant_wil" value="Reclame"> Ik wil reclame maken via internet</p>
<p>	<label>Anders</label><br>
		<input type="text" name="anders1" id="anders1" /></p>
<p>	<label>3. Welke doelgroep wilt u bereiken?</label><br>
		<input type="radio" name="doelgroep_is" value="Particulier"> Particulier <br>
		<input type="radio" name="doelgroep_is" value="Zakelijk"> Zakelijk</p>
<p>	<label>4. In welke branche bent u werkzaam? (Meerdere vakken kunnen aangekruist worden)</label><br>
		<input type="checkbox" name="branche1" value="Detailhandel"> Detailhandel <br>
		<input type="checkbox" name="branche2" value="Recreatie/vakantie"> Recreatie/vakantie <br>
		<input type="checkbox" name="branche3" value="Entertainment"> Entertainment<br>
		<input type="checkbox" name="branche4" value="Horeca"> Horeca <br>
		<input type="checkbox" name="branche5" value="Evenementen"> Evenementen <br>
		<input type="checkbox" name="branche6" value="Automatisering"> Automatisering <br>
		<input type="checkbox" name="branche7" value="Gezondheidzorg"> Gezondheidzorg</p>
<p>	<label>Anders</label><br>
		<input type="text" name="anders2" id="anders2" /></p>
<p>	<label>5. Hoe omschrijft u de stijl van uw bedrijf/instelling/vereniging? (Meerdere vakken kunnen aangekruist worden)</label><br>
		<input type="checkbox" name="stijl1" value="Modern"> Modern <br>
		<input type="checkbox" name="stijl2" value="Retro"> Retro <br>
		<input type="checkbox" name="stijl3" value="Chique"> Chique <br>
		<input type="checkbox" name="stijl4" value="Creatief"> Creatief <br>
		<input type="checkbox" name="stijl5" value="Sportief"> Sportief <br>
		<input type="checkbox" name="stijl6" value="Klassiek"> Klassiek <br>
		<input type="checkbox" name="stijl7" value="Zakelijk"> Zakelijk</p>
<p>	<label>Anders</label><br>
		<input type="text" name="anders3" id="anders3" /></p>
<p>	<label>6. Welke woorden beschrijven uw bedrijf het best? (Meerdere vakken kunnen aangekruist worden)</label><br>
		<input type="checkbox" name="woord1" value="Vrouwelijk"> Vrouwelijk <br>
		<input type="checkbox" name="woord2" value="Neutraal"> Neutraal <br>
		<input type="checkbox" name="woord3" value="Mannelijk"> Mannelijk <br>
		<input type="checkbox" name="woord4" value="Luid"> Luid <br>
		<input type="checkbox" name="woord5" value="Bescheiden"> Bescheiden <br>
		<input type="checkbox" name="woord6" value="Betrouwbaar"> Betrouwbaar <br>
		<input type="checkbox" name="woord7" value="Duurzaam"> Duurzaam<br>
		<input type="checkbox" name="woord8" value="Helder"> Helder <br>
		<input type="checkbox" name="woord9" value="Kleurrijk"> Kleurrijk <br>
		<input type="checkbox" name="woord10" value="Kindvriendelijk"> Kindvriendelijk</p>
<p>	<label>Anders</label><br>
		<input type="text" name="anders4" id="anders4" /></p>
<p>	<label>7. Wat vindt u mooie kleuren?</label><br>
		<textarea name="mooie_kleuren" id="mooie_kleuren" rows="5" cols="20"></textarea></p>
<p>	<label>8. Wat vindt u absoluut geen mooie kleuren?</label><br>
		<textarea name="lelijke_kleuren" id="lelijke_kleuren" rows="5" cols="20"></textarea></p>
<p>	<label>9. Maakt u reeds gebruik van een huisstijl? (Meerdere vakken kunnen aangekruist worden)</label><br>
		<input type="checkbox" name="huisstijl1" value="Logo"> Logo <br>
		<input type="checkbox" name="huisstijl2" value="Kleurstelling"> Kleurstelling <br>
		<input type="checkbox" name="huisstijl3" value="Slogan"> Slogan <br>
		<input type="checkbox" name="huisstijl4" value="Lettertypes"> Lettertypes</p>
<p>	<label>Anders</label><br>
		<input type="text" name="anders5" id="anders5" /></p>
<p>	<label>10. Welke functionaliteiten wilt u op uw website? (Meerdere vakken kunnen aangekruist worden)</label><br>
		<input type="checkbox" name="functionaliteit1" value="teksten plaatsen/updaten"> teksten plaatsen/updaten <br>
		<input type="checkbox" name="functionaliteit2" value="zelf afbeeldingen plaatsen/updaten"> zelf afbeeldingen plaatsen/updaten <br>
		<input type="checkbox" name="functionaliteit3" value="fotoalbum op mijn website"> fotoalbum op mijn website<br>
		<input type="checkbox" name="functionaliteit4" value="nieuws kunnen plaatsen op mijn website"> nieuws kunnen plaatsen op mijn website <br>
		<input type="checkbox" name="functionaliteit5" value="Ik wil producten kunnen plaatsen op mijn website"> Ik wil producten kunnen plaatsen op mijn website <br>
		<input type="checkbox" name="functionaliteit6" value="Men moet kunnen afrekenen op mijn website"> Men moet kunnen afrekenen op mijn website <br>
		<input type="checkbox" name="functionaliteit7" value="Ik wil graag iDeal op mijn website"> Ik wil graag iDeal op mijn website <br>
		<input type="checkbox" name="functionaliteit8" value="Ik wil naast vooraf overschrijven nog andere betaalmogelijkheden"> Ik wil naast vooraf overschrijven nog andere betaalmogelijkheden <br>
		<input type="checkbox" name="functionaliteit9" value="Ik wil een contactformulier op mijn website"> Ik wil een contactformulier op mijn website <br>
		<input type="checkbox" name="functionaliteit10" value="Ik wil een bewegende header of slideshow op mijn website"> Ik wil een bewegende header of slideshow op mijn website <br>
		<input type="checkbox" name="functionaliteit11" value="Ik wil social media geïntegreerd hebben op mijn website"> Ik wil social media ge&iuml;ntegreerd hebben op mijn website <br>
		<input type="checkbox" name="functionaliteit12" value="Ik wil een Poll op mijn website"> Ik wil een Poll op mijn website <br>
		<input type="checkbox" name="functionaliteit13" value="Ik wil een logo laten ontwerpen"> Ik wil een logo laten ontwerpen <br>
		<input type="checkbox" name="functionaliteit14" value="Ik wil een logo en huisstijl laten ontwerpen"> Ik wil een logo en huisstijl laten ontwerpen <br>
		<input type="checkbox" name="functionaliteit15" value="Ik wil dat de website geschikt wordt gemaakt voor alle type apparaten (Responsive design)"> Ik wil dat de website geschikt wordt gemaakt voor alle type apparaten (Responsive design)</p>
<p>	<label>11. Heeft u nog andere wensen?</label><br>
		<textarea name="andere_wensen" id="andere_wensen" rows="5" cols="20"></textarea></p>
<p>	<label>12. Bent u reeds in bezit van een domein en/of hosting? Indien nee, ga naar vraag 17</label><br>
		<input type="radio" name="heeft_domein" value="ja"> ja 
		<input type="radio" name="heeft_domein" value="nee"> nee</p>		
<p>	<label>13. Wat is uw domeinnaam?</label><br> 
		<input type="text" name="domeinnaam" id="domeinnaam" /></p>
<p>	<label>14. Bij welke provider is uw domein ondergebracht?</label><br> 
		<input type="text" name="provider" id="provider" /></p>		
<p>	<label>14. Bij welke provider is uw hosting ondergebracht?</label><br> 
		<input type="text" name="provider" id="provider" /></p>	
<p>	<label>15. Beschikt uw hosting over de volgende onderdelen?</label><br>
		<input type="checkbox" name="onderdelen1" value="Scriptondersteuning"> Scriptondersteuning <br>
		<input type="checkbox" name="onderdelen2" value="CMS ondersteuning"> CMS ondersteuning <br>
		<input type="checkbox" name="onderdelen3" value="MYSQL database"> MYSQL database <br>
		<input type="checkbox" name="onderdelen4" value="Geen idee wat deze termen betekenen"> Geen idee wat deze termen betekenen</p>		
<p>	<label>16. Hoeveel MB ruimte heeft uw hosting pakket?</label><br> 
		<input type="text" name="mb_ruimte" id="mb_ruimte" /></p>			
<p>	<label>17. Heeft u al gedacht aan een (nieuwe) domeinnaam?</label><br>
		<input type="radio" name="nieuw_domein" value="ja"> ja 
		<input type="radio" name="nieuw_domein" value="nee"> nee
		<input type="radio" name="nieuw_domein" value="nvt"> niet van toepassing</p>
<p>	<label>Zo ja, welke domeinnaam wilt u, (indien deze vrij is) registreren?</label><br> 
		Keuze 1 <input type="text" name="domein_keuze1" id="domein_keuze1" /> <br>
		Keuze 2 <input type="text" name="domein_keuze2" id="domein_keuze2" /> <br>
		Keuze 3 <input type="text" name="domein_keuze3" id="domein_keuze3" /></p>
<p>	<label>18. Heeft u voorbeelden van websites die u aanspreken?</label><br> 
		Voorbeeld 1 <input type="text" name="voorbeeld1" id="voorbeeld1" /> <br>
		Voorbeeld 2 <input type="text" name="voorbeeld2" id="voorbeeld2" /> <br>
		Voorbeeld 3 <input type="text" name="voorbeeld3" id="voorbeeld3" /> <br>
		Voorbeeld 4 <input type="text" name="voorbeeld4" id="voorbeeld4" /> <br>
		Voorbeeld 5 <input type="text" name="voorbeeld5" id="voorbeeld5" /></p>
<p>	<label>19. Wanneer uw website is opgeleverd, wie gaat deze onderhouden?</label><br>
		<input type="radio" name="onderhoud" value="zelf"> Ik update zelf de website
		<input type="radio" name="onderhoud" value="iemand"> Ik besteed dat uit aan iemand anders
		<input type="radio" name="onderhoud" value="Gaab"> Ik besteed dat uit aan Designstudio Gaab
		<input type="radio" name="onderhoud" value="nvt"> Weet ik nog niet</p>
<p>	<label>11. Heeft u nog specifieke wensen of opmerkingen?</label><br>
		<textarea name="opmerkingen" id="opmerkingen" rows="5" cols="20"></textarea></p>
		
<p>
	<input type="submit" name="submit" id="submit" value="Versturen" <?php if (isset($disable) && $disable === true) echo ' disabled="disabled"'; ?> />
</p>
</form>
</div>
</div>
</body>
</html>
