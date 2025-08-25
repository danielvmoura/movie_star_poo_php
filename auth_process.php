<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

//Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

//Verificação do tipo de Formulário
if ($type === "register") {

    $email = filter_input(INPUT_POST, "email");
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    $message = new Message($BASE_URL);

    $userDAO = new UserDAO($conn, $BASE_URL);

    //Verifica��o de dados m�nimos
    if ($name && $lastname && $email && $password) {

        //Verificar se as senhas batem
        if ($password === $confirmpassword) {

            //Verificar se o e-mail, já está cadastrado no sistema
            if ($userDAO->findByEmail($email) === false) {

                //Cadastrando o usuário no sistema depois de já ter passado pelas validações
                $user = new User();

                //Criação de token e senha
                $userToken = $user->generateToken();
                $finalPassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = true;

                $userDAO->create($user, $auth);
            } else {
                //Enviar uma msg de erro. email existente
                $message->setMessage("E-mail já cadastrado", "error", "back");
            }
        } else {
            //Enviar uma msg de erro. senhas não batem
            $message->setMessage("As senhas não são iguais", "error", "back");
        }
    } else {
        //Enviar uma msg de erro. de dados faltantes
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
} else if ($type === "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
}
