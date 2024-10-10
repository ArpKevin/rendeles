<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENDELÉS modul</title>
</head>
<style id="inlineCSS">
    td{
        border: 1px solid black;
        padding: 10px;
    }
    td:first-child{
        background-color: grey;
        font-weight: bold;
        font-style: italic;
    }
</style>
<body>
<?php
print"<h1>Rendelés modul</h1>";
?>

<ul>
    <li>Felhasználók</li>
    <ul>
        <li><a href="?t=user&m=list">Listázás</a></li>
        <li><a href="?t=user&m=add">Új felhasználó</a></li>
    </ul>
    <li>Termékek</li>
    <ul>
        <li><a href="?t=product&m=list">Listázás</a></li>
        <li><a href="?t=product&m=add">Új termék</a></li>
    </ul>
    <li>Rendelések</li>
    <ul>
        <li><a href="?t=order&m=list">Listázás</a></li>
        <li><a href="?t=order&m=add">Új rendelés</a></li>
    </ul>
</ul>

<?php

global $DB;

function cimsor($felirat, $alcim){
    print"<h1>$felirat/$alcim</h1>";
}

function opcio_kiir($sor){
    print "<option value=".$sor['kulcs'].">".$sor['opcio']."</option>";
}

$DB = @mysqli_connect("localhost", "root", null, "rendeles") or die("Hiba az adatbázis csatlakozásakor!");

if (isset($_GET['t']) && isset($_GET['m'])) switch ($_GET['t']) {
    case 'user':
        if ($_GET['m'] == "list") {
            cimsor("felhasználó", "lista");
            print("<table>");
            $Q = mysqli_query($DB,"SELECT *, LEFT(u_name, 2) as neve FROM `t_users` ORDER BY u_name");
            while ($sor = mysqli_fetch_array($Q)) {
                print "<tr><td>" . $sor['neve'] . "</td><td>" . $sor['login'] . "</td><td>" . $sor['email'] . "</td></tr>";
            }
            print("</table>");
            
        }
        else if ($_GET['m'] == "add"){
            cimsor("felhasználó", "hozzáadás");
            
            if(isset($_POST['add_u'])){
                // print"USER-ADD";
                $u_name = $_POST['u_name'];
                $login = $_POST['login'];
                $pw = $_POST['pw'];
                $email = $_POST['email'];

                if($u_name<>'' &&  $login<>'' && $email<>'' ){
                    mysqli_query($DB, "INSERT INTO `t_users` (u_name,login,pw,email) VALUES  ('$u_name','$login','$pw','$email')");
                    if (mysqli_error($DB) == "") print'Felhasználó hozzáadva.'; else print 'Hiányos adatlap';
                }
            }

            else {

            print'
            <form method="POST">
                Neve:<br><input type="text" name="u_name" maxlength="30"><br>
                Login:<br><input type="text" name="login" maxlength="20"><br>
                Jelszó:<br><input type="password" name="pw" maxlength="20"><br>
                Email:<br><input type="text" name="email" maxlength="50"><br>
                <button name="add_u">Hozzáad</button>
            </form>
            ';
            }

        }
        break;
    case 'product':
        if ($_GET['m'] == "list") {
            cimsor("termék", "lista");
            print("<table>");
            $Q = mysqli_query($DB,"SELECT * FROM `t_products` ORDER BY p_name");
            while ($sor = mysqli_fetch_array($Q)) {
                print "<tr><td>" . $sor['p_name'] . "</td><td>" . $sor['unit'] . "</td><td>" . $sor['price'] . "</td></tr>";
            }
            print("</table>");
        }
        else if ($_GET['m'] == "add"){
            cimsor("termék", "hozzáadás");

            if(isset($_POST['add_p'])){
                // print"USER-ADD";
                $p_name = $_POST['p_name'];
                $unit = $_POST['unit'];
                $price = $_POST['price'];

                if($p_name<>'' &&  $unit<>'' && $price>0){
                    mysqli_query($DB, "INSERT INTO `t_products` (p_name,unit,price) VALUES ('$p_name','$unit',$price)");
                    if (mysqli_error($DB) == "") print'Termék hozzáadva.'; else print 'Hiányos adatlap';
                }
            }

            else {

            print'
            <form method="POST">
                Neve:<br><input type="text" name="p_name" maxlength="30"><br>
                Egység:<br><input type="text" name="unit" maxlength="20"><br>
                Ár:<br><input type="number" name="price"><br>
                <button name="add_p">Hozzáad</button>
            </form>
            ';
            }
        }
        break;
    case 'order':
        if ($_GET['m'] == "list") {
            cimsor("rendelés", "lista");
            print"<select name=filter_u><option value='0'>?kicsoda</option>";
            $Q = mysqli_query($DB,"SELECT id AS kulcs, u_name AS opcio FROM `t_users` ORDER BY u_name");
            while ($sor = mysqli_fetch_array($Q)) {
                opcio_kiir($sor);
            }
            print"</select>";
            mysqli_free_result($Q);

            print"<select name=filter_u><option value='0'>?micsoda</option>";
            $Q = mysqli_query($DB,"SELECT id AS kulcs, p_name AS opcio FROM `t_products` ORDER BY p_name");
            while ($sor = mysqli_fetch_array($Q)) {
                opcio_kiir($sor);
            }
            print"</select>";
            mysqli_free_result($Q);
        }
        else {
            cimsor("rendelés", "hozzáadás");


            if(isset($_POST['add_o'])){
                // print"USER-ADD";
                $u_id = $_POST['u_id'];
                $p_id = $_POST['p_id'];
                $quantity = $_POST['quantity'];
                $o_date = date('Y-m-d');

                if($u_id>0 &&  $p_id>0 && $quantity>0){
                    mysqli_query($DB, "INSERT INTO `t_orders` (u_id,p_id,quantity,o_date) VALUES ($u_id,$p_id,$quantity,'$o_date')");
                    if (mysqli_error($DB) == "") print'Rendelés hozzáadva.'; else print 'Hiányos adatlap';
                }
            }

            else {

            print'
            <form method="POST">
                <br>Kinek:<select name="u_id">';
                $Q = mysqli_query($DB,"SELECT id AS kulcs, u_name AS opcio FROM `t_users` ORDER BY u_name");
                while ($sor = mysqli_fetch_array($Q)) {
                    opcio_kiir($sor);
                }
                print'</select>';
                mysqli_free_result($Q);
                print'<br>Minek:<select name="p_id">';
                $Q = mysqli_query($DB,"SELECT id AS kulcs, p_name AS opcio FROM `t_products` ORDER BY p_name");
                while ($sor = mysqli_fetch_array($Q)) {
                    opcio_kiir($sor);
                }
                print'</select>';
                mysqli_free_result($Q);
                print'
                <br>Mennyiség:<input type="number" name="quantity""><br>
                Dátum:<input type="date" name="quantity"><br>
                <button name="add_o">Hozzáad</button>
            </form>
            ';
        }
        break;
    }
}
?>

</body>
</html>