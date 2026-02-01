class JQueryHelper {
    static InitializeNavbar() {
        $(".menu").click(function () {
            $(".navegacion").slideToggle(300);
        });

        // Detectar cuando se cambia el tamaño de la ventana
        $(window).resize(function () {
            // Si la pantalla es mayor a 1000px, mostrar el menú normalmente
            if ($(window).width() > 1000) {
                $(".navegacion").show();
            } else {
                $(".navegacion").hide();
            }
        });
    }
    static InitializeSliders() {
        let slides = $(".slide");
        let index = 0;

        setInterval(function(){
            slides.eq(index).fadeOut(1500);
            index = (index + 1) % slides.length;
            slides.eq(index).fadeIn(1500);
        }, 5000);
    }
}