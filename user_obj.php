<?php
//USER
class User_obj extends Alap_obj
{
  public function lista()
  {
    parent::lista();
    print "<table>";
    $Q = mysqli_query($this->DB, "SELECT * FROM `" . $this->tabla . "` ORDER BY u_name");
    while ($sor = mysqli_fetch_array($Q))
      print "<tr><th>" . $sor['u_name'] . "</th><td>" . $sor['login'] . "</td><td>" . $sor['email'] . "</td></tr>";
    print "</table>";
    mysqli_free_result($Q);
  }
  public function adatlap_kiir()
  {
    parent::adatlap_kiir();
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
  public function adatlap_hozzaad()
  {
    parent::adatlap_hozzaad();
    $u_name = $_POST['u_name'];
    $login = $_POST['login'];
    $pw = $_POST['pw'];
    $email = $_POST['email'];
    if ($u_name <> '' && $login <> '' && $email <> '') {
      mysqli_query($this->DB, "INSERT INTO `" . $this->tabla . "` (u_name,login,pw,email) VALUES ('$u_name','$login','$pw','$email')");
      if (mysqli_error($this->DB) == '')
        print "Felhasználó hozzáadva :)";
    } else
      print "Hiányos adatlap!";
  }
}
;

$user = new User_obj("t_users", "Felhasználók");
?>