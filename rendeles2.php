<!DOCTYPE html>
<html lang="hu">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <meta http-equiv="content-language" content="hu">
   <title>RENDELÉS modul</title>
   <style id="inlineCSS">
      body {
         margin: 0;
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         background-color: #f8f9fa;
         color: #333;
      }

      th, td {
         padding: 10px;
         border: 1px solid #ddd;
      }

      th {
         background-color: #007bff;
         color: white;
         text-align: left;
      }

      select {
         padding: 8px;
         border-radius: 5px;
         border: 1px solid #ccc;
         width: 100%;
         box-sizing: border-box;
      }

      .container {
         display: flex;
         flex-direction: row;
         height: 100vh;
         background-color: #fff;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .module {
         width: 25%;
         background-color: rgba(176, 224, 255, 0.4);
         padding: 20px;
         box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
         border-right: 1px solid #ddd;
      }

      .module h1 {
         color: #007bff;
         font-size: 1.5rem;
         margin-bottom: 20px;
      }

      .module ul {
         list-style: none;
         padding: 0;
      }

      .module li {
         margin: 10px 0;
         font-size: 1rem;
      }

      .module a {
         text-decoration: none;
         color: #007bff;
         font-weight: bold;
      }

      .module a:hover {
         text-decoration: underline;
      }

      .menu {
         width: 75%;
         padding: 40px;
         background-color: #ffffff;
         display: flex;
         flex-direction: column;
      }

      .menu h2 {
         color: #495057;
         font-size: 2rem;
         margin-top: 0;
         margin-bottom: 20px;
      }

      .menu form {
         width: 100%;
         max-width: 600px;
      }

      .menu input, .menu button {
         padding: 10px;
         margin-bottom: 20px;
         border-radius: 5px;
         border: 1px solid #ccc;
         width: 100%;
         box-sizing: border-box;
      }

      .menu button {
         background-color: #007bff;
         color: white;
         border: none;
         cursor: pointer;
         font-weight: bold;
      }

      .menu button:hover {
         background-color: #0056b3;
      }

      @media (max-width: 768px) {
         .container {
            flex-direction: column;
         }

         .module,
         .menu {
            width: 100%;
         }

         .menu {
            padding: 20px;
         }
      }
   </style>
</head>

<body>
   <div class="container">
      <div class="module">
         <?php
         print "<h1>RENDELÉS modul (v2)</h1>
         <ul>
         <h2>Felhasználók</h2>
         <ul>
         <li><a href='?t=user&m=list'>Listázás</a></li>
         <li><a href='?t=user&m=add'>Új felhasználó</a></li>
         </ul>
         <h2>Termékek</h2>
         <ul>
         <li><a href='?t=product&m=list'>Listázás</a></li>
         <li><a href='?t=product&m=add'>Új termék</a></li>
         </ul>
         <h2>Rendelések</h2>
         <ul>
         <li><a href='?t=order&m=list'>Listázás</a></li>
         <li><a href='?t=order&m=add'>Új rendelés</a></li>
         </ul>
         </ul>";
         ?>
      </div>
      <div class="menu">
         <?php
         global $DB;

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

         if (isset($_GET['t']) && isset($_GET['m'])) {
            switch ($_GET['t']) {
               case "user":
                  include_once("user_obj.php");
                  if ($_GET['m'] == "list") {
                     $user->lista();
                  } elseif ($_GET['m'] == "add") {
                     if (isset($_POST['add_u'])) {
                        $user->adatlap_hozzaad();
                     } else {
                        $user->adatlap_kiir();
                     }
                  }
                  break;
               case "product":
                  include_once("product_obj.php");
                  if ($_GET['m'] == "list") {
                     $product->lista();
                  } elseif ($_GET['m'] == "add") {
                     if (isset($_POST['add_p'])) {
                        $product->adatlap_hozzaad();
                     } else {
                        $product->adatlap_kiir();
                     }
                  }
                  break;
               case "order":
                  include_once("order_obj.php");
                  if ($_GET['m'] == "list") {
                     $order->lista();
                  } elseif ($_GET['m'] == "add") {
                     if (isset($_POST['add_o'])) {
                        $order->adatlap_hozzaad();
                     } else {
                        $order->adatlap_kiir();
                     }
                  }
                  break;
            }
         }
         ?>
      </div>
   </div>
</body>

</html>
