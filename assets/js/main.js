/* Слайдер */
$(document).ready(function () {
    var slideInterval = 4000;

    $('#viewport').hover(function(){
        clearInterval(switchInterval);
    },function() {
        switchInterval = setInterval(nextSlide, slideInterval);
    });

    $('#next-btn').click(function() {
        nextSlide();
    });

    $('#prev-btn').click(function() {
        prevSlide();
    });
});


var slideNow = 1;
var slideCount = $('#slidewrapper').children().length;
var translateWidth = 0;

function nextSlide() {

    if (slideNow == slideCount || slideNow <= 0 || slideNow > slideCount) {
        $('#slidewrapper').css('transform', 'translate(0, 0)');
        slideNow = 1;
    } else {
        translateWidth = -$('#viewport').width() * (slideNow);
        $('#slidewrapper').css({
            'transform': 'translate(' + translateWidth + 'px, 0)',
            '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
            '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
        });
        slideNow++;
    }
}

function prevSlide() {
    if (slideNow == 1 || slideNow <= 0 || slideNow > slideCount) {
        translateWidth = -$('#viewport').width() * (slideCount - 1);
        $('#slidewrapper').css({
            'transform': 'translate(' + translateWidth + 'px, 0)',
            '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
            '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
        });
        slideNow = slideCount;
    } else {
        translateWidth = -$('#viewport').width() * (slideNow - 2);
        $('#slidewrapper').css({
            'transform': 'translate(' + translateWidth + 'px, 0)',
            '-webkit-transform': 'translate(' + translateWidth + 'px, 0)',
            '-ms-transform': 'translate(' + translateWidth + 'px, 0)',
        });
        slideNow--;
    }
}

/* кнопка "вверх" */

$( document ).ready(function() {
    if ($(window).width() > 767 ) {
        $('#scrollup img').mouseover( function(){
            $( this ).animate({opacity: 0.65},100);
        }).mouseout( function(){
            $( this ).animate({opacity: 1},100);
        }).click( function(){
            window.scroll(0 ,0);
            return false;
        });

        $(window).scroll(function(){
            if ( $(document).scrollTop() > 0 ) {
                $('#scrollup').fadeIn('fast');
            } else {
                $('#scrollup').fadeOut('fast');
            }
        });
    }
});

/* Раскрытие списка категорий товаров */
function shiftSubDiv(n)
{
    var el = document.getElementById('subDiv'+n);

    if ( el.className  == 'subDivn' )
        el.className  = 'subDivb';
    else
    if ( el.className  == 'subDivb' )
        el.className  = 'subDivn';
}

/* фильтр товара */

function showProductList($controllerShow, $category, $startFrom){

    if(!$startFrom) {
        $startFrom = 0;
    }

    $.ajax({
        /* адрес файла-обработчика запроса */
        url: '/catalog/showProduct'+$controllerShow,
        /* метод отправки данных */
        method: 'POST',
        /* данные, которые мы передаем в файл-обработчик */
        data: {"cat" : $category, "startFrom": $startFrom},
        success: function(data) {

            /* Преобразуем результат, пришедший от обработчика - преобразуем json-строку обратно в массив */
            data = jQuery.parseJSON(data);
            $(".no_prod").remove();

            if(!$startFrom) {
                $(".products").remove();
            }

            /* Если массив не пуст (т.е. статьи там есть) */
            if (data.length > 0) {

                $(".btn_more_prod").remove();

                /* Делаем проход по каждому результату, оказвашемуся в массиве,
                 где в index попадает индекс текущего элемента массива, а в data - сама статья */
                $.each(data, function (index, data) {

                    /* Отбираем по идентификатору блок со статьями и дозаполняем его новыми данными */
                    $("#catalogue").append
                    (
                        "<div class=\"col-xs-6 col-sm-6 col-md-4 products\"><div class=\"product\"><div class=\"product_img\">"+
                        "<a href=\"http://codeigniterwebstore.webaddiction.ru/product/getProd/"+ data.id +"\" target=\"_blank\">"+
                        "<img src=\"http://codeigniterwebstore.webaddiction.ru/assets/images/product/"+ data.image +"\" class=\"product_img_inside\">"+
                        "</a>"+
                            "<div class=\"hit"+data.id+"\"></div>"+
                        "</div>"+
                        "<div class=\"product_body\">"+
                        "<a href=\"http:http://codeigniterwebstore.webaddiction.ru/product/getProd/"+ data.id +"\" class=\"product_title\" target=\"_blank\">"+
                        data.title +
                        "</a>"+
                        "<div class=\"product_price\">"+
                        data.price + " руб."+
                        "</div>"+
                        "<button type=\"button\" class=\"product-btn btn\"  onClick=\"add_to_cart("+
                        data.id+")\">В корзину"+
                        "</button>"+
                        "</div></div></div>"
                    );

                    if (data.is_new == 1) {
                        $(".hit"+data.id).append(
                            "<div class=\"hit_product\">"+
                            "<img src=\"http://codeigniterwebstore.webaddiction.ru/assets/images/work/hit.png\" class=\"hit_product_inside\">"+
                            "</div>"
                        );
                    }

                });

                $startFrom+=6;

                $("#moreProd").append
                (
                    "<button type=\"button\" class=\"product-btn btn btn_more_prod add-to-cart\" onClick=\"showProductList('"+
                    $controllerShow+"',"+$category+","+$startFrom+")\">Еще</button>"
                );
            } else {

                $(".btn_more_prod").remove();

                $("#moreProd").append
                (
                    "<p class='no_prod'>Товаров больше нет</p>"
                );
            }
        }
    })
}

