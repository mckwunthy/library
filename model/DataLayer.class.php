<?php
//all function needed to get data from or communicate with mysql database via sql request

class DataLayer
{

    private $connexion;

    function __construct() //connexion to db with API PDO
    {
        try {
            $this->connexion = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            //echo "connexion à la base de données réussie";
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }


    /**
     * fonction qui sert à récupérer les genre de livre au sein de la base de données
     * @param rien ne prend pas de paramètre
     * @return array tableau contenant les genres, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getGender()
    {
        $sql = "SELECT * FROM gender";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les livres au sein de la base de données
     * @param rien ne prend pas de paramètre
     * @return array tableau contenant les livre, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getBooks()
    {
        $sql = "SELECT * FROM books";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }
    /**
     * fonction qui sert à récupérer les edition au sein de la base de données
     * @param rien ne prend pas de paramètre
     * @return array tableau contenant les editions, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getEdition()
    {
        $sql = "SELECT * FROM edition";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }


    /**
     * fonction qui créer un book en base de données
     * @param title le titre du livre
     * @param author l'auteur
     * @param isbn L'isbn
     * @param gender le genre
     * @param pages le nombre de pages
     * @param resume le resumé
     * @param publication la date de publication
     * @param edition la maison d'edition
     * @param image le nouveau nom de l'image
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createBooks($title, $author, $isbn, $gender, $pages, $resume, $publication, $edition, $image)
    {
        $sql = "INSERT INTO `books`(`title`, `author`, `isbn`, `gender`, `pages`, `resume`, `publication`, `edition`, `image`) VALUES (:title, :author, :isbn, :gender, :pages, :resume, :publication, :edition, :image)";
        // print_r($sql);
        // exit();
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'gender' => $gender,
                'pages' => $pages,
                'resume' => $resume,
                'publication' => $publication,
                'edition' => $edition,
                'image' => $image
            ));
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }
    /**
     * fonction qui créer un book en base de données
     * @param title le titre du livre
     * @param author l'auteur
     * @param isbn L'isbn
     * @param gender le genre
     * @param pages le nombre de pages
     * @param resume le resumé
     * @param publication la date de publication
     * @param edition la maison d'edition
     * @param image le nouveau nom de l'image
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function updatBooks($title, $author, $isbn, $pages, $resume, $publication, $edition)
    {
        $sql = "UPDATE books SET title = :title, author = :author, pages= :pages, resume = :resume, publication = :publication, edition = :edition WHERE isbn= :isbn";
        // print_r($sql);
        // exit();
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                'title' => $title,
                'author' => $author,
                'isbn' => $isbn,
                'pages' => $pages,
                'resume' => $resume,
                'publication' => $publication,
                'edition' => $edition
            ));
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les livres au sein de la base de données selon isbn
     * @param isbn l'isbn du livre à supprimer
     * @return array tableau contenant les livre, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getBooksByIsbn($isbn)
    {
        $sql = "SELECT COUNT(id) AS nbre_book, title, author, isbn, gender, pages, resume, DATE_FORMAT(publication, '%d/%m/%Y') AS
date_pub, edition, image FROM books WHERE isbn = :isbn";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(':isbn' => $isbn));
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }
    /**
     * fonction qui sert à supprimer un livre de la base de données selon isbn
     * @param id id du livre a supprimer
     * @param isbn isbn du livre a supprimer
     * @return TRUE si la commande a été exécutée, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function deleteBooksByIsbn($id, $isbn)
    {


        $sql = "DELETE FROM books WHERE isbn = :isbn AND id = :id";
        $sql_1 = "DELETE FROM borrow WHERE book_id = :id";
        // print_r($sql_1);
        // exit();
        try {

            $result_1 = $this->connexion->prepare($sql_1);
            $var_1 = $result_1->execute(array(':id' => $id));

            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(':id' => $id, ':isbn' => $isbn));

            // $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }


    /**
     * fonction qui créer une demande d'emprunt
     * @param book_id le id du livre
     * @param book_isbn L'isbn du livre
     * @param user_id L'id du user
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createBorow($book_id, $book_isbn, $user_id, $user_email)
    {
        $sql = "INSERT INTO `borrow`(`book_id`, `book_isbn`, `user_id`, `user_email`) VALUES (:book_id, :book_isbn, :user_id, :user_email)";

        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                'book_id' => $book_id,
                'book_isbn' => $book_isbn,
                'user_id' => $user_id,
                'user_email' => $user_email
            ));

            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les demandes d'emprunts
     * @return array tableau contenant les details sur les emprunts, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getBooksBorrow()
    {
        $sql = "SELECT * FROM borrow";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui met à jour la liste des livres empruntés
     * @param book_id id du livre
     * @param book_isbn L'isbn
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function updatBooksBorrowList($book_id, $book_isbn)
    {
        $sql = "UPDATE borrow SET borrow_date = NOW() WHERE book_id = :book_id AND book_isbn = :book_isbn";

        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':book_isbn' => $book_isbn,
                ':book_id' => $book_id
            ));
            // print_r($var);
            // exit();
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui retourne un livre emprunté
     * @param book_id id du livre
     * @param book_isbn L'isbn
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function updatBooksBorrowReturn($book_id, $book_isbn)
    {
        $sql = "UPDATE borrow SET return_date = NOW() WHERE book_id = :book_id AND book_isbn = :book_isbn";

        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':book_isbn' => $book_isbn,
                ':book_id' => $book_id
            ));
            // print_r($var);
            // exit();
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les statistiques sur les emprunts
     * @return array tableau contenant les details sur les emprunts, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getBooksBorrowStat()
    {
        $sql = "SELECT book_isbn, COUNT(book_isbn) AS nbre_isbn, SUM(DATEDIFF(return_date, borrow_date)) AS duree FROM borrow WHERE borrow_date > 0 AND return_date > 0 GROUP BY book_isbn ";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les statistiques sur les utilisateurs emprunteurs
     * @return array tableau contenant les details sur les emprunts, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getBooksBorrowUserStat()
    {
        $sql = "SELECT user_email, COUNT(user_email) AS nbre_emprunt, SUM(DATEDIFF(return_date, borrow_date)) AS duree FROM borrow WHERE borrow_date > 0 AND return_date > 0 GROUP BY user_email ";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui permet d'authentifier un user
     * @param email l'email du customer
     * @param password le mot de passe du customer
     * @return ARRAY tableau contenant les infos du user si authentification réussie
     * @return FALSE si authentification échouée
     * @return NULL s'il y a une exception déclenchée 
     */
    function authentifier($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        try {
            $result = $this->connexion->prepare($sql);
            $result->execute(array(':email' => $email));
            $data = $result->fetch(PDO::FETCH_ASSOC);
            if ($data && ($data['password'] == sha1($password))) {
                unset($data['password']);
                if ($data['sexe'] == 1) {
                    $data['sexe'] = "masculin";
                } else {
                    $data['sexe'] = "feminin";
                }
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui met à jour les informations du user
     * @param email l'eamil
     * @param f_name le firstname
     * @param l_name le lastname
     * @param age l'age
     * @param sexe le sexe
     * @param description sa description
     * @param adresse l'adresse 
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function updatUser($email, $f_name, $l_name, $age, $sexe, $description, $adress)
    {


        $sql = "UPDATE users SET f_name = :f_name, l_name = :l_name, age= :age, sexe = :sexe, description = :description, adress = :adress WHERE email= :email";
        // print_r($sql);
        // exit();
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                'email' => $email,
                'f_name' => $f_name,
                'l_name' => $l_name,
                'age' => $age,
                'sexe' => $sexe,
                'description' => $description,
                'adress' => $adress
            ));

            $data = array(
                'email' => $email,
                'f_name' => $f_name,
                'l_name' => $l_name,
                'age' => $age,
                'sexe' => $sexe,
                'description' => $description,
                'adress' => $adress
            );
            if ($var) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui créer un user en base de données
     * @param email l'email du user
     * @param password le mot de passe du user
     * @return TRUE sien cas de création avec succès du customer, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createUser($email, $password)
    {
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':email' => $email,
                ':password' => sha1($password)
            ));
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }

    /**
     * fonction qui sert à récupérer les livres au sein de la base de données selon type et valeur
     * @param isbn l'isbn du livre à supprimer
     * @return array tableau contenant les livre, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getSearchBook($item_type, $item_value)
    {
        try {
            if ($item_type == "gender") {
                $sql = 'SELECT * FROM books INNER JOIN gender ON books.gender = gender.id WHERE gender.gender_name REGEXP \'' . $item_value . '\'';
            } else {
                $sql = 'SELECT * FROM books WHERE ' . $item_type . ' REGEXP \'' . $item_value . '\'';
            }

            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data_books = $result->fetchAll(PDO::FETCH_ASSOC);

            if ($data_books) {
                return $data_books;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }
}