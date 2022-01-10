<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Project / Tasks (Laravel App)</title>
        <!-- some necessary routes -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="reorder-project-tasks" content="{{ route('reorder-project-tasks') }}" />
        <meta name="reload-page-content" content="{{ route('reload-page-content') }}" />

         <!-- Bootstrap core -->
        <link href="{{ URL::to('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- sweetalert plugin  -->
        <link href="{{ URL::to('public/vendor/sweetalert/sweetalert.css') }}" rel="stylesheet"> 
        <!-- Custom styles -->
        <link href="{{ URL::to('public/assets/css/main.css') }}" rel="stylesheet">
          
    </head>
    <body >
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h2>Project / Tasks <small class="text-muted">(Laravel App)</small></h2>
                    <hr>
                </div>
                <div class="col-md-8 offset-md-2">
                     <button class="btn btn-success btn-md mb-2" data-toggle="modal" data-target="#add-project-modal"> + Add Project</button>
                </div>
                <div class="col-md-8 offset-md-2 projects-container">
                    {!! $projects_html !!}
                   
                </div>
            </div>
        </div>

        <!-- Add Project Modal -->
        <div class="modal fade" id="add-project-modal" tabindex="-1" role="dialog"  aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" > + Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id='add-project-form' method="POST" action="{{ route('add-project') }}" data-parsley-validate>
                    <div class="form-group">
                        <label class="form-label">Project Title</label>
                        <input class='form-control' type="text" name='title' placeholder="Enter title" required data-parsley-minlength="3" data-parsley-maxlength="200">
                        @csrf
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Project" class="btn btn-primary">
                    </div>                    
                </form>
              </div>              
            </div>
          </div>
        </div> <!-- END Add Project Modal -->

        <!-- Add Task Modal -->
        <div class="modal fade" id="add-task-modal" tabindex="-1" role="dialog"  aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" > + Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id='add-task-form' method="POST" action="{{ route('add-task') }}" data-parsley-validate>
                    <div class="form-group">
                        <label class="form-label">Project</label>
                        <input class='form-control' type="text" id='project-title-value' value="" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Title</label>
                        <input class='form-control' type="text" name='title' placeholder="Enter title" required data-parsley-minlength="3" data-parsley-maxlength="400">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Priority</label>
                        <select class="form-control" name='priority' required>
                            <option value="">Select Priority</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        @csrf
                        <input type="hidden" name="project_id" value="">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add Task" class="btn btn-primary">
                    </div>                    
                </form>
              </div>              
            </div>
          </div>
        </div> <!-- END Add Task Modal -->

        <!-- JQuery core -->
        <script src="{{ URL::to('public/vendor/jquery/jquery.min.js') }}"></script>
        <!-- JQuery UI -->
        <script src="{{ URL::to('public/assets/js/jquery-ui.js') }}"></script>
        <!-- Bootstrap core -->
        <script src="{{ URL::to('public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Loading Overlay plugin -->
        <script src="{{ URL::to('public/vendor/jquery.loadingoverlay/loadingoverlay.min.js') }}"></script>
        <script src="{{ URL::to('public/vendor/jquery.loadingoverlay/loadingoverlay_progress.min.js') }}"></script>
        <!-- sweetalert plugin -->
        <script src="{{ URL::to('public/vendor/sweetalert/sweetalert.min.js') }}"></script>
        <!-- Parsely form validation plugin -->
        <script src="{{ URL::to('public/assets/js/parsley.min.js') }}"></script>
        <!-- Custom scripts -->
        <script src="{{ URL::to('public/assets/js/main.js') }}"></script>

    </body>
</html>
