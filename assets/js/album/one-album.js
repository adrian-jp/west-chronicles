import router from 'router';

import ('../tracks/media-player')


let id = getIdAlbum(window.location.href);
let url = router.routing.generate('one_album_json', {'id': id}, true);
console.log(url)
$.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: url,
    success: function (album) {
        console.log(album.tracks)
        $('#audio').html('');
        let tracks = album.tracks;
        let albumList = [];
        for (let i = 0; i < tracks.length; i++) {
            console.log(tracks[i]['ordre_album'])
            let sourceFile =
                '<source src="tracks/' + tracks[i]['url'] + '" data-track-number="' + tracks[i]['ordre_album'] + '" />';
            albumList.push(sourceFile);

        }
        $('#audio').append(albumList);
    }
})


//Récupère l'élément après le dernier / de l'url
function getIdAlbum(url) {
    return url.match(/\/([^\/]+)\/?$/)[1];
}