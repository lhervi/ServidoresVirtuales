<?php 

//MENUINICIO, MENUINDEX, MENULINEABASE, MENUDASHBOARD, MENUVROPS



?>

<div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">                  
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              
              <li class="nav-item">
                <!--<a class="nav-link active" aria-current="page" href="/CTISCR/view/index.php">Login</a>-->
                <a class="nav-link active" aria-current="page" href="/CTISCR/view/index.php">Home</a>                
              </li>

              <li class="nav-item">
                <!--<a class="nav-link active" aria-current="page" href="/CTISCR/view/index.php">Login</a>-->
                <a class="nav-link active" aria-current="page" href="/CTISCR/vROps/bitacora.php">Bitacora</a>
              </li>
              
              <!--li class="nav-item">                  
                <a class="nav-link" href="/CTISCR/view/lineabase.php">Gestión de Linea Base</a>              
              </li>
              <li class="nav-item">                  
                <a class="nav-link" href="/CTISCR/view/Dashboard.php">Dashboard</a>              
              </li-->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Gestión de datos
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="/CTISCR/vROps/view/Vrops.php">vROps</a></li>                  
                  <!-- <li><a class="dropdown-item" href="/CTISCR/view/bsm.php">BSM</a></li>-->                  
                  <!--li><a class="dropdown-item" href="/CTISCR/view/index.php">Home</a></li-->
                  <!--li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li-->
                </ul>
              </li>
              <!--li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
              </li-->
              <li class="nav-item">
                <!--<a class="nav-link active" aria-current="page" href="/CTISCR/view/index.php">Login</a>-->
                <a class="nav-link active" aria-current="page" href="<?php echo MENUINICIO ?>">Login</a>
              </li>
            </ul>
            <!--form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form-->
          </div>
        </div>
      </nav>
</div>