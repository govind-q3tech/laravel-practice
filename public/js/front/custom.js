

/*search bar*/

  $(document).ready(function(){
  $(".search-btn-mobile").click(function(){
    $(".topsrch-flt").slideToggle("search-field-open"); 
  });
});

//Header
$(document).on('scroll', function() {
   if ($(document).scrollTop() > 50) {
      $('.header').addClass('header-shrink');
   } else {
      $('.header').removeClass('header-shrink');
   }
});



 $(document).on('click', '.confirmDeleteBtn', function() {
    var _this = $(this);
    var _id = _this.data("id");
    var url = _this.data("url");
    var message = _this.data("message");
    var title = _this.data("title");
    var action = _this.data("action");
    
    if(message == 'undefined'){
        message = 'Are you sure want to delete this record?';
    }else{
        message = message;
    }
    if(action == 'undefined'){
        actionB = 'DELETE';
    }else{
        actionB = action;
    }
    
    $.confirm({
        title: 'Alert',
        content: message,
        icon: 'fa fa-exclamation-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        theme: 'supervan',
        buttons: {
            'confirm': {
                text: 'Yes',
                btnClass: 'btn-blue',
                action: function() {
                    $.ajax({
                        url: url,
                        type: actionB,
                        dataType: 'json',
                        headers: {
                            "accept": "application/json",
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(record) {
                            if (record.status == true) {
                                $.alert({
                                    columnClass: 'medium',
                                    title: 'Success',
                                    icon: 'fa fa-check',
                                    type: 'green',
                                    content: record.message,
                                    buttons: {
                                          Ok: function(){
                                              //location.reload();
                                              if (_this.hasClass("noreload")) {
                                                $(".table-row-" + record.data.id).remove();
                                            } else {
                                                location.reload();
                                            }
                                          }
                                      }
                                    });
                                if (_this.hasClass("reload")) {
                                    location.reload();
                                } else {
                                    $(".row-" + record.data.id).remove();
                                }
                            }else{
                                $.alert({
                                    columnClass: 'medium',
                                    title: 'Error',
                                    icon:  'fa fa-warning',
                                    type:  'red',
                                    content: record.message,
                                    });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
            },
            cancelAction: {
                text: 'Cancel',
            }
        }
    });

});


