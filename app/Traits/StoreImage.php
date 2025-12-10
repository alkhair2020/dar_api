<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
Trait  StoreImage
{
     function saveImage($photo,$folder){
    
        $file_extension = $photo -> getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $path = $folder ;
        $photo -> move($path,$file_name);
        return $file_name ;
    }


    public function uploadImages(Request $request, $fieldName, $directory)
    {
        $uploadedFiles = [];
        if ($request->hasFile($fieldName)) {
            $files = $request->file($fieldName);
            foreach ($files as $file) {
                $fileName =  time().'.'.$file->getClientOriginalName();
                $filePath = $file->storeAs($directory, $fileName, 'public');
                $uploadedFiles[] = $fileName ;
            }
        }
        // dd($uploadedFiles);
        return $uploadedFiles;
    }
}



