import router from 'router';

$(function () {
    console.log('we are here')
    let fileupload = $("#FileUpload1");
    let filePath = $("#spnFilePath");
    let image = $("#imgFileUpload");
    image.click(function () {
        fileupload.click();
    });
    fileupload.change(function () {
        let fileName = $(this).val().split('\\')[$(this).val().split('\\').length - 1];
        filePath.html("<b>Selected File: </b>" + fileName);
    });

    let deletePhoto = function () {
        let artistId = $('btn-delete-photo').data('id');
        console.log(artistId);
        $.ajax({
            url: router.routing.generate('delete_photo', {'id': artistId}, true),
            success: function () {
                console.log('success');
            },
            error: function (data) {
                console.log(data);
            }
        })
    }
})