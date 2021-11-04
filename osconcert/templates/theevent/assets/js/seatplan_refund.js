function loader() {
    $.ajax({
        url: $.baseurl + "seatplan_ajax.php?mode=load" + $.ie + "&cPath=" + $.cPath,
        dataType: "json",
        success: function(e) {
            if (e.cart) {
                if (e.cart.length > 0 && typeof $.timer == "undefined") {
                    $("div#ajax_status").slideDown(200);
                    $.timer = setInterval(countdown, 1e3)
                }
                if (e.cart.length == 0 && typeof $.timer == "number") {
                    clearInterval($.timer);
                    delete $.timer;
                    $("div#ajax_status").slideUp(200).empty();
                    count = $.lifetime
                }
                $.each(e.cart, function(e, t) {
                    if ($("li#s" + t).hasClass("s")) {
                        $.cls = $("li#s" + t).attr("class").match(/(bl|rd|gr|or|fu|ye|sa|sb|te|th|pg)/gi);
                        if ($.cls != null) {
                            $("li#s" + t).removeClass("s").removeClass($.cls.toString()).addClass("y").addClass(flip($.cls.toString()))
                        }
                    }
                })
            }
            if (e.sold) {
                $.each(e.sold, function(e, t) {
                   // $("li#s" + t).unbind("click").removeClass("s").removeClass("z").addClass("x")
                })
            }
            if (e.lock) {
                $.each(e.lock, function(e, t) {
                  //  $("li#s" + t).unbind("click").removeClass("s").addClass("z")
                })
            }
            if (e.prev) {
                $.each(e.prev, function(e, t) {
                  //  $("li#s" + t).unbind("click").removeClass("x").addClass("o")
                })
            }
            if (e.shopping_box) {
                $("#box_ajaxCart").html(e.shopping_box);
                alert(lng.tooslow)
            }
        }
    })
}

function freeSeats() {
    $.ajax({
        url: $.baseurl + "seatplan_ajax.php?mode=free" + $.ie + "&cPath=" + $.cPath,
        dataType: "json",
        success: function(e) {
            if (e.free) {
                $.each(e.free, function(e, t) {
                    if ($("li#s" + t).hasClass("z")) {
                        $("li#s" + t).removeClass("z").addClass("s")
                    }
                })
            }
        }
    })
}

