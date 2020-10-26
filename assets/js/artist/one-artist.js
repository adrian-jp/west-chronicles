import router from 'router';


let id = getIdCreateur(window.location.href);
console.log(id)
$.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: router.routing.generate('one_artist_id', {'id': id}, true),
    success: function (artist) {
        console.log(artist['nom']);
        $('#show-artist').html('');
        let clips = artist['clips'];


        for (let i = 0, len = artist['clips'].length; i < len; i++) {
            console.log(clips[i])
            const videoId = getId(clips[i]['url']);
            let clipBody =
                '<h5 class="card-title">' + clips[i]["titre"] + '</h5>\n' +
                '<p>by ' + artist["nom"] + '</p>' +
                '    <iframe class="ml-xl-5" width="420" height="315" src="//www.youtube.com/embed/' + videoId + '" frameborder="0" allowfullscreen></iframe>\n' +
                '<hr>';
            $('#show-artist').append(clipBody);
        }


    },
    error: function () {
        console.log('fail')
    }
})

//Rend le lien youtube lisible
function getId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
        ? match[2]
        : null;
}

//Récupère l'élément après le dernier / de l'url
function getIdCreateur(urlCreateur) {
    return urlCreateur.match(/\/([^\/]+)\/?$/)[1];
}

//Vérifie si le track est dans l'album
function containsObject(obj, list) {
    var i;
    for (i = 0; i < list.length; i++) {
        if (list[i] === obj) {
            return true;
        }
    }

    return false;
}