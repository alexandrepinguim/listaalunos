<?php

$conn = new mysqli('localhost', 'root', '', 'gestao_alunos');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $id);  
    $stmt->execute();
    $resultado = $stmt->get_result();
    $aluno = $resultado->fetch_assoc();

    if (!$aluno) {
        echo "Aluno não encontrado!";
        exit();
    }

    $stmt->close();
} else {

    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];

    if (empty($nome) || empty($email) || empty($contacto)) {
        echo "Todos os campos são obrigatórios.";
    } else {

        $stmt = $conn->prepare("UPDATE alunos SET nome = ?, email = ?, contacto = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $email, $contacto, $id);  

        if ($stmt->execute()) {

            header('Location: index.php');
            exit();
        } else {
            echo "Erro ao atualizar o aluno: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Aluno</h1>

        <div style="padding: 20px;"></div>

        <form method="post" action="editar.php?id=<?php echo $id; ?>">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>

            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>

            <label for="contacto" class="form-label">Contacto:</label>
            <input type="text" name="contacto" class="form-control" id="contacto" value="<?php echo htmlspecialchars($aluno['contacto']); ?>" required>

            <div style="padding: 10px;"></div>

            <input type="submit" value="Atualizar" class="add-btn">
        </form>

        <a class="back-link" href="index.php">Voltar à lista de alunos</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php

$conn->close();
?>