function bindTriggers() {
    $(".s").bind("click", function() {
        if (!$("div.ticket_discount").is(":hidden")) {
            $("div#ticket_discount").fadeOut()
        }
        if (!$("div#indicator").hasClass("activity")) {
            $("div#indicator").fadeIn(40, function() {
                $(this).addClass("activity")
            });
            clearInterval($.tick);
            clearInterval($.free);
            if ($(this).attr("id")) {
                $.id = $(this).attr("id").replace(/s/, "");
                if (!$(this).hasClass("y")) {
                    $("li#s" + $.id).addClass("activity_on");
                    $.ajax({
                        url: "seatplan_ajax.php",
                        data: "mode=add_seat" + $.ie + "&cPath=" + $.cPath + "&products_id=" + $.id,
                        dataType: "json",
                        success: function(e) {
                            if (e.denied) {
                                alert(lng.tooslow)
                            } else if (e.max) {
                                alert(lng.toomany)
                            } else {
                                $("li#s" + $.id).effect("transfer", {
                                    to: $("ul#ajax_cart")
                                }, 120, function() {
                                    $.title = $("li#s" + $.id)[0]._title.split(" - ", 2);
                                    $.ticket_color = $("li#s" + $.id).attr("class").match(/(lb|dr|rg|ro|uf|ey|as|bs|et|ht|gp)/gi);
                                    $.html = '<li id="c' + $.id + '" class="c ' + flip($.ticket_color.toString()) + '"><span class="cht">' + $.cht + '</span></br><span class="pn"><a href="' + $.baseurl + "product_info.php?products_id=" + $.id + '">' + $.title[0] + '</a></span><span class="cnt">1</span><div id="del' + $.id + '" class="bd"></div><span class="pp">' + $.title[1] + '</span><span class="res"  style="display:none">' + e.sum_res + "</span></li>";
                                    $("ul#ajax_cart").append($.html);
                                    count = parseInt(e.remaining);
                                    updateTotals()
                                })
                            } if (e.discounts) {
                                var t = "";
                                for (var n = 0; n < e.discounts.length; n++) {
                                    t += '<li data-choice_warning="' + e.discounts[n]["choice_warning"] + '" data-sale_id="' + e.discounts[n]["sale_id"] + '" id="' + e.discounts[n]["discounted_price"] + '" class="xx ' + e.granted + '">' + e.discounts[n]["description"] + "</li>"
                                }
                                t += '<li id="kill_discount"><strong>X</strong> ' + $.thank + '</li>';
                                $("ul#discount").html(t);
                                $("#discount_show_name").html(e.show_name);
                                $("#discount_products_name").html(e.products_name);
                                $("div#ticket_discount").fadeIn()
                            }
                            $.cls = $("li#s" + $.id).attr("class").match(/(bl|rd|gr|or|fu|ye|sa|sb|te|th|pg)/gi);
                            if ($.cls != null && !e.max) {
                                $("li#s" + $.id).removeClass("s").removeClass($.cls.toString()).addClass("y").addClass(flip($.cls.toString()))
                            }
                            $("div#indicator").fadeOut(200, function() {
                                $(this).removeClass("activity")
                            });
                            $("li#s" + $.id).removeClass("activity_on")
                        }
                    })
                } else {
                    if ($("li#s" + $.id).length == 1) {
                        $("li#s" + $.id).addClass("activity_off");
                        $.ajax({
                            url: "seatplan_ajax.php",
                            data: "mode=remove_seat" + $.ie + "&cPath=" + $.cPath + "&products_id=" + $.id,
                            dataType: "json",
                            success: function(e) {
                                $.cls = $("li#s" + $.id).attr("class").match(/(lb|dr|rg|ro|uf|ey|as|bs|et|ht|gp)/gi);
                                if ($.cls != null) {
                                    $("li#s" + $.id).removeClass("y").removeClass($.cls.toString()).addClass("s").addClass(flip($.cls.toString()))
                                }
                                $("li#c" + $.id).effect("transfer", {
                                    to: $("li#s" + $.id)
                                }, 120, function() {
                                    $("li#c" + $.id).fadeOut(400).remove();
                                    updateTotals()
                                });
                                $("div#indicator").fadeOut(200, function() {
                                    $(this).removeClass("activity")
                                });
                                $("li#s" + $.id).removeClass("activity_off")
                            }
                        })
                    }
                }
                $.tick = setInterval("loader();", $.refresh);
                $.free = setInterval("freeSeats();", $.refresh * 24)
            }
        }
    });
    $(".bd").on("click", function() {
        $.id = $(this).attr("id").replace("del", "");
        if ($("li#s" + $.id).length == 0) {
            $("div#indicator").fadeIn(40, function() {
                $(this).addClass("activity")
            });
            $.ajax({
                url: "seatplan_ajax.php",
                data: "mode=remove_seat" + $.ie + "&cPath=" + $.cPath + "&products_id=" + $.id,
                dataType: "json",
                success: function(e) {
                    $("li#c" + $.id).effect("puff", 200, function() {
                        if ($("tr#p" + $.id).length > 0) {
                            $("tr#p" + $.id).fadeOut(400, function() {
                                $(this).remove()
                            })
                        }
                        $("li#c" + $.id).remove();
                        updateTotals()
                    });
                    $("div#indicator").fadeOut(220, function() {
                        $(this).removeClass("activity")
                    });
                    if ($("ul#ajax_cart").children("li.c").length == 0) {
                        clearInterval($.timer);
                        $("div#ajax_status").slideUp(200).empty();
                        count = parseInt($.lifetime);
                        delete $.timer
                    }
                }
            })
        } else {
            $("li#s" + $.id).trigger("click");
            return false
        }
    });
    $(".c").on("click", function() {
        $.id = $(this).attr("id").replace("c", "");
        if ($("li#s" + $.id).length == 1) {
            $("li#s" + $.id).effect("pulsate", {
                times: 2,
                mode: "show"
            }, 360)
        }
    });
    $(".xx").on("click", function() {
        if (isNaN(parseInt($("input#qty").val()))) {
            $("input#qty").val("1")
        }
        if ($(this).attr("class")) {
            $the_id = $(this).attr("class").replace(/xx/, "")
        }
        if ($(this).attr("id")) {
            $the_price = $(this).attr("id") * 1e3
        }
        $the_sale_id = $(this).attr("data-sale_id");
        $.ajax({
            url: "seatplan_ajax.php",
            data: "mode=live_discount" + $.ie + "&products_id=" + $the_id + "&discount_id=" + $the_sale_id + "&quantity=1&cPath=" + $.cPath + "&new_price=" + $the_price,
            dataType: "json",
            success: function(e) {
                if (e.granted) {
                    {
                        $.html = '<span class="cht">' + e.show_name + '</span></br><span class="pn">' + (e.ga_in_cart > 1 ? e.ga_in_cart + "x" : "") + '<a href="' + $.baseurl + "product_info.php?products_id=" + e.granted + '">' + e.products_name + '</a></span><span class="cnt">' + e.ga_in_cart + '</span><div id="del' + e.granted + '" class="bd"></div><span class="pp">' + lng.sym_left + (e.discount_price * e.ga_in_cart).toFixed(2) + lng.sym_right + "</span>";
                        $("li#c" + e.granted).html($.html)
                    }
                    $("div#ticket_discount").effect("transfer", {
                        to: $("ul#ajax_cart")
                    }, 120, function() {
                        updateTotals()
                    });
                    $("div#ticket_discount").fadeOut()
                }
            }
        });
        return false
    });
    $(".xx").on("mouseout", function() {
        $("#discount_choice_text").html("")
    });
    $(".xx").on("mouseover", function() {
        $("#discount_choice_text").html($(this).attr("data-choice_warning"))
    });
    $("#kill_discount").on("click", function() {
        $("div#ticket_discount").fadeOut()
    });
    if ($("input#qty").length == 1) {
        $("input#qty").bind("keyup", function() {
            if (isNaN(parseInt($("input#qty").val()))) {
                $("input#qty").val("1")
            }
            $.qty = parseInt($("input#qty").val());
            if (product.discount_id != 0) {
                $.price = (product.saleMaker.sales[product.discount_id].price * $.qty).toFixed(2);
                $("span#totalProductsPrice").html(product.currency.symbolLeft + $.price + product.currency.symbolRight)
            } else {
                setTotalPrice()
            }
        })
    }
}

