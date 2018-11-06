$(document).ready(function(){
    var score = $('.score').html() / 100;
    $('.rating').show();
    $('#circle').circleProgress({
        value: 1,
        size: 60,
        emptyFill: '#204529',
        fill: '#21d07a',
        startAngle: -Math.PI / 4 * 3,
        lineCap: 'round',
        thickness: 6
    });
    setTimeout(function() { $('#circle').circleProgress('value', score); }, 1000);
});