/* добавление товара в корзину */

function add_to_cart($id){

    $.blockUI({ message: $('#add_message') });
    setTimeout($.unblockUI, 500);


    $.ajax({
        url: '/cart/addAjax/'+$id,
        method: 'POST',
        success: function(data) {


            $(".total-cart-items-id").remove();
            if (data.length > 0) {

               $("#total-cart-items").append("<div class=\"total-cart-items-id\">"+data+"</div>");

            }
        }
    })
}



/* зависимые выпадающие списки, добавление товара */

function selectCat($prod_id){

    $( '#selectproductscat_'+$prod_id ).change(function () {

        $('#selectproducstuncat_'+$prod_id).find('option:not(:first)')
            .remove()
            .end()
            .prop('disabled', true);
        var id = $(this).val();

        if (id == 0) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: '/Adminproductsadd/selectCat',
            dataType: "json",
            data: ({"id": id})
        }).done(function (data) {
            $('#selectproductsuncat_'+$prod_id).prop('disabled', false).empty();
            if (data.length > 0) {
                $.each(data, function (index, data) {

                    $("#selectproductsuncat_"+$prod_id).append('<option value="' + data.id + '">' + data.title + '</option>');
                });
            }});
    });
}

$(document).ready(function(){
    selectCat(1);
});

/* Админка. Редактирование подкатегорий */

function showRedUncat($id){
    $.ajax({
        url: '/admincategory/reduncat/'+$id,
        method: 'POST',
        success: function(data) {

            if (data.length > 0) {
                data = jQuery.parseJSON(data);
                $('.red_uncat_'+$id).remove();
                    $('#red_uncat_table_'+$id).append(
                        "<form action=\"Admincategory/safeuncat\" method=\"post\">"+
                        "<table class=\"table table-hover\">"+
                        "<tr id=\"red_uncat_safe\">" +
                        "<input type=\"hidden\" name=\"id\" value=\""+data.id+"\">"+
                        "<td><input type=\"text\" name=\"title\" value=\"" + data.title + "\" /></td>" +
                        "<td><input type=\"text\" name=\"sort_order\" value=\"" + data.sort_order + "\" /></td>" +
                        "<td><button class=\"admin_btn_add btn\">Сохранить</button></td>" +
                        "<td><button class=\"admin_btn_add btn\" type=\"submit\"   onclick='location=\"http://codeigniterwebstore.webaddiction.ru/admincategory\"'>Отмена</button></td>" +
                        "</tr>"+
                        "</table>"+
                        "</form>");
            }
        }
    })
}

/* Админка. Редактирование категорий */