function countdown() {
    $("div#ajax_status").html(lng.expiry + "<br> " + (new Date).clearTime().addSeconds(parseInt(count)).toString("mm:ss"));
    if (count > 0) {
        $.pct = count / $.lifetime * 100;
        if ($.pct > 30) {
            $.col = "Green"
        } else {
            if ($.pct > 20) {
                $.col = "Orange"
            } else {
                $.col = "Red"
            }
        }
        $("div#ajax_status").css({
            color: $.col
        });
        count -= 1
    } else {
        $.ajax({
            url: "seatplan_ajax.php",
            data: "mode=terminate" + $.ie + "&cPath=" + $.cPath,
            dataType: "json",
            success: function(e) {
                clearInterval($.tick);
                clearInterval($.free);
                clearInterval($.timer);
                if ($("div#easyTooltip").length > 0) {
                    $("div#easyTooltip").fadeOut(0)
                }
                $("ul#ajax_cart").html('<li class="timedout">' + lng.expired + "</li>");
                $("div.clear").html('<div id="cleared">' + lng.cleared + "</div>");
                $("span#total_price").html(lng.sym_left + "0.00" + lng.sym_right);
                $("span#total_seats").html("0 " + lng.seats);
                $("li#ticket_count span").html("0 " + lng.seats);
                $("div#ajax_status").slideUp(400);
                $("div#btnCheckOut").fadeOut(200);
                $("div#res_display").fadeOut(200)
            }
        })
    }
}

function mobileLayout() {
    var e = navigator.userAgent;
    if (e.indexOf("Android") != -1 || e.indexOf("iPhone") != -1) {
        $("div.nav").children("a").css({
            height: "20px",
            paddingTop: "12px",
            paddingBottom: "8px"
        });
        $("div.navSelect").children("a").css({
            height: "20px",
            paddingTop: "12px",
            paddingBottom: "8px"
        });
        $("div.navGroup").children("a").css({
            height: "20px",
            paddingTop: "12px",
            paddingBottom: "8px"
        })
    }
}

