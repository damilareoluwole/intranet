<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FolderRequest;
use App\Http\Requests\RequestInformationRequest;
use App\Mail\InfoMail;
use App\Models\Employee;
use App\Models\File;
use App\Models\Folder;
use App\Models\ResearchInformationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ResearchController extends Controller
{
    // List all folders and files
    public function index(Request $request)
    {
        $folders = Folder::with('files')->whereNull('parent_id')->get();
        return successResponse(Response::HTTP_OK, "Here you go", $folders);
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
        $files = File::where('name', 'like', "%{$keyword}%")->get();

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

        $link = config('constants.frontend_url')."/research?ref={$newinfo->uuid}";
        
        Mail::to(config('constants.group_emails.red'))->send(new InfoMail(
            "RED", 
            "Please, note that {$requester->displayName} has requested information for research purposes.
            <p><p>Click <a href='".$link."'>here</a> for more details</p></p>"
        ));

        return successResponse(Response::HTTP_CREATED, "Your request has been submitted successfully");
    }

    // All equest informations
    public function requestInformationIndex(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:employees,guid'
        ]);

        if (! $user = Employee::where('guid', $request->user_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown user");
        }

        $informations = $user->informationRequests;
        return successResponse(Response::HTTP_OK, "Here you go", $informations);
    }

    // A particular Request information
    public function requestInformationShow(Request $request, $research_id)
    {
        $request->validate([
            'user_id' => 'required|exists:employees,guid'
        ]);

        if(! $information = ResearchInformationRequest::where('uuid', $research_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Research not found");
        }

        if (! $user = Employee::where('guid', $request->user_id)->first()) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "Unknown user");
        }

        if($information->requester_id != $user->id) {
            return errorResponse(Response::HTTP_BAD_REQUEST, "You are not allowed to view this request");
        }
        
        return successResponse(Response::HTTP_OK, "Here you go", $information);
    }
}
