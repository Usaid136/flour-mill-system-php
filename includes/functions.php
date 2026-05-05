<?php


// Base URL
define("BASE_URL", "http://localhost/flour_mill_system/");

// ========================
// Redirect Helper Function
// ========================
function redirect(string $url): never
{
    header("Location: $url");
    exit;
}



// ==================
// Set Flash Message
// ==================
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}


// ==================
// Get Flash Message
// ==================
function getFlash(string $type): ?string
{
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash']);
        return $msg;
    }
    return null;
}


// ===========================
// Clean Input -> remove space
// ===========================
function c(string $value): string
{
    return trim($value);
}




// ==============
// Escape Output
// ==============
function e(string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}



// =================
// Input Validation
// =================
function required(string $value): bool
{
    return trim($value) !== '';
}

// Email Validation
function validEmail(string $value): bool
{
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

// Number Minimum
function minLength(string $value, int $min): bool
{
    return strlen(trim($value)) >= $min;
}

// Number Maximum
function maxLength(string $value, int $max): bool
{
    return strlen(trim($value)) <= $max;
}
