<?php
function verifParams()
{
  if (isset($_POST) && sizeof($_POST) > 0) {
    foreach ($_POST as $key => $value) {
      $data = trim($value);
      $data = stripslashes($data);
      $data = strip_tags($data);
      $data = htmlspecialchars($data);
      $_POST[$key] = $data;
    }
    //print_r($_POST);exit();
  }
}

// print_r($_SERVER);
// exit();
function checkData($tab, $file_tab)
{
  global $model;
  $result_books = array();
  $data = array();

  foreach ($tab as $key => $value) {
    $value = trim($value);
    $data[$key] = $value;

    //GENERAL
    if ($value === "") {
      $result_books[$key] = "Le champs " . $key . "  ne peut pas être vide";
    }

    //SPECIFIQUE
    if ($key == "title" && !isset($result_books[$key])) {
      if (strlen($value) < 2) {
        $result_books[$key] = "Le titre doit faire au moins 2 caratères !";
      }
      if (strlen($value) > 30) {
        $result_books[$key] = "Le titre doit faire au maxium 30 caratères !";
      }
    }

    if ($key == "author" && !isset($result_books[$key])) {
      if (strlen($value) < 5) {
        $result_books[$key] = "Le nom d'autheurs doit faire au moins 5 caratères !";
      }
      if (strlen($value) > 20) {
        $result_books[$key] = "Le nom d'autheurs doit faire au plus 20 caratères  !";
      }
    }

    if ($key == "isbn" && !isset($result_books[$key])) {
      if (!strlen($value) == 13) {
        $result_books[$key] = "Le numero ISBN se compose de 10 chiffres !";
      }
    }

    if ($key == "gender" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,2}/', $value))) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value > 14) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value < 1) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
    }

    if ($key == "pages" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,}/', $value))) {
        $result_books[$key] = "veullez saisir une chiffre correcte !";
      }
      if ($value < 0) {
        $result_books[$key] = "le nombre ne peut etre négatif !";
      }
    }
    if ($key == "resume" && !isset($result_books[$key])) {
      if (strlen($value) > 100) {
        $result_books[$key] = "le resume doit compter moins de 100 caractères !";
      }
    }

    if ($key == "edition" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,2}/', $value))) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value > 11) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value < 1) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
    }
  }
  /*  print_r($data);
  echo '<br><br><br>';
  print_r($result_books);
  echo '<br><br><br>';
  print_r($file_tab);
  exit();*/
  //traitement sur le fichier

  $file_name = $file_tab["image"]["name"];
  $table_explode = explode('.', $file_name);
  $data["fileExtension"] = $table_explode[count($table_explode) - 1];

  $data["file_size"] = $file_tab["image"]["size"];
  $data["file_error"] = $file_tab["image"]["error"];
  $data["file_tmp"] = $file_tab["image"]["tmp_name"];


  if (isset($file_tab["image"]["name"]) && !isset($result_books["name"])) {
    if ($file_tab["image"]["name"] === "") {
      $result_books["name"] = "veullez choisir une image d'illustration !";
    } else {
      $extension_autorise = ["jpeg", "png", "jpg"];
      if (!in_array($data["fileExtension"], $extension_autorise)) {
        $result_books["name"] = "extension d'images autorisées : jpeg, jpg, png !";
      }
    }
  }

  if (isset($file_tab["image"]["size"]) && !isset($result_books["size"])) {
    if ($data["file_size"] > 5000000) {
      $result_books["size"] = "taille maximal autorisée 5Mo !";
    }
  }

  //rename file and save it if not error
  if (empty($result_books)) {
    $message_name = "";
    $code = "az12345678MWXC9ertyuiUIOPQSDFGHJopqsdfgh123456789jklmwxcvbn123456789AZERTYKLVBN";

    $index = 1;
    while ($index <= 20) {
      $message_name .= $code[rand(0, 78)];
      $index++;
    }

    $data["file_rename"] = $message_name;


    //copie du fichier sur serveur
    $file_fullname = $data["file_rename"] . '.' . $data["fileExtension"];
    $file_folder = "images" . SP . "books" . SP . $data["file_rename"] . '.' . $data["fileExtension"];
    if ($data["file_error"] == 0) {
      $result_copy = copy($data["file_tmp"], $file_folder);
    }

    //enregistrement des donnees en bdd
    $createBooksResult = $model->createBooks($data["title"], $data["author"], $data["isbn"], $data["gender"], $data["pages"], $data["resume"], $data["publication_date"], $data["edition"], $file_fullname);
    // echo $createBooksResult;
    // print_r($data);
    // exit();

    if ($createBooksResult) {
      echo '
      <div class="d-grid gap-2">
        <button class="btn btn-success" type="button">Book enregistré avec succès</button>
      </div>
      ';
    } else {
      echo '
      <div class="d-grid gap-2">
        <button class="btn btn-danger" type="button">Echec de l\'enregistrement !</button>
      </div>
      ';
    }
  } else {
    $createBooksResult = NULL;
  }

  return [$result_books, $data, $createBooksResult];
}
function updatData($tab, $file_tab)
{
  global $model;
  $result_books = array();
  $data = array();

  foreach ($tab as $key => $value) {
    $value = trim($value);
    $data[$key] = $value;

    //GENERAL
    if ($value === "") {
      $result_books[$key] = "Le champs " . $key . "  ne peut pas être vide";
    }

    //SPECIFIQUE
    if ($key == "title" && !isset($result_books[$key])) {
      if (strlen($value) < 2) {
        $result_books[$key] = "Le titre doit faire au moins 2 caratères !";
      }
      if (strlen($value) > 30) {
        $result_books[$key] = "Le titre doit faire au maxium 30 caratères !";
      }
    }

    if ($key == "author" && !isset($result_books[$key])) {
      if (strlen($value) < 5) {
        $result_books[$key] = "Le nom d'autheurs doit faire au moins 5 caratères !";
      }
      if (strlen($value) > 20) {
        $result_books[$key] = "Le nom d'autheurs doit faire au plus 20 caratères  !";
      }
    }

    if ($key == "isbn" && !isset($result_books[$key])) {
      if (!strlen($value) == 13) {
        $result_books[$key] = "Le numero ISBN se compose de 10 chiffres !";
      }
    }

    if ($key == "gender" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,2}/', $value))) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value > 14) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value < 1) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
    }

    if ($key == "pages" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,}/', $value))) {
        $result_books[$key] = "veullez saisir une chiffre correcte !";
      }
      if ($value < 0) {
        $result_books[$key] = "le nombre ne peut etre négatif !";
      }
    }
    if ($key == "resume" && !isset($result_books[$key])) {
      if (strlen($value) > 100) {
        $result_books[$key] = "le resume doit compter moins de 100 caractères !";
      }
    }

    if ($key == "edition" && !isset($result_books[$key])) {
      if (!(preg_match('/[0-9]{1,2}/', $value))) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value > 11) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
      if ($value < 1) {
        $result_books[$key] = "veullez choisir un genre dans la liste !";
      }
    }
  }

  if (empty($result_books)) {

    //mise a jour des donnees en bdd
    $updatBooksResult = $model->updatBooks($data["title"], $data["author"], $data["isbn"], $data["pages"], $data["resume"], $data["publication_date"], $data["edition"]);

    if ($updatBooksResult) {
      echo '
      <div class="d-grid gap-2">
        <button class="btn btn-success" type="button">Book updat avec succès</button>
      </div>
      ';
    } else {
      echo '
      <div class="d-grid gap-2">
        <button class="btn btn-danger" type="button">Echec de la mise à jour !</button>
      </div>
      ';
    }
  } else {
    $updatBooksResult = NULL;
  }

  return [$result_books, $data, $updatBooksResult];
}


