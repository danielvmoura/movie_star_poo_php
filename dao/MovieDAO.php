<?php

require_once("models/Movie.php");
require_once("models/Message.php");

//Review DAO


class MovieDAO implements MovieDAOInterface
{

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildMovie($data) /*Receber dados e fazer objetos de filme*/
    {

        $movie = new Movie();

        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->users_id = $data["users_id"];

        return $movie;
    }

    public function findAll() /*Econtrar todos os filmes do meu BD*/ {}

    public function getLatestMovies() /*Pegar todos os filmes mas em ordem de adição decrescente*/
    {

        $movies = [];

        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $moviesArray = $stmt->fetchAll();

            foreach ($moviesArray as $movie) {
                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }

    public function getMoviesByCategory($category) /*Pegar filmes por determinada categoria*/
    {
        $movies = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category ORDER BY id DESC");

        $stmt->bindParam(":category", $category);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $moviesArray = $stmt->fetchAll();

            foreach ($moviesArray as $movie) {
                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }

    public function getMoviesByUserId($id) /*Pegar filmes do user específico*/ {}

    public function findById($id) /*Encontrar um filme por id*/ {}

    public function findByTitle($title) /*Encontrar um filme por Título específico*/ {}

    public function create(Movie $movie)
    {

        $stmt = $this->conn->prepare("INSERT INTO movies (
        title, description, image, trailer, category, length, users_id
        ) VALUES (
        :title, :description, :image, :trailer, :category, :length, :users_id
        )");

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":users_id", $movie->users_id);

        $stmt->execute();

        //Redireciona para a Home
        $this->message->setMessage("Filme adicionado com sucesso!", "succes", "index.php");
    }

    public function update(Movie $movie) {}

    public function destroy($id) {}
}
