import router from 'router'

console.log('hello people');

const url = router.routing.generate('clip_list', {}, true);
console.log(url);

$.ajax({
    type: 'POST',
    dataType: 'JSON',
    url: url,
    success: function (clips) {
        $('#clip-list').html('');
        console.log(clips);
        for (let i = 0, len = clips.length; i<len; i++) {
            const videoId = getId(clips[i]['url']);
            let clipBody =
                '<h5 class="card-title">'+clips[i]["titre"]+'</h5>\n' +
                '<p>by '+ clips[i]['artists'][0]['nom']+'</p>' +
                '    <iframe class="ml-5" width="420" height="315" src="//www.youtube.com/embed/'+videoId+'" frameborder="0" allowfullscreen></iframe>\n' +
                '<hr>';
            $('#clip-list').append(clipBody);
        }
    }
});


function getId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
        ? match[2]
        : null;
}
