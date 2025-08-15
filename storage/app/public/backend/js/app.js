var LCK_App = {
    initialised: false,
    processing: false,
    google_maps_key: null,
    url_get_district: null,
    url_get_ward: null,
    url_get_street: null,
    url_get_project: null,
    url_get_alley: null,
    init: function () {

        if (!this.initialised) {
            this.initialised = true;
        } else {
            return;
        }

        $.fn.modal.Constructor.prototype._enforceFocus = function () {
        };

        $('.select2').select2();

        $('.select2_tag').select2({tags: true});

        $('.selectpicker').selectpicker();

        $('#form-add .select_province').on('change', function () {
            LCK_App.get_district('#form-add ');
            //LCK_App.get_project('#form-add ');
        });

        $('#form-add .select_district').on('change', function () {
            LCK_App.get_ward('#form-add ');
            if ($('#form-add .select_street').length) {
                LCK_App.get_street('#form-add ');
            }
            //LCK_App.get_project('#form-add ');
        });

        $('#form-add .select_ward').on('change', function () {
            // if ($('#form-add .select_street').length) {
            //     LCK_App.get_street('#form-add ');
            // }

            if ($('#form-add .select_project').length) {
                LCK_App.get_project('#form-add ');
            }
        });

        $('#form-add .select_street').on('change', function () {
            if ($('#form-add .select_alley').length) {
                LCK_App.get_alley('#form-add ');
            }
        });
    },
    get_district: function (form_id) {
        $(form_id + '.select_district').html('<option value="">Chọn</option>');
        $(form_id + '.select_ward').html('<option value="">Chọn</option>');
        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        $(form_id + '.select_project').html('<option value="">Chọn</option>');
        var province_id = $(form_id + '.select_province').val();
        $.ajax({
            type: 'GET',
            url: LCK_App.url_get_district,
            data: {province_id: province_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_district').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    },
    get_ward: function (form_id) {
        $(form_id + '.select_ward').html('<option value="">Chọn</option>');
        //$(form_id + '.select_street').html('<option value="">Chọn</option>');
        $(form_id + '.select_project').html('<option value="">Chọn</option>');
        var province_id = $(form_id + '.select_province').val();
        var district_id = $(form_id + '.select_district').val();
        $.ajax({
            type: 'GET',
            url: LCK_App.url_get_ward,
            data: {province_id: province_id, district_id: district_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_ward').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    },
    get_street: function (form_id) {
        $(form_id + '.select_street').html('<option value="">Chọn</option>');
        var province_id = $(form_id + '.select_province').val();
        var district_id = $(form_id + '.select_district').val();
        var ward_id = $(form_id + '.select_ward').val();
        $.ajax({
            type: 'GET',
            url: LCK_App.url_get_street,
            data: {province_id: province_id, district_id: district_id, ward_id: ward_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_street').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    },
    get_alley: function (form_id) {
        $(form_id + '.select_alley').html('<option value="">Chọn</option>');
        var province_id = $(form_id + '.select_province').val();
        var district_id = $(form_id + '.select_district').val();
        var ward_id = $(form_id + '.select_ward').val();
        var street_id = $(form_id + '.select_street').val();
        $.ajax({
            type: 'GET',
            url: LCK_App.url_get_alley,
            data: {province_id: province_id, district_id: district_id, ward_id: ward_id, street_id: street_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_alley').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    },
    get_project: function (form_id) {
        $(form_id + '.select_project').html('<option value="">Chọn</option>');
        var province_id = $(form_id + '.select_province').val();
        var district_id = $(form_id + '.select_district').val();
        var ward_id = $(form_id + '.select_ward').val();
        $.ajax({
            type: 'GET',
            url: LCK_App.url_get_project,
            data: {province_id: province_id, district_id: district_id, ward_id: ward_id},
            dataType: 'json',
            success: function (data) {
                $.each(data.data, function (index, element) {
                    $(form_id + '.select_project').append('<option value=' + element.id + '>' + element.name + '</option>');
                });
            }
        });
    }
};

function calculator_area() {
    var el_width = $('input[name=width]');
    var el_length = $('input[name=length]');
    var el_area = $('input[name=area]');
    var el_cut_area = $('input[name=cut_area]');
    var el_recognized_area = $('input[name=recognized_area]');

    var el_construct = $('select[name=construct]');
    var el_construct_area = $('input[name=construct_area]');
    var el_unit_construct_price = $('select[name=unit_construct_price]');
    var el_construct_price = $('input[name=construct_price]');

    var el_land_price = $('input[name=land_price]');
    var el_land_sqm_price = $('input[name=land_sqm_price]');
    var el_land_recognized_sqm_price = $('input[name=land_recognized_sqm_price]');

    var el_price = $('input[name=price]');
    var el_price_type = $('select[name=price_type]');

    var width = parseFloat(el_width.val());
    var length = parseFloat(el_length.val());
    var area = parseFloat(el_area.val());
    var cut_area = parseFloat(el_cut_area.val());
    var recognized_area = parseFloat(el_recognized_area.val());

    var construct = parseInt(el_construct.val());
    var construct_area = parseFloat(el_construct_area.val());
    var unit_construct_price = parseInt(el_unit_construct_price.val());
    var construct_price = parseFloat(el_construct_price.val());

    var land_price = parseFloat(el_land_price.val());
    var land_sqm_price = parseFloat(el_land_sqm_price.val());
    var land_recognized_sqm_price = parseFloat(el_land_recognized_sqm_price.val());

    var price = parseFloat(el_price.val());
    var price_type = parseInt(el_price_type.val());

    width = isNaN(width) ? 0 : width;
    length = isNaN(length) ? 0 : length;
    area = isNaN(area) ? 0 : area;
    cut_area = isNaN(cut_area) ? 0 : cut_area;
    recognized_area = isNaN(recognized_area) ? 0 : recognized_area;

    construct = isNaN(construct) ? 0 : construct;
    construct_area = isNaN(construct_area) ? 0 : construct_area;
    unit_construct_price = isNaN(unit_construct_price) ? 0 : unit_construct_price;
    construct_price = isNaN(construct_price) ? 0 : construct_price;

    land_price = isNaN(land_price) ? 0 : land_price;
    land_sqm_price = isNaN(land_sqm_price) ? 0 : land_sqm_price;
    land_recognized_sqm_price = isNaN(land_recognized_sqm_price) ? 0 : land_recognized_sqm_price;

    price = isNaN(price) ? 0 : price;
    price_type = isNaN(price_type) ? 0 : price_type;

    var price_full = 0;
    price_full = price_type == 1 ? (price) : (price * 1000);

    area = parseFloat(width * length);
    el_area.val(round_digit(area));

    recognized_area = parseFloat(area - cut_area);
    el_recognized_area.val(round_digit(recognized_area));

    construct_area = parseFloat(construct * recognized_area);
    el_construct_area.val(round_digit(construct_area));

    construct_price = parseFloat(construct_area * unit_construct_price);
    el_construct_price.val(round_digit(construct_price));

    land_price = parseFloat(price_full - construct_price);
    el_land_price.val(round_digit(land_price, 1));

    land_sqm_price = parseFloat(land_price / area);
    el_land_sqm_price.val(round_digit(land_sqm_price, 1));

    land_recognized_sqm_price = parseFloat(land_price / recognized_area);
    el_land_recognized_sqm_price.val(round_digit(land_recognized_sqm_price, 1));
}

function round_digit(num, digit) {
    if (digit === undefined)
        digit = 10;

    return Math.round(num * digit) / digit;
}

$('.calculate_input').change(function () {
    calculator_area();
});