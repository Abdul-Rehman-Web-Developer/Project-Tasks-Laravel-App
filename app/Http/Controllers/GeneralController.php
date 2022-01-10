<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use DB;

class GeneralController extends Controller
{
    public function index(){
        
    	$projects_html = $this->renderAllProjects();
    	return view('home',[
    		'projects_html' => $projects_html
    	]);
    }
    public function addProject(Request $request){
    	
    	$messages = [
            'title.unique' => "A project with the following :attribute has already been added.",
      
        ];
		$validator=Validator::make($request->all(),[
            'title' => 'required|min:3|max:200|unique:projects,title'
        ],$messages);

        if ($validator->fails()) {
        	$error_html="<span style='color:red'>".$validator->errors()->first()."</span>";
            return response()->json(["success"=> false, 'validation_error'=>$error_html ], 200);
        }

        $project = new Project;
        $project->title = $request->title;  
		$project->save();
        $projects_html = $this->renderAllProjects();
        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Project successfully added.</span>", 'projects_html' => $projects_html], 200);

    }

    public function addTask(Request $request){

		$validator=Validator::make($request->all(),[
            'title' => 'required|min:3|max:400',
            'priority' => 'required|in:High,Medium,Low'
        ]);
        
        if ($validator->fails()) {
        	$error_html="<span style='color:red'>".$validator->errors()->first()."</span>";
            return response()->json(["success"=> false, 'validation_error'=>$error_html ], 200);
        }

        if(!Project::find($request->project_id)){
            return response()->json(["success"=> false, 'validation_error'=>"<span style='color:red'>An invalid Project ID submitted.</span>" ], 200);
        }

        if(Project::find($request->project_id)->tasks()->where('tasks.title', $request->title)->exists()){
            return response()->json(["success"=> false, 'validation_error'=>"<span style='color:red'>A Task with a given title has already been added to the chosen Project.</span>" ], 200);
		}

        if(Project::find($request->project_id)->tasks()->count() == 0){
            $position =1;
        }else{
            $max_position = Project::find($request->project_id)->tasks()->max('position');
            $position = $max_position + 1;
        }

        $task = new Task;
        $task->title = $request->title;  
        $task->project_id = $request->project_id;  
        $task->priority = $request->priority;
        $task->position = $position;  
		$task->save();
        $projects_html = $this->renderAllProjects();

        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Task successfully added.</span>", 'projects_html' => $projects_html], 200);

    }

    public function deleteProject(Request $request){

        if(!$project=Project::find($request->id)){
            return response()->json(["success"=> false, 'validation_error'=>"<span style='color:red'>An invalid Project ID submitted.</span>" ], 200);
        }

		$project->delete();
		
        $projects_html = $this->renderAllProjects();

        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Project successfully deleted.</span>", 'projects_html' => $projects_html], 200);
  
    }


    public function deleteTask(Request $request){

        if(!$task=Task::find($request->id)){
            return response()->json(["success"=> false, 'validation_error'=>"<span style='color:red'>An invalid Task ID submitted.</span>" ], 200);
        }

		$task->delete();
		
        $projects_html = $this->renderAllProjects();

        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Task successfully deleted.</span>", 'projects_html' => $projects_html], 200);
  
    }

    public function reorderProjectTasks(Request $request){
        DB::beginTransaction();
        try{
            foreach ($request->tasks as $task) {
                Task::where([
                                ['id', $task['task_id'] ], 
                                ['project_id', $task['project_id'] ]
                            ])
                    ->update(['position' => $task['position']]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
                 
        $projects_html = $this->renderAllProjects();

        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Project Tasks successfully reordered.</span>", 'projects_html' => $projects_html], 200);
  
    }

    public function reloadPageContent(){
        $projects_html = $this->renderAllProjects();
        return response()->json(["success"=> true,  'message'=>"<span style='color: #1ed35e'>Page content successfully reloaded.</span>", 'projects_html' => $projects_html], 200);
    }
  
    private function renderAllProjects(){
     	$delete_project_route =route('delete-project');
     	$delete_task_route =route('delete-task');

    	$projects = Project::all();
        $html="<ul class='list-group  list-group-flush mb-5'>";
    	foreach ($projects as $project) {
    		$html.="
    			<li class='list-group-item project'>
    				<h5 class='project-heading'>
    				<span style='font-weight:bold; font-size:30px'>".$project->title."</span>
    				<button class='btn btn-danger btn-sm delete-project-btn d-none' data-id='".$project->id."' data-url='".$delete_project_route."'>Delete</button>
    				<button class='btn btn-success btn-sm float-right add-project-btn d-none' data-project-id='".$project->id."' data-toggle='modal' data-target='#add-task-modal'> + Add Task </button>
    				</h5>
    				<ul class='list-group tasks'>
    			";    		
                        
    		$tasks = Project::find($project->id)->tasks()->orderBy('position')->get();
    		foreach ($tasks as $task) {
                switch ($task->priority) {
                    case 'High':
                        $priority_color = "#d43920";
                        break;
                    case 'Medium':
                        $priority_color = "#f48f03";
                        break;
                    case 'Low':
                        $priority_color = "#19a552";
                        break;
                    
                }
    			$html.="
    					<li class='list-group-item task' data-task-id='".$task->id."' data-project-id='".$project->id."'>
    						<div class='clearfix' style='font-size:20px'>
                                ".$task->title."
    						    <button class='btn btn-danger btn-sm delete-task-btn d-none' data-id='".$task->id."' data-url='".$delete_task_route."'>Delete</button>
                            </div>
                            <div class='clearfix text-right'>                              
                              <span title='Priority' style='display: inline-block;padding: 3px;font-size: 12px;border-radius: 5px; background-color:".$priority_color."; color:white'>
                                ".$task->priority."
                              </span>
                            </div>
                            <div class='clearfix text-right text-muted'>
                               <small> ".\Carbon\Carbon::parse($task->created_at)->format('F d, Y - g:i A')." </small>
                            </div>
    					</li>";
    		}

    		$html.="</ul> </li>";
    	}
    	$html.="</ul>";

    	return $html;

    }
}
