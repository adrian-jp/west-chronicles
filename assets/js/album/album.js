import router from 'router';
console.log($('.album-track-delete').data())
$(function () {
    $('.album-tracks-collection').collection({
        name_prefix: 'track-collection',
        allow_duplicate: false,
        allow_up: false,
        allow_down: false,
        add: '<a href="#" class="collection-add btn btn-primary btn-sm float-left mt-2" ' +
            'title="Ajouter un track"><span class="fas fa-plus-circle mr-2"></span>Ajouter un track</a>',
        remove: '<a href="#" class="collection-remove btn btn-danger btn-sm float-right mt-2" ' +
            'title="Supprimer un track"><span class="fas fa-trash-alt"></span></a>'+
        '<hr>',
        add_at_the_end: true
    });

    // function deleteTrack() {
    //     let albumId = $('.album-track-delete').data('id');
    //         console.log(albumId);
    //         $.ajax({
    //             url: router.routing.generate('delete_track', {'id': albumId, 'track_id': track.id})
    //         })
    // }
})

//Récupère l'élément après le dernier / de l'url
function getIdAlbum(url) {
    return url.match(/\/([^\/]+)\/?$/)[1];
}