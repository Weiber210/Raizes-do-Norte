<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/jwt.php";

require_once __DIR__ . "/../app/Repositories/AuthRepository.php";
require_once __DIR__ . "/../app/Services/AuthService.php";
require_once __DIR__ . "/../app/Controllers/AuthController.php";

require_once __DIR__ . "/../app/Repositories/PedidoRepository.php";
require_once __DIR__ . "/../app/Services/PedidoService.php";
require_once __DIR__ . "/../app/Controllers/PedidoController.php";

require_once __DIR__ . "/../app/Middleware/JwtMiddleware.php";

function responderJson(array $dados, int $status = 200): void
{
    http_response_code($status);

    echo json_encode(
        $dados,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );

    exit;
}

function responderErro(
    string $erro,
    string $mensagem,
    int $status,
    string $rota
): void {
    responderJson([
        "error" => $erro,
        "message" => $mensagem,
        "details" => [],
        "timestamp" => date(DATE_ATOM),
        "path" => "/api" . $rota
    ], $status);
}

function lerJson(): array
{
    $conteudo = file_get_contents("php://input");

    if ($conteudo === false || trim($conteudo) === "") {
        return [];
    }

    $dados = json_decode($conteudo, true);

    if (!is_array($dados)) {
        throw new InvalidArgumentException("JSON inválido.");
    }

    return $dados;
}

$rota = "/" . trim((string) ($_GET["rota"] ?? ""), "/");
$metodo = $_SERVER["REQUEST_METHOD"];

$authRepository = new AuthRepository($pdo);
$jwt = new Jwt();
$authService = new AuthService($authRepository, $jwt);
$authController = new AuthController($authService);

$pedidoRepository = new PedidoRepository($pdo);
$pedidoService = new PedidoService($pedidoRepository);
$pedidoController = new PedidoController($pedidoService);

$middleware = new JwtMiddleware($jwt);

try {
if ($rota === "/auth/login") {
    if ($metodo !== "POST") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    responderJson([
        "data" => $authController->login(lerJson())
    ]);
}

$usuario = $middleware->autenticar();

if ($rota === "/produtos") {
    if ($metodo !== "GET") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    responderJson([
        "data" => $pedidoController->listarCardapio()
    ]);
}

if ($rota === "/pedidos") {
    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente",
        "Cozinha"
    ]);

    if ($metodo === "GET") {
        responderJson([
            "data" => $pedidoController->listar($_GET)
        ]);
    }

    if ($metodo === "POST") {
        $middleware->autorizar($usuario, [
            "Administrador",
            "Gerente",
            "Atendente"
        ]);

        $pedidoId = $pedidoController->cadastrar(
            lerJson(),
            (int) $usuario["sub"]
        );

        responderJson([
            "message" => "Pedido cadastrado com sucesso.",
            "data" => [
                "pedidoId" => $pedidoId,
                "status" => "AGUARDANDO_PAGAMENTO"
            ]
        ], 201);
    }

    responderErro(
        "METHOD_NOT_ALLOWED",
        "Método não permitido.",
        405,
        $rota
    );
}

if (
    preg_match(
        "#^/pedidos/([0-9]+)$#",
        $rota,
        $resultado
    )
) {
    $pedidoId = (int) $resultado[1];

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente",
        "Cozinha"
    ]);

    if ($metodo === "GET") {
        responderJson([
            "data" =>
                $pedidoController->formularioEdicao($pedidoId)
        ]);
    }

    if ($metodo === "DELETE") {
        $middleware->autorizar($usuario, [
            "Administrador",
            "Gerente",
            "Atendente"
        ]);

        $status = $pedidoController->cancelar(
            $pedidoId,
            (int) $usuario["sub"]
        );

        responderJson([
            "message" => "Pedido cancelado com sucesso.",
            "data" => [
                "pedidoId" => $pedidoId,
                "status" => $status
            ]
        ]);
    }

    responderErro(
        "METHOD_NOT_ALLOWED",
        "Método não permitido.",
        405,
        $rota
    );
}