function showRedCat($id){
    $.ajax({
        url: '/admincategory/redcat/'+$id,
        method: 'POST',
        success: function(data) {

            if (data.length > 0) {
                data = jQuery.parseJSON(data);
                $('.red_cat_'+$id).remove();
                $('#red_cat_table_'+$id).append(
                    "<form action=\"Admincategory/safecat\" method=\"post\">"+
                    "<table class=\"table table-hover\">"+
                    "<tr id=\"red_cat_safe\">" +
                    "<input type=\"hidden\" name=\"id\" value=\""+data.id+"\">"+
                    "<td><input type=\"text\" name=\"title\" value=\"" + data.title + "\" /></td>" +
                    "<td><input type=\"text\" name=\"sort_order\" value=\"" + data.sort_order + "\" /></td>" +
                    "<td><button class=\"admin_btn_add btn\">Сохранить</button></td>" +
                    "<td><button class=\"admin_btn_add btn\" type=\"submit\"  onclick='location=\"http://codeigniterwebstore.webaddiction.ru/admincategory\"'>Отмена</button></td>"+
                    "</tr>"+
                    "</table>"+
                    "</form>"
                );
            }
        }
    })
}

/* Админка. Добавление категорий */
function showAddCat(){
    $('#add_cat_form').append(
        "<td>"+
        "<form action=\"Admincategory/addcat\" method=\"post\" class='admin_prod_form'>"+
        "<input type=\"text\" name=\"title\" />" +
        "<input type=\"text\" name=\"sort_order\" />" +
        "<button class=\"admin_btn_add btn\">Сохранить</button>" +
        "<button class=\"admin_btn_add btn\" type=\"submit\" onclick='location=\"http://codeigniterwebstore.webaddiction.ru/admincategory\"'>Отмена</button>"+
        "</form>"+
        "</td>"
    );
}

/* Админка. Добавление подкатегорий */
function showAddUncat($id_cat){
    $('#add_uncat_form_'+$id_cat).append(
        "<td>"+
        "<form action=\"Admincategory/adduncat\" method=\"post\" class='admin_prod_form'>"+
        "<input type=\"text\" name=\"title\" />" +
        "<input type=\"hidden\" name=\"category_id\" value='"+ $id_cat +"'/>" +
        "<input type=\"text\" name=\"sort_order\" />" +
        "<button class=\"admin_btn_add btn\">Сохранить</button>" +
        "<button class=\"admin_btn_add btn\" type=\"submit\" onclick='location=\"http://codeigniterwebstore.webaddiction.ru/admincategory\"'>Отмена</button>"+
        "</form>"+
        "</td>"
    );
}

/* поиск */

$( document ).ready(function() {
    $("#searchprod").click(function () {

        $.ajax({
            /* адрес файла-обработчика запроса */
            url: '/catalog/searchprod',
            /* метод отправки данных */
            method: 'POST',
            /* данные, которые мы передаем в файл-обработчик */
            data: jQuery("#searchform").serialize(),
            success: function(data) {

                /* Преобразуем результат, пришедший от обработчика - преобразуем json-строку обратно в массив */
                data = jQuery.parseJSON(data);
                $(".no_prod").remove();
                $(".products").remove();
                $(".btn_more_prod").remove();

                /* Если массив не пуст (т.е. статьи там есть) */
                if (data.length > 0) {

                    /* Делаем проход по каждому результату, оказвашемуся в массиве,
                     где в index попадает индекс текущего элемента массива, а в data - сама статья */
                    $.each(data, function (index, data) {

                        /* Отбираем по идентификатору блок со статьями и дозаполняем его новыми данными */
                        $("#catalogue").append
                        (
                            "<div class=\"col-xs-12 col-sm-6 col-md-4 products\"><div class=\"product\"><div class=\"product_img\">"+
                            "<a href=\"http://base-hookah.ru/product/getProd/"+ data.id +"\" target=\"_blank\">"+
                            "<img src=\"http://base-hookah.ru/assets/images/product/"+ data.image +"\" class=\"product_img_inside\">"+
                            "</a>"+
                            "<div class=\"hit"+data.id+"\"></div>"+
                            "</div>"+
                            "<div class=\"product_body\">"+
                            "<a href=\"http://base-hookah.ru/product/getProd/"+ data.id +"\" class=\"product_title\" target=\"_blank\">"+
                            data.title +
                            "</a>"+
                            "<div class=\"product_price\">"+
                            data.price + " руб."+
                            "</div>"+
                            "<button type=\"button\" class=\"product-btn btn\"  onClick=\"add_to_cart("+
                            data.id+")\">В корзину"+
                            "</button>"+
                            "</div></div></div>"
                        );

                        if (data.is_new == 1) {
                            $(".hit"+data.id).append(
                                "<div class=\"hit_product\">"+
                                "<img src=\"http://base-hookah.ru/assets/images/work/hit.png\" class=\"hit_product_inside\">"+
                                "</div>"
                            );
                        }

                    });

                } else {

                    $("#catalogue").append
                    (
                        "<p class='no_prod'>К сожалению, поиск с заданными вами параметрами не дал результатов.</p>"
                    );
                }
            }
        })
    })
});

