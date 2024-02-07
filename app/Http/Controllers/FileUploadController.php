<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadImageRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function uploadImage(FileUploadImageRequest $request)
    {
        try {
            $result = $request->file('file')->storeOnCloudinary();
            return response()->json(['secure_path' => $result->getSecurePath()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro upload image'], 400);
        }
    }
}
