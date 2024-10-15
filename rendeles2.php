<!DOCTYPE html>
<html lang="hu">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="content-language" content="hu">
<title>RENDELÉS modul</title>
<style id="inlineCSS">
 th,td {padding:5px;border: 1px solid gray}
 th {background-color: silver}
 select {padding:10px}
</style>
<script>
</script>
<body>
<?php
print "<h1>RENDELÉS modul (v1)</h1>
<ul>
<li>Felhasználók</li>
 <ul>
  <li><a href='?t=user&m=list'>Listázás</a></li>
  <li><a href='?t=user&m=add'>Új felhasználó</a></li>
 </ul>
<li>Termékek</li>
 <ul>
  <li><a href='?t=product&m=list'>Listázás</a></li>
  <li><a href='?t=product&m=add'>Új termék</a></li>
 </ul>
<li>Rendelések</li>
 <ul>
  <li><a href='?t=order&m=list'>Listázás</a></li>
  <li><a href='?t=order&m=add'>Új rendelés</a></li>
 </ul>
</ul>
";
print "";

global $DB;

class Alap_obj{
    public $tabla;
    public $felirat;
    public $alcim;
    public $DB;
    function  __construct($tabla,$felirat,$alcim,$DB){
    $this->tabla=$tabla;
    $this->felirat=$felirat;
    $this->alcim=$alcim;
    }
}

function cimsor($felirat,$alcim)
{
 print "<h2>$felirat / $alcim</h2>";	
};

function opcio_kiir($sor)
{
 print "<option value='".$sor['kulcs']."'>".$sor['opcio']."</option>";	
}

$DB = mysqli_connect("localhost","root",null,"rendeles");

