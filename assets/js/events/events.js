$(function () {

    // collection d'artistes'
    $('.event-artist-collection').collection({
        name_prefix: 'artist-collection',
        allow_duplicate: false,
        allow_up: false,
        allow_down: false,
        add: '<a href="#" class="collection-add btn btn-primary btn-sm float-left mt-2" ' +
            'title="Ajouter un album"><span class="fas fa-plus-circle mr-2"></span>Ajouter un artiste</a>',
        remove: '<a href="#" class="collection-remove btn btn-danger btn-sm float-right mt-2" ' +
            'title="Supprimer un album"><span class="fas fa-trash-alt"></span></a>',
        add_at_the_end: true
    });


})