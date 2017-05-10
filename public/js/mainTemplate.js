$(document).ready(function () {
   const s = $("#wrapper");

    $("#sidebar-menu-toggle").click(function (e) {
        e.preventDefault();
        s.toggleClass("active");
    });

    $('#small-nav-toggle').click(function (e) {
        e.preventDefault();
        s.toggleClass("active");
    });

});


