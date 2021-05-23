function hamburger() {
    var ham = ['#menu_contents', '#menu_icon', '#line1', '#line2', '#line3'];
    for (var i = 0; i < ham.length; i++) {
        $(ham[i]).toggleClass('active');
    }
}
$('#menu_icon').click(hamburger);