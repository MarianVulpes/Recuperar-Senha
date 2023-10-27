<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pag.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Troca de senha.</title>
</head>

<body>
    <div class="container col d-flex justify-content-center" style="opacity: 92%">
        <div class="card card-custom my-4 border-dark" style="width: 65%;height: fit-content; display: inline-flex;">
            <div class="card-body">
                <h5 class="card-title">
                    <h3>Recuperar Senha</h3>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                      <!-- FORM PARA RECUPERAÇÃO DE SENHA -->
                      <div class="mb-3">
                          <label for="email_rec" class="form-label">Endereço de e-mail</label>
                          <input type="email" name="email_rec" class="form-control" required>
                      </div>
                      <button type="submit" name="recuperation" class="btn btn-secondary">Recuperar Senha</button>
                  </form>
                </h5>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recuperation"])) {
        require('./assets/config/connect.php');

        $email = $_POST["email_rec"];
        $token = bin2hex(random_bytes(16));
        $expire = date("Y-m-d H:i:s", time() + 60 * 30);

        try {
            $stmt = $conn->prepare("UPDATE cadastros SET reset_token = :token, expire = :expire WHERE email = :email");
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":expire", $expire);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header("Location: .\assets\actions\password_reset.php?email=" . urlencode($email));
                exit;
            } else {
                echo "Nenhum usuário encontrado com este e-mail.";
            }

        } catch (PDOException $e) {
            echo "Erro ao atualizar o token: " . $e->getMessage();
        }
    }
    ?>
</body>

</html>
