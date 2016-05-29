<?php

  /*
  * Author Jeferson Daniel
  * Version 1.0.0
  * CRUD PDO
  */

  class Employe {

    // Deleta usuários
    public function delete( $id ) {
      include '../database.php';
      echo "<script>confirm('Tem certeza que deseja apagar esse registro?')</script>";
      $db = new Database;
      $pdo = $db->connect();
      $sql = $pdo->prepare("DELETE FROM funcionario WHERE id=:id");
      $sql->bindValue(':id', $id);
      $sql->execute();

      $message = 'funcionario excluido com sucesso!';
      header("Location: ../../funcionario.php?message={$message}");
    }

    public function count() {
      $db = new Database;
      $pdo = $db->connect();
      $busca = $pdo->prepare("SELECT count(*) as total FROM funcionario");
      $busca->execute();
      $result = $busca->fetchColumn();
      echo $result;
    }

    // Lista todos usuários cadatrados
    public function readAll() {
      $db = new Database;
      $pdo = $db->connect();
      $busca = $pdo->prepare("SELECT * FROM funcionario");
      $busca->execute();

      $linha = $busca->fetchAll(PDO::FETCH_ASSOC);

      return $linha;
    }

    // Cadastro no banco
    public function insert() {
      include '../database.php';
      $db = new Database;
      $pdo = $db->connect();

      $nome =      $_POST['nome'];
      $email =     $_POST['email'];
      $senha =     $_POST['senha'];

      $validar = $pdo->prepare("SELECT * FROM funcionario WHERE email=:email");
      $validar->bindValue(':email', $email);
      $validar->execute();

      if( $validar->rowCount() == 0 ):
        $sql = $pdo->prepare("INSERT INTO funcionario(nome, email, senha)VALUES(:nome, :email, :senha)");

        $sql->bindValue(':nome',      $nome);
        $sql->bindValue(':email',     $email);
        $sql->bindValue(':senha',     $senha);

        $sql->execute();
        $message = 'Cadastro realizado com sucesso!';
        header("Location: ../../funcionario.php?message={$message}");
      else:
        $message = 'Já existe um funcionario com este id!';
        header("Location: ../../funcionario.php?message={$message}");
      endif;
    }

    // Recuperar usuário pelo ID
    public function getById( $id ) {
      $db = new Database;
      $pdo = $db->connect();
      $busca = $pdo->prepare("SELECT * FROM funcionario WHERE ID = :id");
      $busca->bindValue(':id', $id);
      $busca->execute();

      return $busca->fetchAll(PDO::FETCH_ASSOC);
    }


    // Atualizar usuário
    public function update() {
      include '../database.php';
      $db = new Database;
      $pdo = $db->connect();

      $id =        $_POST['id'];
      $nome =      $_POST['nome'];
      $email =     $_POST['email'];
      $senha =     md5($_POST['senha']);

      if( !empty( $nome ) ) {
        $sql = $pdo->prepare("UPDATE funcionario SET nome=:nome WHERE id=:id");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':id', $id);
        $sql->execute();
      }

      if( !empty( $email ) ) {
        $sql = $pdo->prepare("UPDATE funcionario SET email=:email WHERE id=:id");
        $sql->bindValue(':email', $email);
        $sql->bindValue(':id', $id);
        $sql->execute();
      }

      if( !empty( $senha ) ) {
        $sql = $pdo->prepare("UPDATE funcionario SET senha=:senha WHERE id=:id");
        $sql->bindValue(':senha', $senha);
        $sql->bindValue(':id', $id);
        $sql->execute();
      }

      $message = 'Usuário atualizado com sucesso!';
      header("Location: ../../funcionario.php?message={$message}");
    }

    public function wrapperList( $array ) {
      foreach ($array as $value) {
        
        $line = '<tr>';
        $line .=  '<td>' . $value['id'] . '</td>';
        $line .=  '<td>' . $value['nome'] . '</td>';
        $line .=  '<td>' . $value['email'] . '</td>';
        $line .=  '<td>';
        $line .=      '<a class="icon-table" href="./partials/employe/editar.php?id='. $value['id'] .'">';
        $line .=          '<i class="glyphicon glyphicon-pencil"></i>';
        $line .=      '</a>';
        $line .=  '</td>';
        $line .= '<td>';
        $line .= '  <a class="icon-table" href="./functions/employe/delete.php?id='. $value['id'] .'">';
        $line .= '     <i class="glyphicon glyphicon-trash"></i>';
        $line .= '  </a>';
        $line .= '</td> ';
        $line .= '</tr>';

        echo $line;
      }
    }

    // Resgata o usuário que está logado
    public function getLoggedUser() {
      $db = new Database;
      $pdo = $db->connect();

      $email = $_SESSION['email'];
      $senha = $_SESSION['senha'];

      $sql = $pdo->prepare("SELECT * FROM funcionario WHERE email=:email and senha=:senha");
      $sql->bindValue(':email', $email);
      $sql->bindValue(':senha', $senha);
      $sql->execute();

      $currentUser = $sql->fetchAll(PDO::FETCH_ASSOC);
      return $currentUser;
    }

    // Mostra o nome do usuário logado
    public function loggedUserName() {
      $user = $this->getLoggedUser();
      echo $user[0]['nome'];
    }

  }

  $employe = new Employe;

?>