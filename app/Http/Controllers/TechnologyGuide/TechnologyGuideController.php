<?php

namespace App\Http\Controllers\TechnologyGuide;

use App\Http\Controllers\Controller;
use App\Models\HrFile;
use App\Models\HrFolder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnologyGuideController extends Controller
{
    // List all folders and files
    public function index(Request $request)
    {
        $folders = HrFolder::with('files')->ofCategories(HrFolder::types['TG'])->whereNull('parent_id')->get();
        return successResponse(Response::HTTP_OK, "Here you go", $folders);
    }

    // Get details of a folder, including its files and children folders
    public function show(HrFolder $folder)
    {
        return successResponse(Response::HTTP_OK, "Here you go", $folder);
    }

    // Get details of a file
    public function showFile(HrFile $file)
    {
        return successResponse(Response::HTTP_OK, "Here you go", $file);
    }

    // Search for folders and files
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $folders = HrFolder::where('name', 'like', "%{$keyword}%")->ofCategories(HrFolder::types['TG'])->get();
        $files = HrFile::ofCategories(HrFolder::types['TG'])
            ->where(function($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('file_name', 'like', "%{$keyword}%");
            })->get();

        return successResponse(Response::HTTP_OK, "Here you go", [
            'folders' => $folders,
            'files' => $files,
        ]);
    }

}
