require('../common/common');
require('../../sass/common/mainTemplate.scss');

$("#sidebar-menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("active");
});