function displayLibrary()
{
  global $book_borrow;
  global $books;
  global $edition;
  global $gender;
  $result = '
  <div class="row">
  <div class="col-9 border border-end-primary d-flex flex-wrap">';
  if ($books) {

    foreach ($books as $key => $value) {
      // echo SRC . SP . 'images' . SP . 'books' . SP . $value["image"];
      // exit();
      $result .= '
  <div class="card m-3" style="width: 18rem;">
  <img src="./images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="illustration">
  <div class="card-body">
    <h5 class="card-title"><strong>Title : </strong>' . $value["title"] . '</h5>
     <p class="card-text"><strong>Resume : </strong>
     ' . $value["resume"] . '
     </p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
    <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
    <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
    <li class="list-group-item"><strong>Publication : </strong>' . $value["publication"] . '</li>
    <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
    <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
  </ul>
  <div class="card-body">
  ';
      if (isset($_SESSION["user"])) {
        $email_to_show = $_SESSION["user"]["email"];
        $id_to_show = $_SESSION["user"]["id"];
      } else {
        $email_to_show = "";
        $id_to_show = "";
      }
      $button_at_end = '
        <form action="askborrow" method="POST">
          <input type="hidden" name="email_borrower" value="' . $email_to_show . '"/>
<input type="hidden" name="id_borrower" value="' . $id_to_show . '"/>
          <input type="hidden" name="book_borrowed_id" value="' . $value["id"] . '"/>
          <input type="hidden" name="book_borrowed_isbn" value="' . $value["isbn"] . '"/>
          <input type="submit" name="ask_borrow" value="Borrow"/>
        </form>
        ';
      if ($book_borrow) {

        for ($i = 0; $i < count($book_borrow); $i++) {
          if (($book_borrow[$i]["book_id"] == $value["id"]) &&
            ($book_borrow[$i]["book_isbn"] == $value["isbn"]) && ($book_borrow[$i]["borrow_date"] != NULL) &&
            ($book_borrow[$i]["return_date"] == NULL)
          ) { //livre indisponible / emprunte==> on ecrase la valeur precedante
            $button_at_end = '
<button type="button" class="btn btn-warning btn-sm">
    Déjà emprunté
</button>
';
          }
          if (($book_borrow[$i]["book_id"] == $value["id"]) && ($book_borrow[$i]["book_isbn"] == $value["isbn"]) &&
            ($book_borrow[$i]["borrow_date"] == NULL) && ($book_borrow[$i]["user_email"] == $email_to_show)
          ) {
            //en cours de traitement ==> on ecrase la valeur precedante
            $button_at_end = '
<button type="button" class="btn btn-primary btn-sm">
    Validation en attente
</button>
';
          }
        }
      }
      $result .= $button_at_end;
      $result .= '
</div>
</div>';
    }
  }
  $result .= '</div>
<div class="col-3">
    <div class="books_search_box"></div>
    <div class="availBorrowBookBox"></div>
</div>
</div>
';
  return $result;
}

function displayAskborrow()
{
  global $model;
  $result = '';

  //redirection : protection
  if (!$_SESSION["user"]["level"]) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  if (isset($_POST["ask_borrow"])) {
    $var = $model->createBorow(
      $_POST["book_borrowed_id"],
      $_POST["book_borrowed_isbn"],
      $_POST["id_borrower"],
      $_POST["email_borrower"]
    );
    if ($var) {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-success" type="button">
        votre demande à été prise en compte
    </button>
</div>';
    } else {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-danger" type="button">
        echec de la demande ! veuillez rééssèyer plus tard !
    </button>
</div>';
    }
  }
  $result .= displayLibrary();
  return $result;
}

