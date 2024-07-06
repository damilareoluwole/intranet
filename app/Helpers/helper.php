<?php

use Symfony\Component\HttpFoundation\Response;

if (!function_exists('successResponse')) { 
    function successResponse($code = Response::HTTP_OK, $message = "Successful", $data = [])
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], $code);
    }
}

if (!function_exists('errorResponse')) { 
    function errorResponse($code = Response::HTTP_UNPROCESSABLE_ENTITY, $message = "Failed", $data = [])
    {
        return response()->json([
            "success" => false,
            "message" => $message,
            "data" => $data
        ], $code);
    }
}

if (!function_exists('formatFileSize')) { 
    function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}