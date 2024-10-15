<!DOCTYPE html>
<html lang="hu">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

   <meta http-equiv="content-language" content="hu">
   <title>RENDELÉS modul</title>
   <style id="inlineCSS">
      th,
      td {
         padding: 5px;
         border: 1px solid gray
      }

      th {
         background-color: silver
      }

      select {
         padding: 10px
      }
   </style>
   <script>
   </script>

<body>
   <?php
   print "<h1>RENDELÉS modul (v2)</h1>
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

   //ALAP OBJEKTUM
   class Alap_obj
   {
      public $tabla;
      public $felirat;
      public $DB;
      function __construct($tabla, $felirat)
      {
         $this->DB = mysqli_connect("localhost", "root", null, "rendeles");
         $this->tabla = $tabla;
         $this->felirat = $felirat;
      }
      public function cimsor_kiir($alcim)
      {
         print "<h2>" . $this->felirat . " / $alcim</h2>";
      }
      public function lista()
      {
         $this->cimsor_kiir("lista");
      }
      public function adatlap_kiir()
      {
         $this->cimsor_kiir("hozzáadás (form)");
      }
      public function adatlap_hozzaad()
      {
         $this->cimsor_kiir("hozzáadás (sql)");
      }
   }
   //
   
   if (isset($_GET['t']) && isset($_GET['m']))
      switch ($_GET['t']) {
         case "user":
            include_once("user_obj.php");
            if ($_GET['m'] == "list") {
               $user->lista();
            } elseif ($_GET['m'] == "add") {
               if (isset($_POST['add_u'])) {
                  $user->adatlap_hozzaad();
               } else
                  $user->adatlap_kiir();
            }
            break;
         case "product":
            include_once("product_obj.php");
            if ($_GET['m'] == "list") {
               $product->lista();
            } elseif ($_GET['m'] == "add") {
               if (isset($_POST['add_p'])) {
                  $product->adatlap_hozzaad();
               } else
                  $product->adatlap_kiir();
            }
            break;
         case "order":
            include_once("order_obj.php");
            if ($_GET['m'] == "list") {
               $order->lista();
            } elseif ($_GET['m'] == "add") {
               if (isset($_POST['add_o'])) {
                  $order->adatlap_hozzaad();
               } else
                  $order->adatlap_kiir();
            }
            break;
      }

   ?>
</body>

</html>