function displayManagebooks()
{
  global $gender;
  global $books;
  global $edition;
  global $error_and_data;

  //redirection : protection
  if ($_SESSION["user"]["level"] != 9) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  //traitement des donnees denregistrement de book
  if (isset($_POST["submit_managebooks"]) && !empty($_FILES)) {
    $error_and_data = checkData($_POST, $_FILES);
  }

  //error
  $error_title = isset($error_and_data[0]["title"]) && !empty($error_and_data[0]["title"]) ? $error_and_data[0]["title"] :
    null;
  $error_author = isset($error_and_data[0]["author"]) && !empty($error_and_data[0]["author"]) ?
    $error_and_data[0]["author"] : null;
  $error_isbn = isset($error_and_data[0]["isbn"]) && !empty($error_and_data[0]["isbn"]) ? $error_and_data[0]["isbn"] :
    null;
  $error_gender = isset($error_and_data[0]["gender"]) && !empty($error_and_data[0]["gender"]) ?
    $error_and_data[0]["gender"] : null;
  $error_pages = isset($error_and_data[0]["pages"]) && !empty($error_and_data[0]["pages"]) ? $error_and_data[0]["pages"] :
    null;
  $error_resume = isset($error_and_data[0]["resume"]) && !empty($error_and_data[0]["resume"]) ?
    $error_and_data[0]["resume"] : null;
  $error_publication_date = isset($error_and_data[0]["publication_date"]) &&
    !empty($error_and_data[0]["publication_date"]) ? $error_and_data[0]["publication_date"] : null;
  $error_edition = isset($error_and_data[0]["edition"]) && !empty($error_and_data[0]["edition"]) ?
    $error_and_data[0]["edition"] : null;
  $error_image_name = isset($error_and_data[0]["name"]) && !empty($error_and_data[0]["name"]) ? $error_and_data[0]["name"]
    : null;
  $error_image_size = isset($error_and_data[0]["size"]) && !empty($error_and_data[0]["size"]) ? $error_and_data[0]["size"]
    : null;

  // print_r($error_image_size);
  // print_r($error_image_name);
  // exit();
  //value
  $value_title = isset($error_and_data[1]["title"]) && !empty($error_and_data[1]["title"]) && empty($error_and_data[2]) ?
    $error_and_data[1]["title"] : null;
  $value_author = isset($error_and_data[1]["author"]) && !empty($error_and_data[1]["author"]) && empty($error_and_data[2])
    ? $error_and_data[1]["author"] : null;
  $value_isbn = isset($error_and_data[1]["isbn"]) && !empty($error_and_data[1]["isbn"]) && empty($error_and_data[2]) ?
    $error_and_data[1]["isbn"] : null;
  // $value_gender = isset($error_and_data[1]["gender"]) && !empty($error_and_data[1]["gender"]) ? $error_and_data[1]["gender"] : null;
  $value_pages = isset($error_and_data[1]["pages"]) && !empty($error_and_data[1]["pages"]) && empty($error_and_data[2]) ?
    $error_and_data[1]["pages"] : null;
  $value_resume = isset($error_and_data[1]["resume"]) && !empty($error_and_data[1]["resume"]) && empty($error_and_data[2])
    ? $error_and_data[1]["resume"] : null;
  $value_publication_date = isset($error_and_data[1]["publication_date"]) &&
    !empty($error_and_data[1]["publication_date"]) && empty($error_and_data[2]) ? $error_and_data[1]["publication_date"] :
    null;
  // $value_edition = isset($error_and_data[1]["edition"]) && !empty($error_and_data[1]["edition"]) ? $error_and_data[1]["edition"] : null;


  $result = '
<div class="row mt-5">
    <div class="col-5 here-col-5">
        <div class="addbooks">add book</div>
        <form method="POST" action="managebooks" enctype="multipart/form-data">
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                <input type="text" name="title" value="' . $value_title . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_title . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Author</span>
                <input type="text" name="author" value="' . $value_author . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_author . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">ISBN</span>
                <input type="number" name="isbn" value="' . $value_isbn . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_isbn . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Gender</span>
                <select class="form-select" name="gender" aria-label="Default select example">
                    <option selected>choose gender</option>';

  foreach ($gender as $key => $value) {
    $result .= '<option value="' . $key + 1 . '">' . strtoupper($value["gender_name"]) . '</option>';
  }
  $result .= '
                </select>
            </div>
            <div class="error">' . $error_gender . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Pages</span>
                <input type="number" name="pages" value="' . $value_pages . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_pages . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Resume</span>
                <input type="textarea" name="resume" value="' . $value_resume . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_resume . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Publication date</span>
                <input type="date" name="publication_date" value="' . $value_publication_date . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_publication_date . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Edition</span>
                <select class="form-select" name="edition" aria-label="Default select example">
                    <option selected>choose edition</option>';

  foreach ($edition as $key => $value) {
    $result .= '<option value="' . $key + 1 . '">' . strtoupper($value["edition_name"]) . '</option>';
  }
  $result .= '
                </select>
            </div>
            <div class="error">' . $error_edition . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Image</span>
                <input type="file" name="image" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_image_name . '<br>' . $error_image_size . '</div>
            <div class="input-group mb-2">
                <input type="submit" name="submit_managebooks" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default">
            </div>
        </form>
    </div>
    <div class="col-7">
        <div class="addbooks">book list</div>
        <div class="table_content">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Author</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Pages</th>
                        <th scope="col">Edition</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    ';
  if ($books) {
    foreach ($books as $key => $value) {
      $result .= '
                    <tr>
                        <th scope="row" class="cle">' . $key + 1 . '</th>
                        <td scope="col" class="title">' . $value['title'] . '</td>
                        <td scope="col" class="gender">' . $gender[$value['gender'] - 1]['gender_name'] . '</td>
                        <td scope="col" class="author">' . $value['author'] . '</td>
                        <td scope="col" class="isbn">' . $value['isbn'] . '</td>
                        <td scope="col" class="pages">' . $value['pages'] . '</td>
                        <td scope="col" class="edition">' . $edition[$value['edition'] - 1]['edition_name'] . '</td>
                        <td scope="col">
                            <button class="update view anta-regular">
                                <form method="POST" action="updatbooks">
                                    <input type="hidden" name="isbn_to_updat" value="' . $value['isbn'] . '" />
                                    <input type="submit" value="Updt" />
                                </form>
                            </button>
                        </td>
                        <td scope="col">
                            <button class="delete view anta-regular">
                                <form method="POST" action="deletebooks">
                                    <input type="hidden" name="isbn_to_delete" value="' . $value['isbn'] . '" />
                                    <input type="hidden" name="isbn_to_delete_id" value="' . $value['id'] . '" />
                                    <input type="submit" value="Delt" />
                                </form>
                            </button>
                        </td>
                        <td scope="col"></td>
                    </tr>
                    ';
    }
  }
  $result .= '
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Author</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Pages</th>
                        <th scope="col">Edition</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
';

  return $result;
}

