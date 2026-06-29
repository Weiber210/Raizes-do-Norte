<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Raízes do Nordeste</title>

    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.31.0/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.31.0/swagger-ui-bundle.js"></script>

    <script>
    SwaggerUIBundle({
    url: "/Raizes-do-Norte/docs/openapi.yaml",
    dom_id: "#swagger-ui",
    persistAuthorization: true
    });
    </script>
</body>
</html>