<?php

namespace App\Http\Controllers\TechnologyGuide;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Models\Employee;
use App\Models\HrFile;
use App\Models\HrFolder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTechnologyGuideController extends Controller
{
    // List all folders and files
    public function index(Request $request)
    {
        $folders = HrFolder::with('files')->whereNull('parent_id')->ofCategories(HrFolder::types['TG'])->get();
        return successResponse(Response::HTTP_OK, "Here you go", $folders);
    }

    // Create a new folder
    public function store(Request $request, HrFolder $folder = null)
    {
        $request->validate([
            'name'          => 'required|string',
            'modifier_id'   => 'required|exists:employees,guid',
            'parent_id'     => 'nullable|exists:folders,id'
        ]);

        if (! $modifier = Employee::where('guid', $request->modifier_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown modifier");
        }

        $folder = HrFolder::create([
            'name'          => $request->name,
            'category'      => HrFolder::types['TG'],
            'modifier_id'   => $modifier->id,
            'parent_id'     => $folder ? $folder->id : null
        ]);

        return successResponse(Response::HTTP_CREATED, "Research folder created successfully", $folder);
    }

    // Add a file to a folder
    public function addFile(FileRequest $request, HrFolder $folder)
    {
        if (! $modifier = Employee::where('guid', $request->modifier_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown modifier");
        }

        $uploadedFile = $request->file('doc');
        $originalName = $uploadedFile->getClientOriginalName(); // Get the original file name
        $extension = $uploadedFile->getClientOriginalExtension(); // Get the file extension
        $size = $uploadedFile->getSize();

        $file = HrFile::create([
            'name'          => $request->name ?? null,
            'category'      => HrFolder::types['TG'],
            'file_name'     => $originalName,
            'path'          => $uploadedFile->storePubliclyAs('TechnologyGuide', time(). str_replace(" ","-",$originalName), 'public'),
            'modifier_id'   => $modifier->id,
            'folder_id'     => $folder->id,
            'type'          => $extension,
            'size'          => $size
        ]);

        return successResponse(Response::HTTP_CREATED, "Research file created successfully", $file);
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

    // Delete folder
    public function deleteFolder(Request $request)
    {
        $request->validate([
            'admin_id'     => 'required|exists:employees,guid',
            'folders_id'   => 'required|array',
            'folders_id.*' => 'exists:hr_folders,uuid'
        ]);

        foreach($request->folders_id as $folder_id) {
            $folder = HrFolder::where('uuid', $folder_id)->sole();

            // Delete all files
            $folder->files()->delete();

            // Delete folder
            $folder->delete();
        }

        return successResponse(Response::HTTP_OK, "Folder deleted successfully");
    }

    // Delete folder
    public function deleteFile(Request $request)
    {
        $request->validate([
            'admin_id'     => 'required|exists:employees,guid',
            'files_id'   => 'required|array',
            'files_id.*' => 'exists:hr_files,uuid'
        ]);

        foreach($request->files_id as $file_id) {
            $file = HrFile::where('uuid', $file_id)->sole();

            // Delete filder
            $file->delete();
        }

        return successResponse(Response::HTTP_OK, "File deleted successfully");
    }
}
