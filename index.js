$(document).ready(function(){

    // banner owl carousel
$("#banner-area .owl-carousel").owlCarousel({
    dots: true,
    items: 1
});

// top sale owl carousel
$("#top-sale .owl-carousel").owlCarousel({
    loop: true,
    nav: true,
    dots: false,
    responsive : {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000 : {
            items: 4
        }
    }
});

// isotope filter
var $grid = $(".grid").isotope({
    itemSelector : '.grid-item',
    layoutMode : 'fitRows'
});

// filter items on button click
$(".button-group").on("click", "button", function(){
    var filterValue = $(this).attr('data-filter');
    $grid.isotope({ filter: filterValue});
})


// new phones owl carousel
$("#new-phones .owl-carousel").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    responsive : {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000 : {
            items: 4
        }
    }
});

// blogs owl carousel
$("#blogs .owl-carousel").owlCarousel({
    loop: true,
    nav: false,
    dots: true,
    responsive : {
        0: {
            items: 1
        },
        600: {
            items: 3
        }
    }
})


    // product qty section
    let $qty_up = $(".qty .qty-up");
    let $qty_down = $(".qty .qty-down");
    let $deal_price = $("#deal-price");

    // Function to handle the AJAX request for quantity change
    function handleQtyChange($btn, action) {
        let $input = $(`.qty_input[data-id='${$btn.data("id")}']`);
        let $price = $(`.product_price[data-id='${$btn.data("id")}']`);

        // Collect customer information
        let nama_pelanggan = $("#nama_pelanggan").val();
        let alamat_pelanggan = $("#alamat_pelanggan").val();
        let metode_pembayaran = $("input[name='metode_pembayaran']:checked").val();

        // Ensure customer information is retained
        $.ajax({
            url: "template/ajax.php",
            type: 'post',
            data: {
                itemid: $btn.data("id"),
                action: action,
                nama_pelanggan: nama_pelanggan,
                alamat_pelanggan: alamat_pelanggan,
                metode_pembayaran: metode_pembayaran
            },
            success: function (result) {
                let obj = JSON.parse(result);
                let item_price = obj[0]['item_price'];

                if (action === "qty_up") {
                    if ($input.val() >= 1 && $input.val() <= 9) {
                        $input.val(function (i, oldval) {
                            return ++oldval;
                        });

                        // Increase price of the product
                        $price.text(parseInt(item_price * $input.val()).toFixed(2));

                        // Set subtotal price
                        let subtotal = parseInt($deal_price.text()) + parseInt(item_price);
                        $deal_price.text(subtotal.toFixed(2));
                    }
                }

                if (action === "qty_down") {
                    if ($input.val() > 1 && $input.val() <= 10) {
                        $input.val(function (i, oldval) {
                            return --oldval;
                        });

                        // Decrease price of the product
                        $price.text(parseInt(item_price * $input.val()).toFixed(2));

                        // Set subtotal price
                        let subtotal = parseInt($deal_price.text()) - parseInt(item_price);
                        $deal_price.text(subtotal.toFixed(2));
                    }
                }
            }
        }); // closing ajax request
    }

    // Click on qty up button
    $qty_up.click(function (e) {
        handleQtyChange($(this), "qty_up");
    });

    // Click on qty down button
    $qty_down.click(function (e) {
        handleQtyChange($(this), "qty_down");
    });

    window.addEventListener('beforeunload', function() {
        // Send a request to clear the session
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("clear_checkout=true");
    });


});

