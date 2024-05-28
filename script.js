$(document).ready(function() {
    var $carrousel = $('#carrousel'); // on cible le bloc du carrousel
    var $img = $('#carrousel img'); // on cible les images contenues dans le carrousel
    var indexImg = $img.length - 1; // on définit l'index du dernier élément
    var i = 0; // on initialise un compteur
    var $currentImg = $img.eq(i); // enfin, on cible l'image courante, qui possède l'index i (0 pour l'instant)
    $img.css('display', 'none'); // on cache les images
    $currentImg.css('display', 'block'); // on affiche seulement l'image courante
    $carrousel.append('<div class="controls"><button class="prev">Précédent</button><button class="next">Suivant</button></div>');

    $('.next').click(function() { // image suivante
        i++; // on incrémente le compteur
        if (i > indexImg) {
            i = 0; // si on dépasse le dernier index, on revient au premier
        }
        $img.css('display', 'none'); // on cache les images
        $currentImg = $img.eq(i); // on définit la nouvelle image
        $currentImg.css('display', 'block'); // puis on l'affiche
    });

    $('.prev').click(function() { // image précédente
        i--; // on décrémente le compteur
        if (i < 0) {
            i = indexImg; // si on dépasse le premier index, on revient au dernier
        }
        $img.css('display', 'none');
        $currentImg = $img.eq(i);
        $currentImg.css('display', 'block');
    });

    function slideImg() {
        setTimeout(function() { // on utilise une fonction anonyme
            if (i < indexImg) { // si le compteur est inférieur au dernier index
                i++; // on l'incrémente
            } else { // sinon, on le remet à 0 (première image)
                i = 0;
            }
            $img.css('display', 'none');
            $currentImg = $img.eq(i);
            $currentImg.css('display', 'block');
            slideImg(); // on oublie pas de relancer la fonction à la fin
        }, 5000); // on définit l'intervalle à 5000 millisecondes (5s)
    }

    slideImg(); // enfin, on lance la fonction une première fois
});
