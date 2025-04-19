<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaUploadRequest;
use App\Helpers\FileUploadHelper;

class FileUploadController extends Controller {
    public function upload(MediaUploadRequest $request) {

        try {
            $chunkIndex = $request->input('chunkIndex');
            $totalChunks = $request->input('totalChunks');
            $uploadId = $request->input('uploadId');

            // Upload the file
            if ($totalChunks) {
                $fileDetails = FileUploadHelper::chunkUploadFile($request->file('file'), $chunkIndex, $totalChunks, $uploadId);
                return response()->json($fileDetails);
            }

            $fileDetails = FileUploadHelper::uploadFile($request->file('file'));

            // Respond with success and file details
            return response()->json(['message' => 'File uploaded successfully.', 'file_details' => $fileDetails,]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['message' => 'Validation error.', 'errors' => $e->errors(),], 422);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['message' => 'Error uploading file: ' . $e->getMessage(),], 500);
        }
    }

}