function displayDeletebooks()
{
  global $model;
  global $gender;
  global $edition;
  $result = "";

  //redirection : protection
  if ($_SESSION["user"]["level"] != 9) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  if (isset($_POST["delete_this_isbn"]) && !empty($_POST["isbn_to_delete"])) {
    $deleteStatus = $model->deleteBooksByIsbn($_POST["isbn_to_delete_id"], $_POST["isbn_to_delete"]);
    // print_r($deleteStatus);
    // exit();

    if ($deleteStatus) {
      unlink(SRC . SP . 'images' . SP . 'books' . SP . $_POST["isbn_to_delete_image"]);
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-success" type="button">
        l\'ISBN : ' . $_POST["isbn_to_delete"] . ' a été supprimé de de la base de données
    </button>
</div>';
      $result .= displayLibrary();
      return $result;
    } else {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-danger" type="button">
        Echec de suppression de l\'ISBN : ' . $_POST["isbn_to_delete"] . '
    </button>
</div>';
      $result .= displayLibrary();
      return $result;
    }
  }

  $booksByIsbn = $model->getBooksByIsbn($_POST["isbn_to_delete"]);
  // var_dump($booksByIsbn[0]["nbre_book"]);
  // exit();
  if ($booksByIsbn[0]["nbre_book"] && !isset($_POST["delete_this_isbn"]) && empty($_POST["delete_this_isbn"])) {
    foreach ($booksByIsbn as $key => $value) {
      $result .= '<div class="card" style="width: 18rem;">
    <img src="' . BASE_URL . SP . 'images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="image livre">
  <div class="card-body">
    <h5 class="card-title"><strong>' . $value["title"] . '</strong></h5>
    <p class="card-text"><em>' . $value["resume"] . '</em></p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
    <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
    <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
    <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
    <li class="list-group-item"><strong>Publication : </strong>' . $value["date_pub"] . '</li>
    <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
    <li class="list-group-item"><strong>Stock : </strong>' . $value["nbre_book"] . '</li>
  </ul>
  <div class="card-body">
    <button type="button" class="btn btn-primary">
    <form action="deletebooks" method="POST">
    <input type="hidden" name="isbn_to_delete_id" value="' . $_POST["isbn_to_delete_id"] . '"/>
    <input type="hidden" name="isbn_to_delete" value="' . $_POST["isbn_to_delete"] . '"/>
    <input type="hidden" name="isbn_to_delete_image" value="' . $value["image"] . '"/>
    <input type="submit" value="delete 1 book" name="delete_this_isbn"/>
    </form>
    </button>
  </div>
</div>';
    }
    return $result;
  } else {
    $result .= '
    <div class="d-grid gap-2">
    <button class="btn btn-primary" type="button">
      l\' ISBN : ' . $_POST["isbn_to_delete"] . ' n\'est pas dans la base de donnee </button>
</div>';

    $result .= displayManagebooks();
    return $result;
  }
}


function displayUpdatbooks()
{
  global $model;
  global $gender;
  global $books;
  global $edition;
  global $error_and_data;

  //redirection : protection
  if ($_SESSION["user"]["level"] != 9) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  if (isset($_POST["isbn_to_updat"]) && !empty($_POST["isbn_to_updat"])) {
    $books_to_updat = $model->getBooksByIsbn($_POST["isbn_to_updat"]);
  }

  //traitement des donnees denregistrement de book

  if (isset($_POST["submit_updatbooks"])) {
    foreach ($_POST as $key => $value) {
      $books_to_updat[0][$key] = $value;
    }
    $error_and_data = updatData($_POST, $_FILES);
  }

  //error
  $error_title = isset($error_and_data[0]["title"]) && !empty($error_and_data[0]["title"]) ? $error_and_data[0]["title"] :
    null;
  $error_author = isset($error_and_data[0]["author"]) && !empty($error_and_data[0]["author"]) ?
    $error_and_data[0]["author"] : null;
  $error_isbn = isset($error_and_data[0]["isbn"]) && !empty($error_and_data[0]["isbn"]) ? $error_and_data[0]["isbn"] :
    null;
  $error_gender = isset($error_and_data[0]["gender"]) && !empty($error_and_data[0]["gender"]) ?
    $error_and_data[0]["gender"] : null;
  $error_pages = isset($error_and_data[0]["pages"]) && !empty($error_and_data[0]["pages"]) ? $error_and_data[0]["pages"] :
    null;
  $error_resume = isset($error_and_data[0]["resume"]) && !empty($error_and_data[0]["resume"]) ?
    $error_and_data[0]["resume"] : null;
  $error_publication_date = isset($error_and_data[0]["publication_date"]) &&
    !empty($error_and_data[0]["publication_date"]) ? $error_and_data[0]["publication_date"] : null;
  $error_edition = isset($error_and_data[0]["edition"]) && !empty($error_and_data[0]["edition"]) ?
    $error_and_data[0]["edition"] : null;
  $error_image_name = isset($error_and_data[0]["name"]) && !empty($error_and_data[0]["name"]) ? $error_and_data[0]["name"]
    : null;
  $error_image_size = isset($error_and_data[0]["size"]) && !empty($error_and_data[0]["size"]) ? $error_and_data[0]["size"]
    : null;

  // print_r($error_image_size);
  // print_r($error_image_name);
  // exit();
  //value
  $value_title = isset($error_and_data[1]["title"]) && !empty($error_and_data[1]["title"]) && empty($error_and_data[2]) ?
    $error_and_data[1]["title"] : $books_to_updat[0]["title"];
  $value_author = isset($error_and_data[1]["author"]) && !empty($error_and_data[1]["author"]) && empty($error_and_data[2])
    ? $error_and_data[1]["author"] : $books_to_updat[0]["author"];
  $value_isbn = isset($error_and_data[1]["isbn"]) && !empty($error_and_data[1]["isbn"]) && empty($error_and_data[2]) ?
    $_POST["isbn"] : $books_to_updat[0]["isbn"];
  // $value_gender = isset($error_and_data[1]["gender"]) && !empty($error_and_data[1]["gender"]) ? $error_and_data[1]["gender"] : null;
  $value_pages = isset($error_and_data[1]["pages"]) && !empty($error_and_data[1]["pages"]) && empty($error_and_data[2]) ?
    $error_and_data[1]["pages"] : $books_to_updat[0]["pages"];
  $value_resume = isset($error_and_data[1]["resume"]) && !empty($error_and_data[1]["resume"]) && empty($error_and_data[2])
    ? $error_and_data[1]["resume"] : $books_to_updat[0]["resume"];
  // $value_publication_date = isset($error_and_data[1]["publication_date"]) &&
  !empty($error_and_data[1]["publication_date"]) && empty($error_and_data[2]) ? $error_and_data[1]["publication_date"] :
    $books_to_updat[0]["date_pub"];
  // $value_edition = isset($error_and_data[1]["edition"]) && !empty($error_and_data[1]["edition"]) ? $error_and_data[1]["edition"] : null;


  $result = '
<div class="row mt-5">
    <div class="col-5 here-col-5">
        <div class="addbooks">add book</div>
        <form method="POST" action="updatbooks" enctype="multipart/form-data">
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                <input type="text" name="title" value="' . $value_title . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_title . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Author</span>
                <input type="text" name="author" value="' . $value_author . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_author . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">ISBN</span>
                <input type="hidden" name="isbn" value="' . $value_isbn . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Pages</span>
                <input type="number" name="pages" value="' . $value_pages . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_pages . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Resume</span>
                <input type="textarea" name="resume" value="' . $value_resume . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_resume . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Publication date</span>
                <input type="date" name="publication_date" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default">
            </div>
            <div class="error">' . $error_publication_date . '</div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Edition</span>
                <select class="form-select" name="edition" aria-label="Default select example">
                    <option selected>choose edition</option>';

  foreach ($edition as $key => $value) {
    $result .= '<option value="' . $key + 1 . '">' . strtoupper($value["edition_name"]) . '</option>';
  }
  $result .= '
                </select>
            </div>
            <div class="error">' . $error_edition . '</div>
            <div class="input-group mb-2">
                <input type="submit" name="submit_updatbooks" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default">
            </div>
        </form>
    </div>
    <div class="col-7">
        <div class="addbooks">book list</div>
        <img src="images' . SP . 'books' . SP . $books_to_updat[0]["image"] . '"  class="card-img-top updt_img" alt="illustration">
    </div>
</div>
</div>
';

  return $result;
}

