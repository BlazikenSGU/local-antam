// product add on v 1.0.0
console.log('product add on v1.0.0');


/** Add Product Form */
function ShowAddProductForm(phone, shop_id){
    $.get("/frmProducts.php?phone=" + phone + "&shop_id=" + shop_id, function(data){

        $("#frm_Products").html(data);
        $("#frm_Products").show();
    });
}

function CloseAddProductForm(){
    $("#frm_Products").hide();
}