(function ( $ ) {
 
    $.fn.jPreview = function() {
        var jPreview = this;

        jPreview.boot = function(input){
            var selector = input;
            var container = $(selector).data('jpreview-container');

            $(selector).change(function(){
                $(container).empty();
                $.each(input.files, function(index, file){
                    var imageData = jPreview.readImageData(file, function(data){
                        jPreview.addPreviewImage(container, data);
                    });
                });
            });
        }

        jPreview.readImageData = function(file, successCallback){
            var reader = new FileReader();
            reader.onload = function(event){
                successCallback(event.target.result);
            }
            reader.readAsDataURL(file);
        }
        
        jPreview.addPreviewImage = function(container, imageDataUrl){
            $('.admin_prod_img').remove();
            $(container).append('<div class="jpreview-image" style="background-image: url('+ imageDataUrl +')"></div>');
        }

        var inputs = $(this);
        return $.each(inputs, function(index, input){
            jPreview.boot(input);
        });
 
    };
 
}( jQuery ));