function displayBorrow()
{
  global $model;
  global $book_borrow;
  global $book_stats;
  global $book_user_stats;

  $result = '';

  //redirection : protection
  if ($_SESSION["user"]["level"] != 9) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  //approval borrow
  if (isset($_POST["validate_borrow"])) {
    $var_approval = $model->updatBooksBorrowList($_POST["book_id_to_approv"], $_POST["book_isbn_to_approv"]);
    if ($var_approval) {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-success" type="button">
        le livre ISBN : ' . $_POST["book_isbn_to_approv"] . ' a été prêté avec succès
    </button>
</div>';
    } else {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-danger" type="button">
        echec du pêt du livre ISBN : ' . $_POST["book_isbn_to_approv"] . '
    </button>
</div>';
    }
  }
  //return borrow
  if (isset($_POST["return_borrow"])) {
    $var_approval = $model->updatBooksBorrowReturn($_POST["book_id_to_return"], $_POST["book_isbn_to_return"]);
    if ($var_approval) {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-success" type="button">
        le livre ISBN : ' . $_POST["book_isbn_to_return"] . ' a été retourné avec succès
    </button>
</div>';
    } else {
      $result .= '
<div class="d-grid gap-2">
    <button class="btn btn-danger" type="button">
        echec de retour du livre ISBN : ' . $_POST["book_isbn_to_return"] . '
    </button>
</div>';
    }
  }


  $result .= '
<div class="row">
    <div class="col-6">
        <div class="addbooks">demande en attente</div>
        <div class="table_content">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">book id</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">user email</th>
                        <th scope="col">start</th>
                        <th scope="col">end</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    ';
  if ($book_borrow) {
    foreach ($book_borrow as $key => $value) {
      if (!($value['borrow_date'] != NULL && $value['return_date'] != NULL)) {
        $result .= '
                    <tr>
                        <th scope="row" class="cle">' . $key + 1 . '</th>
                        <td scope="col" class="book_id">' . $value['book_id'] . '</td>
                        <td scope="col" class="book_isbn">' . $value['book_isbn'] . '</td>
                        <td scope="col" class="user_email">' . $value['user_email'] . '</td>
                        <td scope="col" class="star">' . $value['borrow_date'] . '</td>
                        <td scope="col" class="end">' . $value['return_date'] . '</td>
                        <td scope="col">
                            ';
        if ($value['borrow_date'] == NULL) {
          $result .= '
                            <button class="approval_borrow view anta-regular btn-success">
                                <form method="POST" action="borrow">
                                    <input type="hidden" name="book_isbn_to_approv"
                                        value="' . $value['book_isbn'] . '" />
                                    <input type="hidden" name="book_id_to_approv" value="' . $value['book_id'] . '" />
                                    <input type="submit" name="validate_borrow" value="Valid" />
                                </form>
                            </button>
                        </td>
                    </tr>
                    ';
        }
        if (($value['borrow_date'] != NULL) && ($value['return_date'] == NULL)) {
          $result .= '
                    <button class="return_borrow view anta-regular btn-danger">
                        <form method="POST" action="borrow">
                            <input type="hidden" name="book_isbn_to_return" value="' . $value['book_isbn'] . '" />
                            <input type="hidden" name="book_id_to_return" value="' . $value['book_id'] . '" />
                            <input type="submit" name="return_borrow" value="Return" />
                        </form>
                    </button>
                    </td>
                    </tr>
                    ';
        }
      }
    }
  }
  $result .= '
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">book id</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">user email</th>
                        <th scope="col">start</th>
                        <th scope="col">end</th>
                        <th scope="col"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-3">
        <div class="addbooks">livres EMRPUNTéS</div>
        <div class="table_content">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Jours</th>
                    </tr>
                </thead>
                <tbody>
                    ';
  if ($book_stats) {
    // print_r($book_stats);
    // exit();
    foreach ($book_stats as $key => $value) {

      $result .= '
                    <tr>
                        <th scope="row" class="cle">' . $key + 1 . '</th>
                        <td scope="col" class="book_isbn">' . $value['book_isbn'] . '</td>
                        <td scope="col" class="duree">' . $value['duree'] . '</td>
                    </tr>
                    ';
    }
  }
  $result .= '
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Jours</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-3">
        <div class="addbooks">emprunteurs</div>
        <div class="table_content">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">user</th>
                        <th scope="col">emprunts</th>
                        <th scope="col">jours</th>
                    </tr>
                </thead>
                <tbody>';
  if ($book_user_stats) {
    // print_r($book_stats);
    // exit();
    foreach ($book_user_stats as $key => $value) {

      $result .= '
                    <tr>
                        <th scope="row" class="cle">' . $key + 1 . '</th>
                        <td scope="col" class="user_email">' . $value['user_email'] . '</td>
                        <td scope="col" class="nbre_emprunt">' . $value['nbre_emprunt'] . '</td>
                        <td scope="col" class="duree">' . $value['duree'] . '</td>
                    </tr>
                    ';
    }
  }
  $result .= '
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">user</th>
                        <th scope="col">emprunts</th>
                        <th scope="col">jours</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>
';

  return $result;
}

