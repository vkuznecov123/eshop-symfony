$(function () { // после загрузки страницы

    // Функция, вызывающая выпадение подменю
    function drop(x) {
        if (x.parents().eq(2).is('.open')) {  // если открывается подменю дальше первого уровня
            if (!x.parent().is('.open')) {  // исключает закрытие при повторном наведении
                x.dropdown('myToggle');  // вызываем наш добавленный метод, не закрывающий родительские подменю
            }
        }
        else if (!x.parent().is('.open')) {  // исключает закрытие при повторном наведении
            x.dropdown('toggle');
            x.blur(); // убираем ненужный фокус
        }
    }

    // Функция myClearMenus, аналогичная bootstrap ф-ции сlearMenus, но закрывающая только соседние и вложенные меню.

    function myClearMenus ($this) {
        $this.find('[data-toggle="dropdown"]').each(function () {
            var $this = $(this);
            var $parent = $this.parent();
            var relatedTarget = {relatedTarget: this};

            if (!$parent.hasClass('open')) return;

            var e;
            $parent.trigger(e = $.Event('hide.bs.dropdown', relatedTarget));

            if (e.isDefaultPrevented()) return;

            $this.attr('aria-expanded', 'false');
            $parent.removeClass('open').trigger($.Event('hidden.bs.dropdown', relatedTarget))
        });
    }


    /* В выпадающем меню используется два типа таймеров:
     *  Глобальный на элементе .navTree, отвечающий за исчезновение всех подменю при уходе курсора с элементов меню
     *  Локальные на всех элементах [data-toggle="dropdown"], отвечающие за плавное раскрытие подменю     *
     */

    // Устанавливаем таймер закрытия всех меню при уходе курсора с элемента меню
    $('.navTree .menuElement').mouseout(function(){
        var $navTree = $(this).parents('.navTree:first');
        var timer=setTimeout(myClearMenus, 700, $navTree); // передаем в функцию корень меню для закрытия всех подменю
        $navTree.data('timer',timer); // таймер устанавливается на корневой элемент .navTree
    });

    // Скрываем подменю при наведении на элемент меню и снимаем таймер закрытия всех меню
    $('.navTree .menuElement').mouseover(function(){
        if (!$(this).parent().is('.open')) {  // исключает закрытие при повторном наведении
            myClearMenus($(this).parents('ul:first')); // передаем в функцию элемент, у которого нужно закрывать вложенные подменю
        }
        var $navTree = $(this).parents('.navTree:first');
        clearTimeout($navTree.data('timer'));
    });

    // Устанавливаем таймер на открытие подменю при наведении
    $('.navTree [data-toggle="dropdown"]').mouseover(function(){
        console.log();
        var timer=setTimeout( drop, 500, $(this));
        $(this).data('timer',timer);  // таймер устанавливается на текущий элемент
    });

    // Сбрасываем таймер при уходе курсора с пункта меню
    $('.navTree [data-toggle="dropdown"]').mouseout(function(){
        clearTimeout($(this).data('timer'));
    });

    // Заново устанавливаем обработчики на клик по ссылке в меню (default был отменен bootstrap-ом)
    $('.navTree a[data-toggle="dropdown"]').on('click',function (e) {
        location.assign(this.getAttribute('href'));
    });

    // Исправляем изменение размера меню в момент прилипания к верхней границе при прокрутке

    $('#fix').on('affix.bs.affix',function(){
        var width = $(this).parent().width();
        $(this).width(width);
    });

    $('#fix').on('affix-top.bs.affix',function(){
        $(this).width('100%');
    });

    $(window).resize(function () {
        var width = $('#fix.affix').parent().width();
        $('#fix.affix').width(width);
    });

    // Записываем в прототип объектов класса Dropdown метод MyToggle, аналогичный Toggle,
    // но не закрывающий открытые подменю (нужные подменю уже закрыты функцией myClearMenus)

    $.fn.dropdown.Constructor.prototype.myToggle  = function () {
        var $this = $(this);

        if ($this.is('.disabled, :disabled')) return;

        var $parent = $this.parent();
        var isActive = $parent.hasClass('open');

       // clearMenus(); не скрываем всплывшие меню (нужные подменю уже закрыты функцией myClearMenus)

        if (!isActive) {
            var relatedTarget = {relatedTarget: this};
            var e;
            $parent.trigger(e = $.Event('show.bs.dropdown', relatedTarget));

            if (e.isDefaultPrevented()) return;

            $this
                //.trigger('focus') фокус не нужен
                .attr('aria-expanded', 'true');

            $parent
                .toggleClass('open')
                .trigger($.Event('shown.bs.dropdown', relatedTarget))
        }
    }

}); // конец document ready