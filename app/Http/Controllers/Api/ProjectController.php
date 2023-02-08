<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function createProject (Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required'
        ]);

        $studentId = auth()->user()->id;

        $project = new Project();
        $project->student_id = $studentId;
        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration = $request->duration;
        $project->save();

        return response()->json([
            "status" => 1,
            "message" => "project has been created",
            "data" => $project
        ]);
    }

    public function listProject () {
        $studentId = auth()->user()->id;
        $projects = Project::where("student_id", $studentId)->get();
        return response()->json([
            "status" => 1,
            "message" => "projects",
            "data" => $projects
        ]);
    }

    public function singleProject ($id) {
        $studentId = auth()->user()->id;
        $project = Project::where(['id' => $id, 'student_id' => $studentId])->first();
        if($project) {
            return response()->json([
                "status" => 1,
                "message" => "projects",
                "data" => $project
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "project not found",
            ]);
        }
    }

    public function deleteProject ($id) {
        $studentId = auth()->user()->id;
        $project = Project::where(['id' => $id, 'student_id' => $studentId])->first();
        if($project) {
            $project->delete();
            return response()->json([
                "status" => 1,
                "message" => "project deleted successfully",
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "project not found",
            ]);
        }
    }
}