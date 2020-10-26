import '../../styles/artist-list.css';

console.log('enter the wc')

$(function () {
    console.log('ok');

    // Action retouner cartes
    $(document).on('click', '.flip-card-inner', function () {

        $(this).toggleClass('flipped');
    });

// Arrete le retournement de la carte au click sur "plus de détail"
    $(document).on('click', '.list-album', function () {
        $('.flip-card-inner').removeClass('flipped');
    });

})


