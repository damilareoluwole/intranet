<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FolderRequest;
use App\Http\Requests\RequestInformationRequest;
use App\Http\Requests\UpdateRequestInformationRequest;
use App\Mail\InfoMail;
use App\Models\Employee;
use App\Models\File;
use App\Models\Folder;
use App\Models\ResearchInformationRequest;
use App\Models\User;
use App\Notifications\InfoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    // List all folders and files
    public function index(Request $request)
    {
        $folders = Folder::with('files')->whereNull('parent_id')->get();
        return successResponse(Response::HTTP_OK, "Here you go", $folders);
    }

    // Create a new folder
    public function store(FolderRequest $request, Folder $folder = null)
    {
        if (! $modifier = Employee::where('guid', $request->modifier_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown modifier");
        }

        $folder = Folder::create([
            'name'          => $request->name,
            'modifier_id'   => $modifier->id,
            'parent_id'     => $folder ? $folder->id : null
        ]);

        return successResponse(Response::HTTP_CREATED, "Research folder created successfully", $folder);
    }

    // Add a file to a folder
    public function addFile(FileRequest $request, Folder $folder)
    {
        if (! $modifier = Employee::where('guid', $request->modifier_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown modifier");
        }

        $uploadedFile = $request->file('doc');
        $originalName = $uploadedFile->getClientOriginalName(); // Get the original file name
        $extension = $uploadedFile->getClientOriginalExtension(); // Get the file extension
        $size = $uploadedFile->getSize();

        $file = File::create([
            'name'          => $request->name ?? null,
            'file_name'     => $originalName,
            'path'          => $uploadedFile->storePubliclyAs('Files', time(). str_replace(" ","-",$originalName), 'public'),
            'modifier_id'   => $modifier->id,
            'folder_id'     => $folder->id,
            'type'          => $extension,
            'size'          => $size
        ]);

        return successResponse(Response::HTTP_CREATED, "Research file created successfully", $file);
    }

    // Get details of a folder, including its files and children folders
    public function show(Folder $folder)
    {
        return successResponse(Response::HTTP_OK, "Here you go", $folder);
    }

    // Get details of a file
    public function showFile(File $file)
    {
        return successResponse(Response::HTTP_OK, "Here you go", $file);
    }

    // Search for folders and files
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $folders = Folder::where('name', 'like', "%{$keyword}%")->get();
        $files = File::where('name', 'like', "%{$keyword}%")->orWhere('file_name', 'like', "%{$keyword}%")->get();

        return successResponse(Response::HTTP_OK, "Here you go", [
            'folders' => $folders,
            'files' => $files,
        ]);
    }

    // Request information for research purpose
    public function requestInformation(RequestInformationRequest $request)
    {
        if (! $requester = Employee::where('guid', $request->requester_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown user with the requester");
        }

        $information = $request->validated();
        $information['requester_id'] = $requester->id;
        
        $newinfo = ResearchInformationRequest::create($information);

        $requester = User::find($request->requester_id);

        $link = config('constants.frontend_url')."/research?ref={$newinfo->uuid}";
        
        Mail::to(config('constants.group_emails.red'))->send(new InfoMail(
            "RED", 
            "Please, note that {$requester->name} has requested information for research purposes.
            <p><p>Click <a href='".$link."'>here</a> for more details</p></p>"
        ));

        return successResponse(Response::HTTP_CREATED, "Your request has been submitted successfully");
    }

    // All equest informations
    public function requestInformationIndex(Request $request)
    {
        $informations = ResearchInformationRequest::all();
        return successResponse(Response::HTTP_OK, "Here you go", $informations);
    }

    // A particular Request information
    public function requestInformationShow(Request $request, $research_id)
    {
        if(! $information = ResearchInformationRequest::where('uuid', $research_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Research not found");
        }
        
        return successResponse(Response::HTTP_OK, "Here you go", $information);
    }

    // Update Request information
    public function requestInformationUpdate(UpdateRequestInformationRequest $request, $research_id)
    {
        if (! $admin = Employee::where('guid', $request->admin_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown modifier");
        }

        if(! $information = ResearchInformationRequest::where('uuid', $research_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Research not found");
        }

        if($information->status != 'Pending') {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Action has already been taken by an admin");
        }

        $data = $request->validated(); 
        $data['admin_id'] = $admin->id;
        $data['admin_at'] = now();

        $information->update($data);

        //Notify the employee
        if ($requester = Employee::where('guid', $information->requester_id)->first()) {
            $requester->notify(new InfoNotification("The research information you requested has been {$request->status}.", "Research Info Request - {$request->status}"));
        }

        return successResponse(Response::HTTP_OK, "Record updated successfully", $information->refresh());
    }

    // Delete folder
    public function deleteFolder(Request $request, $research_id)
    {
        $request->validate([
            'admin_id'     => 'required|exists:employees,guid',
            'folder_ids'   => 'required|array',
            'folder_ids.*' => 'exists:folders,uuid'
        ]);

        foreach($request->folder_ids as $folder_id) {
            $folder = Folder::where('uuid', $folder_id)->sole();

            // Delete all files
            $folder->files()->delete();

            // Delete folder
            $folder->delete();
        }

        return successResponse(Response::HTTP_OK, "Folder deleted successfully");
    }

    // Delete folder
    public function deleteFile(UpdateRequestInformationRequest $request, $research_id)
    {
        $request->validate([
            'admin_id'     => 'required|exists:employees,guid',
            'file_ids'   => 'required|array',
            'file_ids.*' => 'exists:files,uuid'
        ]);

        foreach($request->file_ids as $file_id) {
            $file = File::where('uuid', $file_id)->sole();

            // Delete filder
            $file->delete();
        }

        return successResponse(Response::HTTP_OK, "File deleted successfully");
    }
}
