require('./plugins/jquery.collection');

//
// Gestion des inputs de type file
//


// Preview de l'image uploadée
previewImage = function (input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            console.log(e);
            $('#photo-user').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
};

const filesInputs = $(".custom-file-input");

filesInputs.on("change", function () {
    setNameIntoInput(this);
});

setNameIntoInput = function (input) {
    const fileName = $(input).val().split("\\").pop();
    $(input).siblings(".custom-file-label").addClass("selected").html(fileName);
};

$('.form-group').hide($('.col-form-label'));
