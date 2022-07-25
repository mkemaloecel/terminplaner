<?php
session_start();
?>

<link rel="stylesheet" href="css/app.css">

<?php
include("function/monatsAnsicht.php");
include("function/monatsMiniAnsicht.php");
include("function/jahresAnsicht.php");
include("function/wochentlicheDatenHolen.php");

if (isset($_GET["seite"]) && $_GET["seite"] == "logout") {
	unset($_SESSION["eingeloggt"]);
	unset($_SESSION["pi"]);
	unset($_SESSION["user"]);
	$_SESSION["mitteilung"] = "<div style='color:red'>Sie haben sich ausgeloggt</div>";	
}

if(isset($_POST["benutzer"]) && isset($_POST["passwort"]))
{
	# Datenbankverbindung
	#########################################################################
	$link = mysqli_connect("localhost",	"root", "", "terminplaner");
	mysqli_query($link, "SET names utf8"); # Verbindung auf utf-8 umstellen
	#########################################################################

	$_POST["benutzer"] = mysqli_real_escape_string($link, $_POST["benutzer"]);
	
	# Überprüfen mit der Datenbank
	$sql =  "select * from benutzer where benutzername ='".$_POST["benutzer"]."' "; 
	//echo "$sql";
	//die();
	$antwort = mysqli_query($link, $sql);

	if($antwort->num_rows == 1)
	{
		# Datensatz aus der Datenbank rausholen
		$datensatz = mysqli_fetch_array($antwort);
		
		# Fingerabdruck vergleichen
		if( password_verify($_POST["passwort"], $datensatz["passwort"]) )
		{
			//echo "willkommen ".$datensatz['benutzername']."<br />";
			$_SESSION["eingeloggt"] = true;
			$_SESSION["pi"] = $datensatz['nr'];
			$_SESSION["mitteilung"] = "<div style='color:lightgreen'>Erfolgreich eingeloggt</div>";
			$_SESSION["user"] = $datensatz['benutzername'];
			header("location: ?seite=alle_termine");
			exit;
		} else {
			$_SESSION["mitteilung"] = "<div style='color:red'>Fingerabdruck stimmt nicht</div>";
		}
	}	
	else
	{
		$_SESSION["mitteilung"] = "<div style='color:red'>Benutzer existiert nicht</div>";
	}
	mysqli_close($link);
}


if (isset($_SESSION["eingeloggt"])) {
	
	echo "<a href='?seite=alle_termine'>Alle Termine</a> ";
	echo "<a href='?seite=neuer_termin'>Neuer Termin</a> ";
	echo "<a href='?seite=termin_suchen'>Termin suchen</a> ";
	echo "<a href='?seite=logout'>Logout</a> ";
	echo "<h1>Willkommen ".$_SESSION["user"] . "</h1>";
} else {
	echo "<a href='?seite=home'>Home</a> ";
	echo "<a href='?seite=login'>Login</a> ";
}

if (!isset($_GET["seite"])) {
	$_GET["seite"] = "home";
}
switch ($_GET["seite"]) {
	case "home":
		include("home.php");
		break;
	case "login":
		include("login_formular.php");
		break;
	case "logout":
		include("logout.php");
		break;

	case "alle_termine":
		include("alle_termine_anzeigen.php");
		break;
	case "neuer_termin":
		include("neuer_termin_speichern.php");
		break;
	case "termin_bearbeiten":
		include("termin_bearbeiten.php");
		break;
	case "termin_aendern":
		include("termin_aendern.php");
		break;

	case "termin_loeschen":
		include("termin_loeschen.php");
		break;
	case "termin_endgueltig_loeschen":
		include("termin_endgueltig_loeschen.php");
		break;
	case "termin_suchen":
		include("termin_suchen.php");
		break;
	case "details_ansicht":
		include("details_ansicht.php");
		break;

	default:
		echo "<h1>404 Seite nicht gefunden</h1>";
}
if(isset($_SESSION["mitteilung"]))
{
	echo $_SESSION["mitteilung"]; # Anzeigen
	unset($_SESSION["mitteilung"]); # Entfernen / Löschen
}

?>