<?php

if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return match ($status) {
            'completed' => 'success', // ✅ Vert
            'pending' => 'warning',   // 🟡 Orange
            'failed' => 'danger',     // ❌ Rouge
            default => 'secondary',   // Gris
        };
    }
}