if (
    preg_match(
        "#^/pedidos/([0-9]+)/pagamentos$#",
        $rota,
        $resultado
    )
) {
    if ($metodo !== "POST") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente"
    ]);

    $dados = lerJson();
    $dados["pedido_id"] = (int) $resultado[1];

    $status = $pedidoController->processarPagamento(
        $dados,
        (int) $usuario["sub"]
    );

    $statusHttp =
        strtoupper((string) ($dados["resultado"] ?? ""))
        === "RECUSADO"
        ? 402
        : 200;

    responderJson([
        "message" => $statusHttp === 402
            ? "Pagamento recusado."
            : "Pagamento aprovado.",
        "data" => [
            "pedidoId" => (int) $resultado[1],
            "status" => $status
        ]
    ], $statusHttp);
}

if (
    preg_match(
        "#^/pedidos/([0-9]+)/status$#",
        $rota,
        $resultado
    )
) {
    if ($metodo !== "PATCH") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $dados = lerJson();
    $dados["pedido_id"] = (int) $resultado[1];

    $status = $pedidoController->atualizarStatus(
        $dados,
        (int) $usuario["sub"],
        (string) $usuario["perfil"]
    );

    responderJson([
        "message" => "Status atualizado com sucesso.",
        "data" => [
            "pedidoId" => (int) $resultado[1],
            "status" => $status
        ]
    ]);
}

if ($rota === "/estoque") {
    if ($metodo !== "GET") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente",
        "Cozinha"
    ]);

    responderJson([
        "data" => $pedidoController->listarEstoque($_GET)
    ]);
}

if (
    preg_match(
        "#^/fidelidade/([0-9]+)$#",
        $rota,
        $resultado
    )
) {
    if ($metodo !== "GET") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente"
    ]);

    responderJson([
        "data" => $pedidoController->consultarFidelidade(
            (int) $resultado[1]
        )
    ]);
}

if ($rota === "/pagamentos") {
    if ($metodo !== "GET") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente",
        "Atendente"
    ]);

    responderJson([
        "data" => $pedidoController->listarPagamentos()
    ]);
}

if ($rota === "/auditoria") {
    if ($metodo !== "GET") {
        responderErro(
            "METHOD_NOT_ALLOWED",
            "Método não permitido.",
            405,
            $rota
        );
    }

    $middleware->autorizar($usuario, [
        "Administrador",
        "Gerente"
    ]);

    responderJson([
        "data" => $pedidoController->listarAuditoria()
    ]);
}

responderErro(
    "NOT_FOUND",
    "Rota não encontrada.",
    404,
    $rota
);
} catch (DomainException $erro) {
responderErro(
    "FORBIDDEN",
    $erro->getMessage(),
    403,
    $rota
);
} catch (InvalidArgumentException $erro) {
responderErro(
    "VALIDATION_ERROR",
    $erro->getMessage(),
    422,
    $rota
);
} catch (RuntimeException $erro) {
$mensagem = $erro->getMessage();

if (
    str_contains($mensagem, "Token") ||
    str_contains($mensagem, "senha")
) {
    $status = 401;
    $codigo = "UNAUTHORIZED";
} elseif (str_contains($mensagem, "não encontrado")) {
    $status = 404;
    $codigo = "NOT_FOUND";
} else {
    $status = 409;
    $codigo = "CONFLICT";
}

responderErro(
    $codigo,
    $mensagem,
    $status,
    $rota
);
} catch (PDOException $erro) {
error_log($erro->getMessage());

responderErro(
    "DATABASE_ERROR",
    "Erro ao acessar os dados.",
    500,
    $rota
);
} catch (Throwable $erro) {
error_log($erro->getMessage());

responderErro(
    "INTERNAL_ERROR",
    "Erro interno do servidor.",
    500,
    $rota
);
}