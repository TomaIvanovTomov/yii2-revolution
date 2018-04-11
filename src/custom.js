$(window).on('load', function () {

    var removeButtons = $('.close.fileinput-remove');

    //Remove image wrapper
    removeButtons.on('click', function () {

        if(confirm("Delete this record permanently?")){

            var parent = $(this).parent().parent().parent().parent().parent();
            var modelID = parent.siblings('input[name^="Slide[model_id]"]').attr('value');

            removeButtons = $('.close.fileinput-remove');

            var imageWrappers = $('.slide-image-counter');

            var index = removeButtons.index( $(this) );

            imageWrappers[index].remove();

            $.ajax({
                method: "POST",
                url: location.href.split('/slider')[0]+"/slider/slide/delete-image",
                data: {
                    id: modelID
                },
                success: function ( data ) {
                    location.reload();
                }
            })

        }

    })

});

/*
 function reorderImages() {
 var pattern = $("input[name^='Slide[image]']");
 var fileInputs = pattern.filter("input[type='file']");
 var hiddenInputs = pattern.filter("input[type='hidden']");
 var isNewInputs = $("input[name^='Slide[is_new]']");
 var modelIdInputs = $("input[name^='Slide[model_id]']");
 //Throw the initial inputs
 Array.prototype.shift.apply(fileInputs);
 Array.prototype.shift.apply(hiddenInputs);

 for(var i=0; i<fileInputs.length; i++){
 fileInputs[i].setAttribute('name', 'Slide[image]['+i+']');
 hiddenInputs[i].setAttribute('name', 'Slide[image]['+i+']');
 isNewInputs[i].setAttribute('name', 'Slide[is_new]['+i+']');
 modelIdInputs[i].setAttribute('name', 'Slide[model_id]['+i+']');
 }
 }*/
