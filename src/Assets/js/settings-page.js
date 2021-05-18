(function($) {
    'use strict';
    $('#wp28_pix_accounts').on('click', 'a.add', function() {
        const size = $('#wp28_pix_accounts').find('tbody .account').length;

        $('<tr class="account">\
                        <td class="sort"></td>\
                        <td><input type="text" name="pix_bank[' + size + ']" /></td>\
                        <td>\
                            <select name="pix_key_type[' + size + ']" class="pix_key_type" style="width: 100%;">\
                                    <option value="cpf">CPF</option>\
                                    <option value="cnpj">CNPJ</option>\
                                    <option value="email">E-mail</option>\
                                    <option value="telefone">Telefone</option>\
                                    <option value="evp">Aleatória</option>\
                                </select>\
                        </td>\
                        <td><input type="text" name="pix_key_code[' + size + ']" /></td>\
                        <td><input type="text" name="pix_holder_name[' + size + ']" /></td>\
                        <td><input type="text" name="pix_holder_city[' + size + ']" /></td>\
                    </tr>').appendTo('#wp28_pix_accounts table tbody');

        return false;
    });

    $('#wp28_contact_channels').on('click', 'a.add', function() {

        const size = $('#wp28_contact_channels').find('tbody .channel').length;

        $('<tr class="channel">\
                        <td class="sort"></td>\
                        <td>\
                            <select name="pix_contact_name[' + size + ']" class="pix_contact_name" style="width: 100%;">\
                                <option value="whatsapp">Whatsapp</option>\
                                <option value="telegram">Telegram</option>\
                                <option value="email">E-mail</option>\
                                <option value="outro">Outro</option>\
                            </select>\
                        </td>\
                        <td><input type="text" name="pix_contact_value[' + size + ']" /></td>\
                    </tr>').appendTo('#wp28_contact_channels table tbody');

        return false;
    });

    $('.accounts').on('change', '.pix_key_type', function (){
        apply_accounts_masks($(this));
    });

    $('.channels').on('change', '.pix_contact_name', function () {
        apply_channels_masks($(this));
    })

    $(document).ready(function () {
        $('.pix_key_type').each(function (){
            apply_accounts_masks($(this));
        });

        $('.pix_contact_name').each(function (){
            apply_channels_masks($(this));
        })
    })
}(jQuery));

function apply_accounts_masks(obj){
    const name = obj.attr('name');
    const value = obj.val();
    const row = name.substring((name.indexOf('[')+1),(name.length - 1));
    const elem = jQuery("input[name='pix_key_code[" + row + "]']");
    switch (value){
        case 'telefone':
            elem.mask('(00)00000-0000');
            break;
        case 'cpf':
            elem.mask('000.000.000-00');
            break;
        case 'cnpj':
            elem.mask('00.000.000/0000-00');
            break;
        case 'evp':
            elem.mask('AAAAAAAA-AAAA-AAAA-AAAA-AAAAAAAAAAAA');
            break;
        default:
            elem.unmask();
            break;
    }
}

function apply_channels_masks(obj){
    const name = obj.attr('name');
    const value = obj.val();
    const row = name.substring((name.indexOf('[')+1),(name.length - 1));
    const elem = jQuery("input[name='pix_contact_value[" + row + "]']");
    if(value === 'whatsapp'){
        elem.mask('(00)00000-0000');
    } else {
        elem.unmask();
    }
}

function mail_validation(mail){
    const regex = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    if(!regex.test(mail)){
        console.log(mail);
    }
}