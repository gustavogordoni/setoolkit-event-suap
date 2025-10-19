<?php

function read_json($path)
{
    if (!file_exists($path))
        return [];
    return json_decode(file_get_contents($path), true);
}


function censor_email($email)
{
    $parts = explode("@", $email);
    if (strlen($parts[0]) <= 2)
        return str_repeat("*", strlen($parts[0])) . "@" . $parts[1];
    $first = $parts[0][0];
    $last = $parts[0][strlen($parts[0]) - 1];
    $masked = $first . str_repeat("*", strlen($parts[0]) - 2) . $last;
    return $masked . "@" . $parts[1];
}


function censor_cpf($cpf)
{
    $cpf = trim($cpf);
    return substr($cpf, 0, 3) . ".***.***-" . substr($cpf, -2);
}


function apply_censorship($row, $censor_options)
{
    foreach ($censor_options as $field => $enabled) {
        if ($enabled && isset($row[$field])) {
            if ($field === "email")
                $row[$field] = censor_email($row[$field]);
            if ($field === "cpf")
                $row[$field] = censor_cpf($row[$field]);
        }
    }
    return $row;
}