function displayAuthentification()
{
  // $mdp = "adminLibrary";
  // $mdp = sha1($mdp);
  // echo $mdp;
  //on charges les donnees du user
  global $model;
  //redirection : protection
  if (!isset($_POST["email"]) || !isset($_POST["password"])) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  $authentData = $model->authentifier($_POST["email"], $_POST["password"]);

  $result = '';
  if (!$authentData) {
    $result .= '
    <div class="d-grid gap-2">
      <button class="btn btn-danger" type="button">Echec de connexion, veullez reessaye plus tard !</button>
    </div>
    ';
    $result .= displayLibrary();
    return $result;
  } else {
    $_SESSION["user"] = [];
    foreach ($authentData as $key => $value) {
      $_SESSION["user"][$key] = $value;
    }

    $result .= displayLibrary();
    return $result;
  }
}

function displayDeconnexion()
{
  session_destroy();

  header('Location: ' . BASE_URL . SP . 'library');
}

function displayProfil()
{
  global $books;
  global $edition;
  global $gender;
  global $book_borrow;

  //redirection : protection
  if (!$_SESSION["user"]["level"]) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  $result = '
  <div class="row">
    <div class="col-5">
     <div class="addbooks">my profil</div>
      <form method="" action="#" id="user_data">
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Level</span>
                <input type="number" name="user_level" value="' . $_SESSION["user"]["level"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Email</span>
                <input type="email" name="user_level" value="' . $_SESSION["user"]["email"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Name</span>
                <input type="text" name="user_fname" value="' . $_SESSION["user"]["f_name"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Lastname</span>
                <input type="text" name="user_lname" value="' . $_SESSION["user"]["l_name"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Age</span>
                <input type="number" name="user_age" value="' . $_SESSION["user"]["age"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User Sexe</span>
                <input type="text" name="user_sexe" value="' . $_SESSION["user"]["sexe"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">User ID</span>
                <input type="number" name="user_id" value="' . $_SESSION["user"]["id"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Description</span>
                <input type="text" name="user_description" value="' . $_SESSION["user"]["description"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text" id="inputGroup-sizing-default">Adresse</span>
                <input type="text" name="user_description" value="' . $_SESSION["user"]["adress"] . '" class="form-control"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
            </div>
            <div class="input-group mb-2">
                <input type="submit" class="form-control" aria-label="Sizing example input"
                    aria-describedby="inputGroup-sizing-default" id="bt_updat_user" value="Updat Profil">
            </div>
      </form>
    </div>
    <div class="col-7">
      <div class="addbooks">my borrowed books</div>
      <div class="d-flex flex-wrap justify-content-center">
      ';
  if ($books) {

    foreach ($books as $key => $value) {
      if ($book_borrow) {
        for ($i = 0; $i < count($book_borrow); $i++) {
          if (($book_borrow[$i]["book_id"] == $value["id"]) && ($book_borrow[$i]["book_isbn"] == $value["isbn"]) &&
            ($book_borrow[$i]["borrow_date"] != NULL) && ($book_borrow[$i]["return_date"] == NULL) && ($book_borrow[$i]["user_email"] == $_SESSION["user"]["email"])
          ) {
            $result .= '
  <div class="card m-3" style="width: 18rem;">
  <img src="images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="illustration">
  <div class="card-body">
    <h5 class="card-title"><strong>Title : </strong>' . $value["title"] . '</h5>
     <p class="card-text"><strong>Resume : </strong>
     ' . $value["resume"] . '
     </p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
    <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
    <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
    <li class="list-group-item"><strong>Publication : </strong>' . $value["publication"] . '</li>
    <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
    <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
  </ul>
  </div>
  ';
          }
        }
      }
    }
  }
  $result .= '
  </div>
  </div>
  ';
  return $result;
}

function displayUpdatUser()
{
  //redirection : protection
  if (!$_SESSION["user"]["level"]) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  global $model;
  if (isset($_POST["updatProfilData"])) {
    $updatResult = $model->updatUser($_SESSION["user"]["email"], $_POST["user_fname"], $_POST["user_lname"], $_POST["user_age"], $_POST["user_sexe"], $_POST["user_description"], $_POST["user_adress"]);
    $result = '';
    if ($updatResult) {
      //mise à jour des infos
      $_SESSION["user"]["f_name"] = $updatResult["f_name"];
      $_SESSION["user"]["l_name"] = $updatResult["l_name"];
      $_SESSION["user"]["age"] = $updatResult["age"];
      if ($updatResult["sexe"] == 1) {
        $_SESSION["user"]["sexe"] = "masculin";
      } else {
        $_SESSION["user"]["sexe"] = "feminin";
      }
      $_SESSION["user"]["description"] = $updatResult["description"];
      $_SESSION["user"]["adress"] = $updatResult["adress"];
      $result .= '
    <div class="d-grid gap-2">
      <button class="btn btn-success" type="button">
        profil modifié avec succes !
      </button>
    </div>
    ';
    } else {
      $result .= '
    <div class="d-grid gap-2">
      <button class="btn btn-danger" type="button">
        echec de modification !
      </button>
    </div>
    ';
    }
    $result .= displayProfil();
    return $result;
  } else {
    $result = displayProfil();
    return $result;
  }
}

