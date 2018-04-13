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

    translate();

});

function translate() {

    $('.slider-title')
        .animate({
            left: "10%",
        }, 600);

    $('.slider-description')
        .animate({
            right: "10%",
        }, 600);
}