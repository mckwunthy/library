<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--animate cdn link / bootstrap / main css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <title> <?php echo $title ?> </title>
</head>

<body>
    <nav class="navbar navbar-expand navbar-primary bg-dark">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"
                            href="<?php echo BASE_URL . SP . 'library'; ?>">Library</a>
                    </li>
                    <?php
                    //profil si connecté
                    if (isset($_SESSION["user"])) {
                        echo '
                         <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="' . BASE_URL . SP . 'profil">Profil and Books</a>
                        </li>
                        ';
                    }

                    //partie admin (level 9)
                    if (isset($_SESSION["user"]) && $_SESSION["user"]["level"] == 9) {
                        echo ' <li class="nav-item">
                        <a class="nav-link" href="' . BASE_URL . SP . 'borrow">Borrow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="' . BASE_URL . SP . 'managebooks">Manage books</a>
                    </li>
                </ul>';
                    }
                    ?>

                    <?php
                    if (!isset($_SESSION["user"])) {
                        echo '
                     <form class="d-flex" method="POST" action="authentification">
                        <input class="me-2" type="email" placeholder="Email" name="email" required>
                        <input class="me-2" type="password" placeholder="Password" name="password" required>
                        <button class="btn btn-outline-light me-2 bg-success" type="submit">Sing in</button>
                        </form>
                        <button type="button" class="btn btn-outline-light btn-sm bg-primary createAccount" style="height: 38px;">Create account</button>
                        ';
                    }

                    //deconnexion
                    if (isset($_SESSION["user"])) {
                        echo '
                       <form class="d-flex" method="POST" action="deconnexion">
                        <button class="btn btn-outline-light me-2 bg-danger" type="submit">Deconnexion</button>
                        </form>
                       ';
                    }
                    ?>

            </div>
        </div>
    </nav>

    <div class="container">
        <div class="layout d-none"></div>
        <?php echo $content ?>
    </div>

    <!--jquery / bootstrap popper / bootstrap js / others js script-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="<?php echo BASE_URL . SP; ?>js/app.js"></script>
    <script src="<?php echo BASE_URL . SP; ?>js/main.js"></script>
</body>

</html>