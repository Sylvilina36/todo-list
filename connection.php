<?php
// Connection to database
$dbhostname = 'sql10.freesqldatabase.com';
$dbdatabase = 'sql10769018';
$dbuser = 'sql10769018';
$dbpass = 'KYcJIQzpCE';

// Create connection
$conn = new mysqli($dbhostname, $dbuser, $dbpass, $dbdatabase);

// Check connection
if ($conn->connect_error) {
    die("Could not connect to DB Server on $dbhostname: " . $conn->connect_error);
}
?>