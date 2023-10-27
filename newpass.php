<?php
require('./assets/config/connect.php');

if (isset($_GET["email"])) {
    $email = urldecode($_GET["email"]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["token"]) && isset($_POST["new_password"])) {
            $token = $_POST["token"];
            $newPassword = $_POST["new_password"];

            try {
                $stmt = $conn->prepare("SELECT email FROM cadastros WHERE reset_token = :token AND expire > NOW()");
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $emailFromDB = $result['email'];

                    $stmt = $conn->prepare("UPDATE cadastros SET senha = :senha, reset_token = NULL, expire = NULL WHERE email = :email");
                    $stmt->bindParam(':senha', $newPassword);
                    $stmt->bindParam(':email', $emailFromDB);
                    $stmt->execute();

                    header('Location: login.php');
                    exit();
                } else {
                    echo "Token inválido ou expirado.";
                }
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
                header('Location: register.php');
                exit();
            }
        }
    }
} else {
    header('Location: recuperacao.php?msg=Email não fornecido!');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pag.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Nova Senha</title>
</head>

<body>
    <div class="container col d-flex justify-content-center" style="opacity: 92%">
        <div class="card card-custom my-4 border-dark" style="width: 65%;height: fit-content; display: inline-flex;">
            <div class="card-body">
                <h5 class="card-title">
                    <h3>Nova Senha</h3>
                    <form method="POST">
                        <!-- FORM PARA DEFINIÇÃO DE NOVA SENHA -->
                        <div class="mb-3">
                            <label for="token" class="form-label">Token</label>
                            <input type="text" name="token" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nova Senha</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-secondary">Definir Nova Senha</button>
                    </form>
                </h5>
            </div>
        </div>
    </div>
</body>

</html>
