    <style>
        #wrap_ct_frmProducts {
            padding: 10px;
        }
        #btnClose {
            position: fixed;
            top: 85px;
            right: 15px;
            cursor: pointer;
        }
        #btnClose:hover {
            font-weight: bold;
        }
        .row-dx {
            margin: 10px;
        }
        .col-dx {
            width: 300px;
            margin: 5px;
        }
        .btn-dx {
            min-width: 300px;
            padding: 10px;
        }
        .btn-dx:hover {
            color: blue;
            cursor: pointer;
        }
        .dv-dx {
            width: 100%;
            border-top: 1px solid gray;
        }
    </style>
<div id="wrap_ct_frmProducts">
    <div id="btnClose" onclick="CloseAddProductForm()">[x]</div>
    <div><h2>Quản lý sản phẩm</h2></div>
    <div class="row-dx">
        <div class="col-dx">
            Tên SP: <input class="form-control" id="txtProductName" placeholder="Tên Sản phẩm" />
        </div>
        <div class="col-dx">
            Mã SP: <input class="form-control" id="txtProductCode" placeholder="Mã Sản phẩm" />
        </div>
        <div class="col-dx">
            Khối lượng (g): <input class="form-control" id="txtProductWeight" placeholder="Khối lượng" type="number" />
        </div>
    </div>
    <div class="row-dx">
        <div class="col-dx">
            <button class="btn btn-primary" onclick="AddNewProduct()">Lưu Sản Phẩm</button>
        </div>
    </div>
    <div class="row-dx">
        <div class="dv-dx"></div>
    </div>
    <div class="row-dx">
        <!-- Product List -->
        <div class="row">
            <table class="table table-striped" id="product-list">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Mã Sản Phẩm</th>
                        <th scope="col">Tên Sản Phẩm</th>
                        <th scope="col">Khối lượng (gram)</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody id="product-list-body">
                    <!-- Danh sách sản phẩm sẽ được load ở đây -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Lấy số điện thoại và shop_id từ URL (nếu có)
    var phone = '<?php echo isset($_GET['phone']) ? $_GET['phone'] : ''; ?>';
    var shop_id = '<?php echo isset($_GET['shop_id']) ? $_GET['shop_id'] : ''; ?>';

    $(function(){
        LoadProductList(phone);
    });

    function AddNewProduct() {
    var product_name = $("#txtProductName").val().trim();
    var product_code = $("#txtProductCode").val().trim();
    var product_weight = $("#txtProductWeight").val().trim();
    var str_err = "";

    if (product_name === "") {
        str_err += "- Vui lòng nhập Tên sản phẩm.\n";
    }
    if (product_code === "") {
        str_err += "- Vui lòng nhập Mã sản phẩm.\n";
    }
    if (product_weight === "" || isNaN(product_weight) || Number(product_weight) <= 0) {
        str_err += "- Vui lòng nhập Khối lượng sản phẩm hợp lệ.\n";
    }

    // Kiểm tra mã sản phẩm không trùng
    var duplicate = false;
    $(".product-code").each(function() {
        if ($(this).text().trim() === product_code) {
            duplicate = true;
        }
    });
    if (duplicate) {
        str_err += "- Mã sản phẩm không được trùng.\n";
    }

    if (str_err !== "") {
        alert(str_err);
        return;
    }

    $.post("/api/products.php?phone=" + phone + "&func=ADDPRODUCT", {
        product_name: product_name,
        product_code: product_code,
        product_weight: product_weight,
        shop_id: shop_id
    })
    .done(function(data) {
        alert(data);
        // Xoá các trường nhập sau khi thêm thành công
        $("#txtProductName").val("");
        $("#txtProductCode").val("");
        $("#txtProductWeight").val("");
        // Tải lại danh sách sản phẩm
        LoadProductList(phone);
    })
    .fail(function(err) {
        console.error("Lỗi thêm sản phẩm:", err);
        alert("Có lỗi xảy ra khi thêm sản phẩm.");
    });
}

function LoadProductList(phone) {
    $.get("/api/products.php?phone=" + phone + "&func=GETPRODUCTLIST&t=" + new Date().getTime())
    .done(function(data) {
        $("#product-list-body").html(data);
    })
    .fail(function(err) {
        console.error("Lỗi tải danh sách sản phẩm:", err);
    });
}

function DeleteProduct(product_id) {
    if (confirm("Bạn có chắc muốn xoá sản phẩm này?")) {
        $.post("/api/products.php?phone=" + phone + "&func=DELETEPRODUCT", {
            product_id: product_id,
            shop_id: shop_id
        })
        .done(function(data) {
            alert(data);
            // Sau khi xoá thành công, tải lại danh sách sản phẩm
            LoadProductList(phone);
        })
        .fail(function(err) {
            console.error("Lỗi xoá sản phẩm:", err);
            alert("Có lỗi xảy ra khi xoá sản phẩm.");
        });
    }
}

    function CloseAddProductForm() {
        // Ví dụ: ẩn form
        $("#wrap_ct_frmProducts").hide();
    }
</script>