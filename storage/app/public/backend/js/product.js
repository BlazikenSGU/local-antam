//init select2
$('.select2').select2();

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

//get province
$('#form-filter .select_province').select2({
    minimumInputLength: 1,
    ajax: {
        url: LOCATION_PROVINCE_URL,
        dataType: 'json',
        method: 'get',
        data: function (params) {
            return {
                name: $.trim(params.term),
                t: 'callback'
            };
        },
        processResults: function (data) {
            var r = $.map(data.data, function (obj) {
                obj.text = obj.text || obj.name;

                return obj;
            });
            return {
                results: r
            };
        },
        cache: true
    }
});

//get district
$('#form-filter .select_district').select2({
    minimumInputLength: 1,
    ajax: {
        url: LOCATION_DISTRICT_URL,
        dataType: 'json',
        method: 'get',
        data: function (params) {
            return {
                name: $.trim(params.term),
                t: 'callback'
            };
        },
        processResults: function (data) {
            var r = $.map(data.data, function (obj) {
                obj.text = obj.text || obj.name;

                return obj;
            });
            return {
                results: r
            };
        },
        cache: true
    }
});

//get ward
$('#form-filter .select_ward').select2({
    minimumInputLength: 1,
    ajax: {
        url: LOCATION_WARD_URL,
        dataType: 'json',
        method: 'get',
        data: function (params) {
            return {
                name: $.trim(params.term),
                t: 'callback'
            };
        },
        processResults: function (data) {
            var r = $.map(data.data, function (obj) {
                obj.text = obj.text || obj.name;

                return obj;
            });
            return {
                results: r
            };
        },
        cache: true
    }
});

//get street
$('#form-filter .select_street').select2({
    minimumInputLength: 2,
    ajax: {
        url: LOCATION_STREET_URL,
        dataType: 'json',
        method: 'get',
        data: function (params) {
            return {
                name: $.trim(params.term),
                t: 'callback'
            };
        },
        processResults: function (data) {
            var r = $.map(data.data, function (obj) {
                obj.text = obj.text || obj.name;

                return obj;
            });
            return {
                results: r
            };
        },
        cache: true
    }
});