<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required'
        ]);
        $pathUpload = $request->file('file')->store('public');
        return response()->json(['path' => explode('/', (string)$pathUpload)[1]]);
    }
}
