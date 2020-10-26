import router from "router";

console.log('message');


const url = router.routing.generate('track_list', {}, true);
console.log(url);

$.ajax({
    type: 'POST',
    dataType: "JSON",
    url: url,
    success: function (data) {
        console.log(data);
        //let tracks = document.write(data);

        $('#track-player').html('');
        $('#audio').html('');

        for (let i = 0, len = data.length; i < len; i++) {
            console.log(data[i]['url']);
            let cardBody =
                '<h3 class="card-title"><b>' + data[i]["titre"] + '</b></h3>\n' +
                '<p>by ' + data[i]['artists'][0]['nom'] + '</p>' +
                '    <audio controls src="album/one/tracks/' + data[i]['url'] + '"></audio>\n';
            $('#track-player').append(cardBody);
        }

    }
});