if (isset($_GET['t']) && isset($_GET['m']))
switch ($_GET['t'])
{
 case "user":
   
   if ($_GET['m']=="list")
   {
	cimsor("Felhasználó","lista");  
	print "<table>";
	$Q=mysqli_query($DB,"SELECT * FROM `t_users` ORDER BY u_name");
	while ($sor=mysqli_fetch_array($Q))
		print "<tr><th>".$sor['u_name']."</th><td>".$sor['login']."</td><td>".$sor['email']."</td></tr>";
	print "</table>";
	mysqli_free_result($Q);
   }
   elseif ($_GET['m']=="add")
   {
	cimsor("Felhasználó","hozzáadás");   
	if (isset($_POST['add_u']))
	{
	 $u_name=$_POST['u_name'];	 
	 $login=$_POST['login'];
	 $pw=$_POST['pw'];
	 $email=$_POST['email'];
	 if ($u_name<>'' && $login<>'' && $email<>'')
	 {
	  mysqli_query($DB,"INSERT INTO `t_users` (u_name,login,pw,email) VALUES ('$u_name','$login','$pw','$email')");
	  if (mysqli_error($DB)=='') print "Felhasználó hozzáadva :)";
	 } else print "Hiányos adatlap!";	 
	}
	else
	print "
    <form method='post'>
    Neve:<br><input type='text' name='u_name' maxlength='30'><br>
	Login:<br><input type='text' name='login' maxlength='20'><br>
	Jelszó:<br><input type='password' name='pw' maxlength='20'><br>
	Email:<br><input type='text' name='email' maxlength='50'><br>
	<button name='add_u'>Hozzáad</button>
	</form>
	";
   }
 break; 
 case "product":   
   if ($_GET['m']=="list")
   {
	cimsor("Termék","lista");  
    print "<table>";
	$Q=mysqli_query($DB,"SELECT * FROM `t_products` ORDER BY p_name");
	while ($sor=mysqli_fetch_array($Q))
		print "<tr><th>".$sor['p_name']."</th><td>".$sor['unit']."</td><td>".$sor['price']."</td></tr>";
	print "</table>";	
	mysqli_free_result($Q);
   }
   elseif ($_GET['m']=="add")
   {
	cimsor("Termék","hozzáadás");   
	if (isset($_POST['add_p']))
	{
	 $p_name=$_POST['p_name'];	 
	 $unit=$_POST['unit'];
	 $price=$_POST['price'];	 
	 if ($p_name<>'' && $unit<>'' && $price>0)
	 {
	  mysqli_query($DB,"INSERT INTO `t_products` (p_name,unit,price) VALUES ('$p_name','$unit','$price')");
	  if (mysqli_error($DB)=='') print "Termék hozzáadva :)";
	 } else print "Hiányos adatlap!";	 
	}
	else
	print "
    <form method='post'>
    Neve:<br><input type='text' name='p_name' maxlength='30'><br>
	Egysége:<br><input type='text' name='unit' maxlength='10'><br>
	Ára:<br><input type='text' name='price' maxlength='4'><br>	
	<button name='add_p'>Hozzáad</button>
	</form>
	";
   }
 break; 
 case "order":
   if ($_GET['m']=="list")
   {
	cimsor("Rendelés","lista");
	$filters='';
	print "<form method='post' onchange=''>";
    print "<select name='filter_u'><option value='0'>?kicsoda</option>";
	$Q=mysqli_query($DB,"SELECT id AS kulcs,CONCAT(u_name,' | ',login) AS opcio FROM `t_users` ORDER BY u_name");
	while ($sor=mysqli_fetch_array($Q)) opcio_kiir($sor);		
	print "</select>";
		if (isset($_POST['filter']) && intval($_POST['filter_u'])>0) $filters.=" AND u_id=".$_POST['filter_u'];
		mysqli_free_result($Q);
		print "<select name='filter_p'><option value='0'>?micsoda</option>";
		$Q=mysqli_query($DB,"SELECT id AS kulcs,p_name AS opcio FROM `t_products` ORDER BY p_name");
		while ($sor=mysqli_fetch_array($Q)) opcio_kiir($sor);
		print "</select>";
		if (isset($_POST['filter']) && intval($_POST['filter_p'])>0) $filters.=" AND p_id=".$_POST['filter_p'];
		mysqli_free_result($Q);
		print "<br><button name='filter'>Keres</button></form>";
		print "<table>($filters)";
		$Q=mysqli_query($DB,"SELECT (SELECT CONCAT(u_name,'=',login) FROM t_users WHERE id=u_id) AS u_info,p_name,unit,quantity,(quantity*price) AS itemprice 
		FROM `t_products`,`t_orders` WHERE (`t_products`.id=`t_orders`.p_id $filters)");
		while ($sor=mysqli_fetch_array($Q)) 
			print "<tr><th>".$sor['p_name']."</th><td>".$sor['quantity']." ".$sor['unit']."</td><td>".$sor['itemprice']." Ft</td><td>".$sor['u_info']."</td></tr>";
		print "</table>";
   }
   elseif ($_GET['m']=="add")
   {
	cimsor("Rendelés","hozzáadás");   
	if (isset($_POST['add_o']))
	{
	 $u_id=$_POST['u_id'];	 
	 $p_id=$_POST['p_id'];	 
	 $quantity=$_POST['quantity'];	 
	 $o_date=date('Y-m-d');
	 if ($u_id>0 && $p_id>0 && $quantity>0)
	 {
	  mysqli_query($DB,"INSERT INTO `t_orders` (u_id,p_id,quantity,o_date) VALUES ($u_id,$p_id,$quantity,'$o_date')");
	  if (mysqli_error($DB)=='') print "Rendelés hozzáadva :)";
	 } else print "Hiányos adatlap!";	 
	}
	else
	{
	 print "
     <form method='post'>
	 Kinek:<br><select name=u_id>";
	 $Q=mysqli_query($DB,"SELECT id AS kulcs,CONCAT(u_name,' | ',login) AS opcio FROM `t_users` ORDER BY u_name");
	 while ($sor=mysqli_fetch_array($Q)) opcio_kiir($sor);		
	 print "</select><br>
	 Mit:<br><select name=p_id>";
	 $Q=mysqli_query($DB,"SELECT id AS kulcs,p_name AS opcio FROM `t_products` ORDER BY p_name");
	 while ($sor=mysqli_fetch_array($Q)) opcio_kiir($sor);		
	 print "</select><br>
     Mennyiség:<br><input type='text' name='quantity' maxlength='5'><br>	
	 <button name='add_o'>Hozzáad</button>
	 </form>
	 ";
	}
   }
 break; 
}

?>
</body>
</html>