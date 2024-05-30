<?php

$env = parse_ini_file('.env');

$GLOBALS['config'] = [

    'DB_HOST' => $env['DB_HOST'] ?? '127.0.0.1',

    'DB_PORT' => $env['DB_PORT'] ?? '5432',

    'DB_NAME' => $env['DB_NAME'] ?? 'postgres',

    'DB_USER' => $env['DB_USER'] ?? 'postgres',

    'DB_PASSWORD' => $env['DB_PASSWORD'] ?? 'postgres',

];

function config(string $key)
{
    return $GLOBALS['config'][$key] ?? null;
}

function arg(string $key, int $position)
{
    global $argc;

    if ($argc < $position) {
        echo "Exception: Argument is not given, '$key' argument should be come $position position.";
        exit(1);
    }

    global $argv;

    return $argv[$position] ?? null;
}

function info(string $message): void
{
    echo "ℹ️ \033[32m$message\033[0m\n";
}

function done(string $message): void
{
    echo "✅ \033[32m$message\033[0m\n";
}

function error(string $message): void
{
    echo "🚫 \033[31m$message\033[0m\n";
}

function warning(string $message): void
{
    echo "⚠️ \033[93m$message\033[0m\n";
}
