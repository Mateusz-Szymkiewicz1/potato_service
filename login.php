<?php
// połączenie z bazą danych
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

// odebranie danych z formularza logowania
$username = $_POST["username"];
$password = $_POST["password"];

// sprawdzenie danych logowania w bazie danych
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // użytkownik zalogowany pomyślnie
  session_start();
  $_SESSION["username"] = $username;
  header("Location: home.php");
} else {
  // błędne dane logowania
  echo "Błędne dane logowania. Spróbuj ponownie.";
}

$conn->close();
?>