function displayCreatetUser()
{
  global $model;
  //redirection : protection
  if (!isset($_POST["createUserData"])) {
    header('Location: ' . BASE_URL . SP . 'library');
  }

  $result = '';
  if (isset($_POST["createUserData"])) {
    // print_r($_POST);
    // exit();
    $createUserResult = $model->createUser($_POST["user_email"], $_POST["user_password"]);
    if ($createUserResult) {
      $result .= '
      <div class="d-grid gap-2">
      <button class="btn btn-success" type="button">
       Compte créé avec succes !
      </button>
    </div>
      ';
      //chargement des donnees user
      $authentData = $model->authentifier($_POST["user_email"], $_POST["user_password"]);
      $_SESSION["user"] = [];
      foreach ($authentData as $key => $value) {
        $_SESSION["user"][$key] = $value;
      }
      // $result .= displayProfil();
    } else {
      $result .= '
      <div class="d-grid gap-2">
      <button class="btn btn-danger" type="button">
        erreur ! email existe !
      </button>
    </div>
      ';
    }
  }
  $result .= displayProfil();
  return $result;
}

function displayAvailBorrowBook()
{
  global $book_borrow;
  global $books;
  global $edition;
  global $gender;

  $result_tot = '';
  $result_body = '';
  $message_available = '
  <div class="d-grid gap-2">
    <button class="btn btn-primary" type="button">
    Books available !
    </button>
  </div>
  ';
  $result = '
  <div class="row">
  <div class="col-9 border border-end-primary d-flex flex-wrap">';
  if ($books) {

    foreach ($books as $key => $value) {
      // echo SRC . SP . 'images' . SP . 'books' . SP . $value["image"];
      // exit();
      $result_body = '
  <div class="card m-3" style="width: 18rem;">
  <img src="images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="illustration">
  <div class="card-body">
    <h5 class="card-title"><strong>Title : </strong>' . $value["title"] . '</h5>
     <p class="card-text"><strong>Resume : </strong>
     ' . $value["resume"] . '
     </p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
    <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
    <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
    <li class="list-group-item"><strong>Publication : </strong>' . $value["publication"] . '</li>
    <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
    <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
  </ul>
  <div class="card-body">
  ';
      if (isset($_SESSION["user"])) {
        $email_to_show = $_SESSION["user"]["email"];
        $id_to_show = $_SESSION["user"]["id"];
      } else {
        $email_to_show = "";
        $id_to_show = "";
      }
      $button_at_end = '
        <form action="askborrow" method="POST">
          <input type="hidden" name="email_borrower" value="' . $email_to_show . '"/>
<input type="hidden" name="id_borrower" value="' . $id_to_show . '"/>
          <input type="hidden" name="book_borrowed_id" value="' . $value["id"] . '"/>
          <input type="hidden" name="book_borrowed_isbn" value="' . $value["isbn"] . '"/>
          <input type="submit" name="ask_borrow" value="Borrow"/>
        </form>
        ';
      if ($book_borrow) {

        for ($i = 0; $i < count($book_borrow); $i++) {
          if (($book_borrow[$i]["book_id"] == $value["id"]) &&
            ($book_borrow[$i]["book_isbn"] == $value["isbn"]) && ($book_borrow[$i]["borrow_date"] != NULL) &&
            ($book_borrow[$i]["return_date"] == NULL)
          ) { //livre indisponible / emprunte==> on ecrase la valeur precedante
            $button_at_end = '';
            $result_body = '';
          }
          if (($book_borrow[$i]["book_id"] == $value["id"]) && ($book_borrow[$i]["book_isbn"] == $value["isbn"]) &&
            ($book_borrow[$i]["borrow_date"] == NULL) && ($book_borrow[$i]["user_email"] == $email_to_show)
          ) {
            //en cours de traitement ==> on ecrase la valeur precedante
            $button_at_end = '
<button type="button" class="btn btn-primary btn-sm">
    Validation en attente
</button>
';
          }
        }
      }
      if ($button_at_end == '' || $result_body == '') {
        //on ne fait pas de concatenation
      } else {
        $result_tot .= $result_body . $button_at_end;
        $result_tot .= '
</div>
</div>';
      }
    }
  }
  $result .= $result_tot .= '
</div>
<div class="col-3">
    <div class="books_search_box"></div>
    <div class="availBorrowBookBox"></div>
</div>
</div>
';

  return $message_available . $result;
}


