<?php
require_once './imports/config.php';
require_once './imports/functions.php';

$json_file = __DIR__ . '/dados/json/dados.json';
$data = read_json($json_file);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($columns as $col => $_) {
        $columns[$col] = isset($_POST['col_' . $col]);
    }
    foreach ($censor_options as $field => $_) {
        $censor_options[$field] = isset($_POST['censor_' . $field]);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Listagem de Inscrições</title>
    <link href="imports/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">

    <div class="container">
        <h1 class="mb-4">Listagem de Inscrições</h1>

        <form method="post" class="mb-4">
            <div class="row g-3">
                <?php foreach ($columns as $col => $enabled): ?>
                    <div class="col-auto form-check">
                        <input class="form-check-input" type="checkbox" name="col_<?= $col ?>" id="col_<?= $col ?>"
                            <?= $enabled ? "checked" : "" ?>>
                        <label class="form-check-label"
                            for="col_<?= $col ?>"><?= ucfirst(str_replace("_", " ", $col)) ?></label>
                    </div>
                <?php endforeach; ?>

                <?php foreach ($censor_options as $field => $enabled): ?>
                    <div class="col-auto form-check">
                        <input class="form-check-input" type="checkbox" name="censor_<?= $field ?>"
                            id="censor_<?= $field ?>" <?= $enabled ? "checked" : "" ?>>
                        <label class="form-check-label" for="censor_<?= $field ?>">Censurar <?= ucfirst($field) ?></label>
                    </div>
                <?php endforeach; ?>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Aplicar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered bg-white">
                <thead class="table-dark">
                    <tr>
                        <?php foreach ($columns as $col => $enabled): ?>
                            <?php if ($enabled): ?>
                                <th><?= ucfirst(str_replace("_", " ", $col)) ?></th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row):
                        $row = apply_censorship($row, $censor_options);
                        ?>
                        <tr>
                            <?php foreach ($columns as $col => $enabled): ?>
                                <?php if ($enabled): ?>
                                    <td><?= htmlspecialchars($row[$col] ?? "") ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>