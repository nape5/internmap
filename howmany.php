<?php
require_once ('../../mysqli_connect.php');

// users
$sql_query = "SELECT * FROM users";
$result = mysqli_query($dbc, $sql_query);

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
$num = mysqli_num_rows($result);
}
echo '<div style="float:left;">';
echo 'Users: ' . $num . '<br />';

$sql_query = "SELECT * FROM users";
$result = mysqli_query($dbc, $sql_query);

echo '<table border=1 cellspacing=0 cellpadding=2><tr><td>ID</td><td>Name</td></tr>';
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
$user_id = $row['user_id'];
$firstname = $row['first_name'];
$lastname = $row['last_name'];
$fullname = $firstname . ' ' . $lastname;
echo '<tr><td>' . $user_id . '</td><td>' . $fullname . '</td></tr>';
}
echo '</table>';
echo '</div>';


// agencies
$sql_query = "SELECT * FROM agency";
$result = mysqli_query($dbc, $sql_query);

while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
$num = mysqli_num_rows($result);
}
echo '<div style="float:left; margin:0 0 0 20px;">';
echo 'Agencies: ' . $num . '<br />';

$sql_query = "SELECT * FROM agency";
$result = mysqli_query($dbc, $sql_query);

echo '<table border=1 cellspacing=0 cellpadding=2><tr><td>ID</td><td>Name</td></tr>';
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
$user_id = $row['agency_id'];
$name = $row['name'];
echo '<tr><td>' . $user_id . '</td><td>' . $name . '</td></tr>';
}
echo '</table>';
echo '</div>';

?>
	