function displayNotAvailBorrowBook()
{
  global $book_borrow;
  global $books;
  global $edition;
  global $gender;

  $result_tot = '';
  $result_body = '';
  $message_available = '
  <div class="d-grid gap-2">
    <button class="btn btn-warning" type="button">
    Borrowed books !
    </button>
  </div>
  ';
  $result = '
  <div class="row">
  <div class="col-9 border border-end-primary d-flex flex-wrap">';
  if ($books) {

    foreach ($books as $key => $value) {
      $result_body = '
  <div class="card m-3" style="width: 18rem;">
  <img src="images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="illustration">
  <div class="card-body">
    <h5 class="card-title"><strong>Title : </strong>' . $value["title"] . '</h5>
     <p class="card-text"><strong>Resume : </strong>
     ' . $value["resume"] . '
     </p>
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
    <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
    <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
    <li class="list-group-item"><strong>Publication : </strong>' . $value["publication"] . '</li>
    <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
    <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
  </ul>
  <div class="card-body">
  ';
      if (isset($_SESSION["user"])) {
        $email_to_show = $_SESSION["user"]["email"];
        $id_to_show = $_SESSION["user"]["id"];
      } else {
        $email_to_show = "";
        $id_to_show = "";
      }
      $button_at_end = '';

      if ($book_borrow) {

        for ($i = 0; $i < count($book_borrow); $i++) {
          if (($book_borrow[$i]["book_id"] == $value["id"]) &&
            ($book_borrow[$i]["book_isbn"] == $value["isbn"]) && ($book_borrow[$i]["borrow_date"] != NULL) &&
            ($book_borrow[$i]["return_date"] == NULL)
          ) { //livre indisponible
            $button_at_end = '
<button type="button" class="btn btn-warning btn-sm">
    Déjà emprunté
</button>
';
          }
          if (($book_borrow[$i]["book_id"] == $value["id"]) && ($book_borrow[$i]["book_isbn"] == $value["isbn"]) &&
            ($book_borrow[$i]["borrow_date"] == NULL) && ($book_borrow[$i]["user_email"] == $email_to_show)
          ) {
            //en cours de traitement ==> on ecrase la valeur precedante
            $button_at_end = '';
            $result_body = '';
          }
        }
      }

      if ($button_at_end == '' || $result_body == '') {
        //on ne fait pas de concatenation
      } else {
        $result_tot .= $result_body . $button_at_end;
        $result_tot .= '
</div>
</div>';
      }
    }
  }
  $result .= $result_tot .= '
</div>
<div class="col-3">
    <div class="books_search_box"></div>
    <div class="availBorrowBookBox"></div>
</div>
</div>
';

  return $message_available . $result;
}

function displaySearchBookRequest()
{
  global $model;
  global $book_borrow;
  global $edition;
  global $gender;


  if (isset($_POST["searchBook"])) {
    $books_search = $model->getSearchBook($_POST["item_type"], $_POST["item_value"]);
    // print_r($books_search);
    // exit();
    $message_results = '
    <div class="d-grid gap-2">
      <button class="btn btn-primary" type="button">
      Results for <strong>' . $_POST["item_type"] . ' : ' . $_POST["item_value"] . '</strong>
      </button>
    </div>
    ';
    $result = '
    <div class="row">
    <div class="col-9 border border-end-primary d-flex flex-wrap">';
    if ($books_search) {

      foreach ($books_search as $key => $value) {
        $result .= '
    <div class="card m-3" style="width: 18rem;">
    <img src="images' . SP . 'books' . SP . $value["image"] . '" class="card-img-top" alt="illustration">
    <div class="card-body">
      <h5 class="card-title"><strong>Title : </strong>' . $value["title"] . '</h5>
       <p class="card-text"><strong>Resume : </strong>
       ' . $value["resume"] . '
       </p>
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Author : </strong>' . $value["author"] . '</li>
      <li class="list-group-item"><strong>ISBN : </strong>' . $value["isbn"] . '</li>
      <li class="list-group-item"><strong>Pages : </strong>' . $value["pages"] . '</li>
      <li class="list-group-item"><strong>Publication : </strong>' . $value["publication"] . '</li>
      <li class="list-group-item"><strong>Edition : </strong>' . $edition[$value["edition"] - 1]["edition_name"] . '</li>
      <li class="list-group-item"><strong>Gender : </strong>' . $gender[$value["gender"] - 1]["gender_name"] . '</li>
    </ul>
    <div class="card-body">
    ';
        if (isset($_SESSION["user"])) {
          $email_to_show = $_SESSION["user"]["email"];
          $id_to_show = $_SESSION["user"]["id"];
        } else {
          $email_to_show = "";
          $id_to_show = "";
        }
        $button_at_end = '
          <form action="askborrow" method="POST">
            <input type="hidden" name="email_borrower" value="' . $email_to_show . '"/>
  <input type="hidden" name="id_borrower" value="' . $id_to_show . '"/>
            <input type="hidden" name="book_borrowed_id" value="' . $value["id"] . '"/>
            <input type="hidden" name="book_borrowed_isbn" value="' . $value["isbn"] . '"/>
            <input type="submit" name="ask_borrow" value="Borrow"/>
          </form>
          ';
        if ($book_borrow) {
          for ($i = 0; $i < count($book_borrow); $i++) {
            if (($book_borrow[$i]["book_id"] == $value["id"]) &&
              ($book_borrow[$i]["book_isbn"] == $value["isbn"]) && ($book_borrow[$i]["borrow_date"] != NULL) &&
              ($book_borrow[$i]["return_date"] == NULL)
            ) { //livre indisponible / emprunte==> on ecrase la valeur precedante
              $button_at_end = '
  <button type="button" class="btn btn-warning btn-sm">
      Déjà emprunté
  </button>
  ';
            }
            if (($book_borrow[$i]["book_id"] == $value["id"]) && ($book_borrow[$i]["book_isbn"] == $value["isbn"]) &&
              ($book_borrow[$i]["borrow_date"] == NULL) && ($book_borrow[$i]["user_email"] == $email_to_show)
            ) {
              //en cours de traitement ==> on ecrase la valeur precedante
              $button_at_end = '
  <button type="button" class="btn btn-primary btn-sm">
      Validation en attente
  </button>
  ';
            }
          }
        }
        $result .= $button_at_end;
        $result .= '
  </div>
  </div>';
      }
      $result .= '</div>
  <div class="col-3">
      <div class="books_search_box"></div>
      <div class="availBorrowBookBox"></div>
  </div>
  </div>
  ';
      return $message_results . $result;
    } else {
      $message_results = '
       <div class="d-grid gap-2">
      <button class="btn btn-danger" type="button">
      AUCUN Results for <strong>' . $_POST["item_type"] . ' : ' . $_POST["item_value"] . '</strong>
      </button>
    </div>
      ';
      $result = $message_results . displayLibrary();
      return $result;
    }
  } else {

    //redirection : protection
    header('Location: ' . BASE_URL . SP . 'library');


    $message_results = '
       <div class="d-grid gap-2">
      <button class="btn btn-danger" type="button">
      AUCUN Results for <strong>' . $_POST["item_type"] . ' : ' . $_POST["item_value"] . '</strong>
      </button>
    </div>
      ';
    $result = $message_results . displayLibrary();
    return $result;
  }
}