function flip(e) {
    st = e.split("");
    rt = st.reverse();
    return rt.join("")
}

function submitData() {
    if (isNaN(parseInt($("input#ga_qty").val()))) {
        $("input#ga_qty").val("1")
    }
    $.sym_left = product.currency.symbolLeft;
    $.sym_right = product.currency.symbolRight;
    $.ajax({
        url: "seatplan_ajax.php",
        data: "mode=ga" + $.ie + "&products_id=" + parseInt(product.id) + "&discount_id=" + parseInt(product.discount_id) + "&quantity=" + parseInt($("input#ga_qty").val()) + "&cPath=" + $.cPath,
        dataType: "json",
        success: function(e) {
            if (e.discount) {}
            if (e.granted) {
                if (product.discount_id == 0) {
                    $.price = parseFloat($("#price").val())
                } else {
                    $.price = product.saleMaker.sales[product.discount_id].price.toFixed(2)
                } if ($.cht == "") {
                    $.cht = e.products_name
                }
                if ($("li#c" + product.id).length == 0) {
                    $.html = '<span class="cht">' + $.cht + '</span></br><span class="pn">' + (e.ga_in_cart > 1 ? e.ga_in_cart + "x" : "") + '<a href="' + $.baseurl + "product_info.php?products_id=" + product.id + '">' + e.products_name + '</a></span><span class="cnt">' + e.ga_in_cart + '</span><div id="del' + product.id + '" class="bd"></div><span class="pp">' + $.sym_left + ($.price * e.ga_in_cart).toFixed(2) + $.sym_right + "</span>";
                    $("ul#ajax_cart").append('<li id="c' + product.id + '" class="c gr">' + $.html + "</li>")
                } else {
                    $.html = '<span class="cht">' + $.cht + '</span></br><span class="pn">' + (e.ga_in_cart > 1 ? e.ga_in_cart + "x" : "") + '<a href="' + $.baseurl + "product_info.php?products_id=" + product.id + '">' + e.products_name + '</a></span><span class="cnt">' + e.ga_in_cart + '</span><div id="del' + product.id + '" class="bd"></div><span class="pp">' + $.sym_left + ($.price * e.ga_in_cart).toFixed(2) + $.sym_right + "</span>";
                    $("li#c" + product.id).html($.html)
                }
                product.stock = e.ga_available_stock;
                if (typeof $.timer == "undefined") {
                    $("div#ajax_status").slideDown(200);
                    $.timer = setInterval(countdown, 1e3)
                }
                updateTotals();
                if (e.ticketlimit) {}
                if (e.GAticketlimit) {}
            }
        }
    })
}
///thsi function only used for GA refunds
function submitDataRefund(pid, order_id,quan_avail) {
    //check quantity
	if(parseInt($("input#ga_qty"+pid).val()) > quan_avail){
										  alert('You may only refund a maximum of: '+quan_avail)
										  return
										  
										  }

    $.sym_left = product.currency.symbolLeft;
    $.sym_right = product.currency.symbolRight;
    $.ajax({
        url: "seatplan_ajax.php",
        data: "mode=ga_refund" + $.ie + "&products_id=" + pid + "&discount_id=0&quantity=" + parseInt($("input#ga_qty"+pid).val()) + "&cPath=" + $.cPath+"&order_id="+order_id,
        dataType: "json",
        success: function(e) {
            if (e.discount) {}
            if (e.granted) {
               
                    $.price = parseFloat($("#price").val())
					
               if ($.cht == "") {
                    $.cht = e.products_name
                }
                if ($("li#c" + e.granted).length == 0) {
                    $.html = '<span class="cht">' + $.cht + '</span></br><span class="pn">' + (e.ga_in_cart > 1 ? e.ga_in_cart + "x" : "") + '<a href="">' + e.products_name + ' </a></span><span class="cnt">' + e.ga_in_cart + '</span><div id="del' + e.granted + '" class="bd"></div><span class="pp"></span>"';
                    $("ul#ajax_cart").append('<li id="c' + e.granted + '" class="c gr">' + $.html + "</li>")
                } else {
                    $.html = '<span class="cht">' + $.cht + '</span></br><span class="pn">' + (e.ga_in_cart > 1 ? e.ga_in_cart + "x" : "") + '<a href="">' + e.products_name + '</a></span><span class="cnt">' + e.ga_in_cart + '</span><div id="del' + e.granted + '" class="bd"></div><span class="pp"></span>"';
                    $("li#c" + e.granted).html($.html)
                }
                product.stock = e.ga_available_stock;
                if (typeof $.timer == "undefined") {
                    $("div#ajax_status").slideDown(200);
                    $.timer = setInterval(countdown, 1e3)
                }
                updateTotals();
                if (e.ticketlimit) {}
                if (e.GAticketlimit) {}
            }
        }
    })
}

