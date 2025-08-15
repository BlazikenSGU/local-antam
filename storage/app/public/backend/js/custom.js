$.fn.modal.Constructor.prototype._enforceFocus = function () {
};

$.fn.select2.defaults.set("theme", "bootstrap");

$(document).ready(function () {
    $('[data-toggle="datepicker"]').datetimepicker({
        format: 'Y-m-d H:i:s'
    });
});

$('.select2').select2();

var _token;

function get_csrf_token() {
    _token = $('meta[name="csrf-token"]').attr('content');
    return _token;
}

function set_csrf_token(token) {
    _token = token;
    $('meta[name="csrf-token"]').attr('content', token);
}

//check all button
$(document).on('click', '.check-all', function (event) {
    $('.check-all-child').prop('checked', this.checked);
    $('.check-all').prop('checked', this.checked);
});

var val = [];

function getChecked(number) {
    if (number == undefined)
        var number = 1;

    val = [];
    $('.check-all-child:checked').each(function () {
        val.push($(this).val());
    });

    if (val.length < number) {
        alert('Vui lòng chọn ít nhất ' + number + ' sản phẩm!');
        return false;
    }
    return true;
}

function SelectLocation(form_id) {
    $(form_id + '.select_province').change(function () {
        $(form_id + '.select_district').html('<option value="">Chọn</option>');
        $(form_id + '.select_ward').html('<option value="">Chọn</option>');
        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        var province_id = $(this).val();
        $.ajax({
            type: 'GET',
            url: LOCATION_DISTRICT_URL,
            data: {province_id: province_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_district').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    });

    $(form_id + '.select_district').change(function () {
        $(form_id + '.select_ward').html('<option value="">Chọn</option>');
        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        var district_id = $(this).val();
        $.ajax({
            type: 'GET',
            url: LOCATION_WARD_URL,
            data: {district_id: district_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_ward').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });

        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        $.ajax({
            type: 'GET',
            url: LOCATION_STREET_URL,
            data: {district_id: district_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_street').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });

    });

    /*$(form_id + '.select_ward').change(function () {
        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        var ward_id = $(this).val();
        $.ajax({
            type: 'GET',
            url: LOCATION_STREET_URL,
            data: {ward_id: ward_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_street').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    });*/
};

$('.image_select').on('dragenter', function () {
    $(this).parent().find('div').addClass('dragover');
});

$('.image_select').on('dragleave', function () {
    $(this).parent().find('div').removeClass('dragover');
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
