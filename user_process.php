<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);

//Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

//Atualizar User
if ($type === "update") {

    //Resgata dados do User
    $userData = $userDao->verifyToken();

    //Receber dados do Post
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    //Criar novo ibjeto de User
    $user = new User();

    //Preencher os dados do user
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    //Upload da imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        //Chegagem de tipo de imagem
        if (in_array($image["type"], $imageTypes)) {

            //Chegar se é JPG
            if (in_array($image, $jpgArray)) {

                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else { //Imagem é png 

                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            //Independente do que venha, aqui vamos criar uma img jpeg e manda-lá para o caminho desejado
            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->image = $imageName;
        } else {

            $message->setMessage("Tipo inválido de imagem! Arquivos suportados: jpeg, jpg e png", "error", "back");
        }
    }

    $userDao->update($userData);
} else if ($type === "changepassword") { //Atualizar Senha do User

    //Receber dados do Post
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //Resgata dados do User
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if ($password === $confirmpassword) {

        //Criar um novo objeto de user
        $user = new User();

        $finalPassword = $user->generatePassword($password);

        $user->password = $finalPassword;
        $user->id = $id;

        $userDao->changePassword($user);
    } else {
        $message->setMessage("As senhas não são iguais!", "error", "back");
    }
} else {

    $message->setMessage("Informações inválidas!", "error", "index.php");
}