/* проверка возраста */
$(document).ready(function() {
    if (typeof $.cookie('age_control1') === 'undefined') {
        $('.welcome_page').removeClass('admin_hidden').addClass('fx-row fx-middle');
    }
    $('.aa-yes').click(function() {
        $('.welcome_page').slideUp(200);
        $.cookie('age_control1', 'cookie_yes', {
                path: '/'
        });
    });
    $('.aa-no').click(function() {
        window.location.href = 'https://www.google.ru';
    });
});

/* Оформление заказа. Активность форм */
$(document).ready(function() {
    order_radio_form();
});

function order_radio_form() {
    if ($('#optionsRadios1').prop("checked")) {
        $('.order1_form').prop("disabled", false);
        $('.order2_form').prop("disabled", true);


        $.getScript("https://www.google.com/recaptcha/api.js");
        $('.capcha_form1').append(
            "<div><p>OkOkOk</p></div>"
        );

        $('.capcha_form2').empty();

    } else {
        $('.order2_form').prop("disabled", false);
        $('.order1_form').prop("disabled", true);

        $.getScript("https://www.google.com/recaptcha/api.js");
        $('.capcha_form2').append(
            "<div><p>OkOkOk</p></div>"
        );

        $('.capcha_form1').empty();
    }
}

/* Всплывающее окно */

function open_red_form($id) {
    $(".popup_prod_red_form_"+$id).fadeIn(300);

    selectCat($id);
}

function close_red_form($id) {
    $(".popup_prod_red_form_"+$id).fadeOut(300)
}

/* Мобильное меню */
$(document).ready(function() {
    $(".open_nav_btn").click(function() {
        $('nav ul').slideToggle(500);
    });

    $(window).resize(function() {
       if ($(window).width() > 767) {
           $('nav ul').removeAttr('style');
       }
    })
});

/* Изменение статуса заказа */
function nextStatus($status,$id) {

    $.ajax({
        url: '/adminorders/nextStatus/' + $status + '/' + $id,
        method: 'POST',
        success: function () {

            if ($status == '2') {
                $(".red_stat").empty().append(
                    "<p>"+
                    "<label>Поменять статус: </label>"+
                    "<button type=\"button\" class=\"product-btn btn\"  onClick=\"nextStatus('3',"+ $id +")\">"+
                    "Закрыт"+
                    "</button>"+
                    "</p>"+
                    "<p>Активен</p>"
                );
                $(".admin_ord_stat_"+$id).empty().append(
                    "Активен"
                );
            } else {
                $(".red_stat").empty().append(
                    "<p>Закрыт</p>"
                );
                $(".admin_ord_stat_"+$id).empty().append(
                    "Закрыт"
                );
            }
        }
    })
}

/* Админка. Добавление страниц */
function showAddCat(){
    $('#add_page_form').append(
        "<td>"+
        "<form action=\"Adminpages/addpage\" method=\"post\" class='admin_prod_form'>"+
        "<p>Название: </p>" +
        "<input type=\"text\" name=\"title\" />" +
        "<p>Псевдоним для адресной строки (одно слово на английском языке): </p>" +
        "<input type=\"text\" name=\"name\" /><br />" +
        "<button class=\"admin_btn_add btn\">Сохранить</button>" +
        "<button class=\"admin_btn_add btn\" type=\"submit\" onclick='location=\"http://codeigniterwebstore.webaddiction.ru/adminpages\"'>Отмена</button>"+
        "</form>"+
        "</td>"
    );
}

/* Админка превью изображения */
$(document).ready(function(){
    $('.admin_load_img').jPreview();
});
