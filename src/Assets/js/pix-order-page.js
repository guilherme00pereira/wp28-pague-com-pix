(function ($) {
    $('button.copyButton').click(function(){
        const linkToCopy = $(this).siblings('input.linkToCopy');
        linkToCopy.select();
        document.execCommand("copy");
        $(this).children('.pix-tooltiptext').html('Chave copiada').css('background-color', '#3cb371');
    });
}(jQuery));