$(document).ready(function(){
	
	// add project form
	$('#add-project-form').parsley().on('form:submit', function (formInstance) {
			var form=$('#add-project-form')
		    var url=form.attr('action');
            var formData=form.serialize(); 

            $.ajax({
                    url: url,
                    type: "post",                            
                    data: formData,                            
                    beforeSend:function(){
                      form.find('input').blur()
                      // show processing spinner
                      form.LoadingOverlay("show")
                    },
                    success: function(data) {
                        
                        if(data['success'] == false){
                        	swal({
		                        title: "Caution",
		                        text: data['validation_error'],
		                        type: "error",
		                      	html:true,
		                  	}) 
                        }else{
                        	form.trigger("reset");
	                    	$('#add-project-modal').modal('hide')
	                    	swal({
			                        title: "Success",
			                        text: data['message'],
			                        type: "success",
			                      	html:true,
			                })


			                $('.projects-container').html(data['projects_html']) 

                        } 
                                                               		
                  
                        // hide processing spinner
                        form.LoadingOverlay("hide",true)                       
                        
                    },
                    error: function(){
                    	swal({
	                        title: "Caution",
	                        text: "Failed sending data to server, please try again.",
	                        type: "error",
	                      	html:true,
		                }) 
                      
                      // hide processing spinner
                        form.LoadingOverlay("hide",true)
                    }                   
            })   

	    // prevent form submit
		return false;
	});

	// add task form
	$('#add-task-form').parsley().on('form:submit', function (formInstance) {
			var form=$('#add-task-form')
		    var url=form.attr('action');
            var formData=form.serialize(); 

            $.ajax({
                    url: url,
                    type: "post",                            
                    data: formData,                            
                    beforeSend:function(){
                      form.find('input').blur()
                      // show processing spinner
                      form.LoadingOverlay("show")
                    },
                    success: function(data) {
                        
                        if(data['success'] == false){
                        	swal({
		                        title: "Caution",
		                        text: data['validation_error'],
		                        type: "error",
		                      	html:true,
		                  	}) 
                        }else{
                        	form.trigger("reset");
	                    	$('#add-task-modal').modal('hide')
	                    	swal({
			                        title: "Success",
			                        text: data['message'],
			                        type: "success",
			                      	html:true,
			                })

			                $('.projects-container').html(data['projects_html']) 
			                reorderProjectTasks();
                        } 
                                                       		
                        // hide processing spinner
                        form.LoadingOverlay("hide",true)                       
                        
                    },
                    error: function(){
                    	swal({
	                        title: "Caution",
	                        text: "Failed sending data to server, please try again.",
	                        type: "error",
	                      	html:true,
		                }) 
                      
                      // hide processing spinner
                        form.LoadingOverlay("hide",true)
                    }                   
            })   

	    // prevent form submit
		return false;
	})

	$('#add-project-modal').on('hidden.bs.modal', function (e) {
	  $('#add-project-form').trigger("reset")
	  $('#add-project-form').parsley().reset()
	})

	$('#add-task-modal').on('hidden.bs.modal', function (e) {
	  $('#add-task-form').trigger("reset")
	  $('#add-task-form').parsley().reset()
	})

	$(document).on('mouseenter','.project',function(){
		$('.delete-project-btn').addClass('d-none')
		$(this).find('h5').find('.delete-project-btn').removeClass('d-none')

		$('.add-project-btn').addClass('d-none')
		$(this).find('h5').find('.add-project-btn').removeClass('d-none')

		var project_title = $(this).find('h5').find('span').text()
		var project_id = $(this).find('h5').find('.add-project-btn').attr('data-project-id')
        $('#project-title-value').val(project_title)
        $('#add-task-form input[name=project_id]').val(project_id)
	})

	$(document).on('mouseleave','.project',function(){
		$('.add-project-btn').addClass('d-none')
		$('.delete-project-btn').addClass('d-none')
	})

	$(document).on('mouseleave','.task',function(){
		$('.delete-task-btn').addClass('d-none')
	})
	$(document).on('mouseenter','.task',function(){

		$('.delete-task-btn').addClass('d-none')
		$(this).find('button').removeClass('d-none')

	})

	$(document).on('click','.delete-task-btn',function(){
		var id = $(this).attr('data-id')
		var url = $(this).attr('data-url')
		var csrf_token =$('meta[name="csrf-token"]').attr('content')

        swal({
		  title:"Are you sure?",
		  text: "Once deleted this item can not be recovered.",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes, delete!",
		  cancelButtonText: "No, cancel please!",
		},
		function(isConfirm){
		  if (isConfirm) {
		            // delete task 
												
		            $.ajax({
		                    url: url,
		                    type: "post",                            
		                    data: { id : id, _token :csrf_token},                            
		                    beforeSend:function(){
		                      // show processing spinner
		                      $('body').LoadingOverlay("show")
		                    },
		                    success: function(data) {
		                        
		                        if(data['success'] == false){
		                        	swal({
				                        title: "Caution",
				                        text: data['validation_error'],
				                        type: "error",
				                      	html:true,
				                  	}) 
		                        }else{
			                    	swal({
				                        title: "Success",
				                        text: data['message'],
				                        type: "success",
				                      	html:true,
					                })

					                $('.projects-container').html(data['projects_html']) 
		                        	reorderProjectTasks();
		                        } 
		                                                       		
		                        // hide processing spinner
		                        $('body').LoadingOverlay("hide",true)                       
		                        
		                    },
		                    error: function(){
		                    	swal({
			                        title: "Caution",
			                        text: "Failed sending data to server, please try again.",
			                        type: "error",
			                      	html:true,
				                }) 
		                      
		                      // hide processing spinner
		                        $('body').LoadingOverlay("hide",true)
		                    }                   
		            })   
		  } 
		})
	})

	$(document).on('click','.delete-project-btn',function(){
		var id = $(this).attr('data-id')
		var url = $(this).attr('data-url')
		var csrf_token =$('meta[name="csrf-token"]').attr('content')

        swal({
		  title:"Are you sure?",
		  text: "Once deleted this item can not be recovered.",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Yes, delete!",
		  cancelButtonText: "No, cancel please!",
		},
		function(isConfirm){
		  if (isConfirm) {
		            // delete project 
												
		            $.ajax({
		                    url: url,
		                    type: "post",                            
		                    data: { id : id, _token :csrf_token},                            
		                    beforeSend:function(){
		                      // show processing spinner
		                      $('body').LoadingOverlay("show")
		                    },
		                    success: function(data) {
		                        
		                        if(data['success'] == false){
		                        	swal({
				                        title: "Caution",
				                        text: data['validation_error'],
				                        type: "error",
				                      	html:true,
				                  	}) 
		                        }else{
			                    	swal({
				                        title: "Success",
				                        text: data['message'],
				                        type: "success",
				                      	html:true,
					                })

					                $('.projects-container').html(data['projects_html']) 
		                        } 
		                                                       		
		                        // hide processing spinner
		                        $('body').LoadingOverlay("hide",true)                       
		                        
		                    },
		                    error: function(){
		                    	swal({
			                        title: "Caution",
			                        text: "Failed sending data to server, please try again.",
			                        type: "error",
			                      	html:true,
				                }) 
		                      
		                      // hide processing spinner
		                        $('body').LoadingOverlay("hide",true)
		                    }                   
		            })   
		  } 
		})
	})

	
	reorderProjectTasks();
	
})

