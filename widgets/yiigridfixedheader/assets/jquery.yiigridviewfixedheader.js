/**
 * jQuery Yii GridViewFixedHeader plugin file.
 *
 * @author Faisal Kaleem <faisalkaleem@msn.com>
 */

(function ($) {
    
    var scrollbarWidth = 0;
    var settings = {afterAjaxUpdate: []};
    function getScrollbarWidth() {
        if (scrollbarWidth)
            return scrollbarWidth;
        var div = jQuery('<div style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"><div style="height:100px;"></div></div>');
        jQuery('body').append(div);
        var w1 = jQuery('div', div).innerWidth();
        div.css('overflow-y', 'auto');
        var w2 = jQuery('div', div).innerWidth();
        jQuery(div).remove();
        scrollbarWidth = (w1 - w2);
        return scrollbarWidth;
    }
    
    function syncHorizontalScroll(source, target) {
        var div_left_pos = jQuery(source).scrollLeft();
        target.scrollLeft(div_left_pos);
    }

    $.fn.yiiGridViewFixedHeader = function (options) {
        
//        settings = $.extend(settings, options || {});
        
        var grid_wrap_div = this;
        var grid_id = this.attr("id");

        var grid_table = jQuery("table", grid_wrap_div);
        var table_heads = jQuery("thead tr th", grid_table);
        var table_width = grid_table.css("width");

        var thead_tr_first = jQuery("thead tr:first", grid_table);
        var tbody_tr_first = jQuery("tbody tr:first", grid_table);

        grid_table.css("width", table_width);

        var table = jQuery("#"+grid_id+" table:first");
        var table_class = table.attr("class");

        var scrollbar_Width = getScrollbarWidth();

        if(jQuery(".header_Wrap_div").length>0){
            jQuery("div.header_Wrap_div").remove();
        }
        var wrap_div = jQuery("<div></div>")
                .addClass(grid_wrap_div.attr("class")).addClass("header_Wrap_div")
                .css("width", (grid_wrap_div.width() - scrollbar_Width) + "px");


        if (table_heads.length > 0) {
            var table = jQuery("<table id=\"fixed_header_table\"></table>").css({width: table_width}).addClass(table_class);
            jQuery("#grid-wrapper").before(wrap_div);
            jQuery(wrap_div).html(table);

            var total_td_width = 0;
            $("th, td", thead_tr_first).each(function (i) {
                w = $(this).outerWidth();
                total_td_width += w;
                $("th:eq(" + i + "), td:eq(" + i + ")", thead_tr_first).css("width", w + "px");
                $("th:eq(" + i + "), td:eq(" + i + ")", tbody_tr_first).css("width", w + "px");
            });

            table.prepend(jQuery("thead", grid_table));
        }

        jQuery(grid_wrap_div).scroll(function () {
            syncHorizontalScroll(this, wrap_div);
        });
        
        return this;
    };
})(jQuery);
