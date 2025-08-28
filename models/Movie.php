<?php

class Movie
{

    public $id;
    public $title;
    public $description;
    public $image;
    public $trailer;
    public $category;
    public $length;
    public $users_id;
    public $rating;

    public function imageGenerateName()
    {
        return bin2hex(random_bytes(60)) . ".jpg";
    }
}

interface MovieDAOInterface
{

    public function buildMovie($data); /*Receber dados e fazer objetos de filme*/
    public function findAll();         /*Econtrar todos os filmes do meu BD*/
    public function getLatestMovies(); /*Pegar todos os filmes mas em ordem de adiçãoo decrescente*/
    public function getMoviesByCategory($category); /*Pegar filmes por determinada categoria*/
    public function getMoviesByUserId($id); /*Pegar filmes do user específico*/
    public function findById($id);     /*Encontrar um filme por id*/
    public function findByTitle($title); /*Encontrar um filme por Título específico*/
    public function create(Movie $movie);
    public function update(Movie $movie);
    public function destroy($id);
}
