<?php

function getDatabaseConnection() {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.db');
    $pdo->exec("PRAGMA foreign_keys = ON;");
    return $pdo;
}
