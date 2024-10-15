<?php
//ORDERS
class Order_obj extends Alap_obj
{
  public $filterek;
  public function opcio($sor)
  {
    return "<option value='" . $sor['kulcs'] . "'>" . $sor['opcio'] . "</option>";
  }
  public function select_kiir($t, $n, $m1, $m2)
  {
    print "<select name='$n'><option value='0'>?válassz</option>";
    $Q = mysqli_query($this->DB, "SELECT id AS kulcs,CONCAT($m1,' | ',$m2) AS opcio FROM `t_" . $t . "s` ORDER BY $m1");
    while ($sor = mysqli_fetch_array($Q))
      print $this->opcio($sor);
    print "</select>";
  }
  public function lista()
  {
    parent::lista();
    print "Szűrés:<br><form method='post' onchange=''>";
    $this->select_kiir("user", "u_filter", "u_name", "login");
    if (isset($_POST['u_filter']) && intval($_POST['u_filter']) > 0)
      $this->filterek .= " AND u_id=" . $_POST['u_filter'];
    $this->select_kiir("product", "p_filter", "p_name", "unit");
    if (isset($_POST['p_filter']) && intval($_POST['p_filter']) > 0)
      $this->filterek .= " AND p_id=" . $_POST['p_filter'];
    print "<br><button name='filter'>Keres</button></form>";
    print "<table>";
    $Q = mysqli_query($this->DB, "SELECT (SELECT CONCAT(u_name,'=',login) FROM t_users WHERE id=u_id) AS u_info,p_name,unit,quantity,(quantity*price) AS itemprice 
   FROM `t_products`,`t_orders` WHERE (`t_products`.id=`t_orders`.p_id " . $this->filterek . ")");
    while ($sor = mysqli_fetch_array($Q))
      print "<tr><th>" . $sor['p_name'] . "</th><td>" . $sor['quantity'] . " " . $sor['unit'] . "</td><td>" . $sor['itemprice'] . " Ft</td><td>" . $sor['u_info'] . "</td></tr>";
    print "</table>";
  }
  public function adatlap_kiir()
  {
    parent::adatlap_kiir();
    print "<form method='post'>Kinek:<br>";
    $this->select_kiir("user", "u_id", "u_name", "login");
    print "<br>Mit:<br>";
    $this->select_kiir("product", "p_id", "p_name", "unit");
    print "<br>
     Mennyiség:<br><input type='text' name='quantity' maxlength='5'><br>	
	 <button name='add_o'>Hozzáad</button>
	 </form>
	 ";
  }
  public function adatlap_hozzaad()
  {
    parent::adatlap_hozzaad();
    $u_id = $_POST['u_id'];
    $p_id = $_POST['p_id'];
    $quantity = $_POST['quantity'];
    $o_date = date('Y-m-d');
    if ($u_id > 0 && $p_id > 0 && $quantity > 0) {
      mysqli_query($this->DB, "INSERT INTO `" . $this->tabla . "` (u_id,p_id,quantity,o_date) VALUES ($u_id,$p_id,$quantity,'$o_date')");
      if (mysqli_error($this->DB) == '')
        print "Rendelés hozzáadva :)";
    } else
      print "Hiányos adatlap!";
  }
}
;

$order = new Order_obj("t_orders", "Rendelések");

?>