function reloadPageContent(){
    let csrf_token =$('meta[name="csrf-token"]').attr('content')
	let url =$('meta[name="reload-page-content"]').attr('content')

	// show processing spinner
	$('body').LoadingOverlay("show")
                        
	$.post( url, {_token :csrf_token})
				.done(function( data ) {
					$('.projects-container').html(data['projects_html']);
					reorderProjectTasks(); 
					// hide processing spinner
					$('body').LoadingOverlay("hide",true); 
				});
}

function reorderProjectTasks(){
	// reorder project tasks
	$(".tasks").sortable({
		stop: function(event, ui) {
			let tasks =[];
			$(this).find('.task').each(function(i, el){
            	tasks.push(
            			{
            				project_id: $(el).attr('data-project-id'),
            				task_id: $(el).attr('data-task-id'),
            				position:i+1
            			}
            		);
            });
			
	        let csrf_token =$('meta[name="csrf-token"]').attr('content')
	        let url =$('meta[name="reorder-project-tasks"]').attr('content')
	        
	        // show processing spinner
			$('body').LoadingOverlay("show")
                        
			$.post( url, { tasks : tasks, _token :csrf_token})
				.done(function( data ) {
					$('.projects-container').html(data['projects_html']);
					reorderProjectTasks(); 
					// hide processing spinner
					$('body').LoadingOverlay("hide",true); 
				});
			
	    	
	    }
	});
}