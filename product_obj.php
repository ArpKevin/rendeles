<?php
//PRODUCT
class Product_obj extends Alap_obj
{
  public function lista()
  {
    parent::lista();
    print "<table>";
    $Q = mysqli_query($this->DB, "SELECT * FROM `" . $this->tabla . "` ORDER BY p_name");
    while ($sor = mysqli_fetch_array($Q))
      print "<tr><th>" . $sor['p_name'] . "</th><td>" . $sor['unit'] . "</td><td>" . $sor['price'] . "</td></tr>";
    print "</table>";
    mysqli_free_result($Q);
  }
  public function adatlap_kiir()
  {
    parent::adatlap_kiir();
    print "
    <form method='post'>
    Neve:<br><input type='text' name='p_name' maxlength='30'><br>
	Egysége:<br><input type='text' name='unit' maxlength='10'><br>
	Ára:<br><input type='text' name='price' maxlength='4'><br>	
	<button name='add_p'>Hozzáad</button>
	</form>
	";
  }
  public function adatlap_hozzaad()
  {
    parent::adatlap_hozzaad();
    $p_name = $_POST['p_name'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    if ($p_name <> '' && $unit <> '' && $price > 0) {
      mysqli_query($this->DB, "INSERT INTO `" . $this->tabla . "` (p_name,unit,price) VALUES ('$p_name','$unit','$price')");
      if (mysqli_error($this->DB) == '')
        print "Termék hozzáadva :)";
    } else
      print "Hiányos adatlap!";
  }
}
;

$product = new Product_obj("t_products", "Termékek");
?>