<?php

class Review
{

    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;
    public $user;
}

interface ReviewDAOInterface
{

    public function buildReview($data);
    public function create(Review $review);
    public function getMoviesReview($id);  /* Para saber notas e comentários de filmes pelo seu id */
    public function hasAlreadyReviewd($id, $userId);   /* Para saber se o user já fez o review do filme */
    public function getRatings($id); /* Receber todas a notas de um filme */
}
