$(document).on('click', '#merge_btn', function (event) {
    var x = getChecked(2);
    if (!x)
        return;

    var html = '', product_id = '';
    $.each(val, function (i, v) {
        if (i == 0) {
            product_id = v;
        }
        var _checked = i == 0 ? 'checked="checked"' : '';
        html += '<input type="radio" id="product_id_' + v + '" name="product_id" value="' + v + '" ' + _checked + '><label for="product_id_' + v + '">' + v + '</label>';
    });

    $('#merge-modal .product_list').html(html);
    getProductDetailMerge(product_id);

});

$(document).on('click', '.product_list input[type=radio]', function (event) {
    var product_id = $('input[name=product_id]:checked').val();
    getProductDetailMerge(product_id);
});

function mergeProject() {
    var x = getChecked();
    if (!x)
        return;

    var formData = new FormData(),
        form_merge = $('#form-merge').serializeArray();

    form_merge.forEach(function (fields) {
        formData.append(fields.name, fields.value);
    });

    $.each(val, function (i, v) {
        formData.append('product_ids[]', v);
    })

    formData.append('_token', get_csrf_token());

    $.ajax({
        url: PRODUCT_MERGE_URL,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (data) {
            if (data.e == 0) {
                $('#merge-modal').modal('hide');
                alert('Đã gộp dự án thành công!');
                location.reload();
            } else {
                alert(data.r);
            }
        }
    });
}

function getProductDetailMerge(product_id) {
    $.ajax({
        url: PRODUCT_MERGE_DETAIL_URL,
        type: 'GET',
        data: {product_id: product_id, _token: get_csrf_token()},
        success: function (data) {
            if (data.e == 0) {
                $('#merge-modal .product_detail').html(data.r);
                $('.select2').select2();
                new SelectLocation('#form-merge ');
                $('#merge-modal').modal('show');
            } else {
                alert(data.r);
            }
        }
    });
}

$(document).on('change', 'select[name=home_structure]', function () {
    var id = parseInt(this.value);
    if (id == 3)
        $('.home_stucture_detail').removeClass('d-none');
    else $('.home_stucture_detail').addClass('d-none');
});