function updateTotals() {
    $.cnt = 0;
    $.sum = 0;
    $.sum_res = 0;
    $.each($("ul#ajax_cart").children("li"), function(e, t) {
        $.sum += parseFloat($(this).children(".pp").html().replace(lng.sym_left + lng.sym_right, "").replace(",", ""));
        $.sum_res += parseFloat($(this).children(".res").html());
        $.cnt += parseInt($(this).children(".cnt").html())
    });
	// get rid of NaN
	if (isNaN($.sum.toFixed(2))){
		$.sum.toFixed(2) = "----";
	}
    $("span#total_price").html(lng.sym_left + $.sum.toFixed(2) + lng.sym_right);
    $("span#total_res_price").html(lng.sym_left + $.sum_res.toFixed(2) + lng.sym_right);
    $("span#total_seats").html($.cnt == 1 ? "1 " + lng.seat : $.cnt + " " + lng.seats);
    $("li#ticket_count span").html($.cnt == 1 ? "1 " + lng.seat : $.cnt + " " + lng.seats);
    if ($("span#cart_subtotal").length > 0) {
        $("span#cart_subtotal").html("&nbsp;" + lng.sym_left + $.sum.toFixed(2) + lng.sym_right);
        if (parseInt($.sum) == 0 && $("span#discount_notice").length > 0) {
            $("span#discount_notice").remove()
        }
    }
    if ($.cnt > 0) {
        $("div#btnCheckOut").css({
            display: "block"
        })
    } else {
        $("div#btnCheckOut").css({
            display: "none"
        })
    } if ($.sum_res > 0) {
        $("div#res_display").css({
            display: "block"
        })
    } else {
        $("div#res_display").css({
            display: "none"
        })
    }
}

function bindInputQuantity() {
    $("input#qty").bind("keyup", function() {
        checkStock("")
    })
}
$.ie = window.navigator.userAgent.indexOf('MSIE ') > 0 || window.navigator.userAgent.indexOf('Trident/') > 0 ? "_ie" : "";
var running = false;
var timer;
var count = parseInt($.remaining);
$(function() {
    mobileLayout();
    bindTriggers();
    if (typeof $.cPath != "undefined" && $.cPath != 0) {
        loader();
        if ($("div.seatplan").length == 1) {
            $.tick = setInterval("loader();", $.refresh);
            $.free = setInterval("freeSeats();", $.refresh * 24)
        }
        if ($.timeout && $.remaining != 0) {
            $("div#ajax_status").fadeIn(0);
            $.timer = setInterval(countdown, 1e3)
        }
        $("li.s").easyTooltip()
    } else {
        if (typeof product == "object") {
            $.cPath = product.cPath;
            bindInputQuantity()
        }
        if ($.timeout && $.remaining != 0) {
            $("div#ajax_status").fadeIn(0);
            $.timer = setInterval(countdown, 1e3)
        }
    } if ($("ul#ajax_cart").children("li.c").length > 0) {
        $("div#btnCheckOut").fadeIn(0)
    }
    if ($("div#res_display_total").length > 0) {
        $("div#res_display").css({
            display: "block"
        })
    }
})

$(document).ready(function() {
    var borp = window.box_office_refund_product;
	if ( $( borp ).length ) {
    $(borp)
.css({'border-color':'red','border-color':'red','color':'red'});
//.css('outline-color', 'red');
	    $('html, body').animate({
        scrollTop: $(borp).offset().top - $("#triple_boxes").outerHeight()
    }, 2000);
	}
});