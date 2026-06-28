<?php
class AuthRepository{

    public function __construct(private PDO $pdo)
    {}

    public function buscarPorEmail(string $email): array|false
    {
        $sql = "
            SELECT id, nome, email, senha, perfil
            FROM usuarios
            WHERE LOWER(email) = LOWER(:email)
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}