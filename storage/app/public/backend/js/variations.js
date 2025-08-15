var Variation = {
    initialised: false,
    processing: false,
    selected_variation: [],
    init: function () {

        if (!this.initialised) {
            this.initialised = true;
        } else {
            return;
        }

        this.init_select2();
        this.select_variation();
        this.delete_variation();
        this.add_product_variation();
        this.product_variation_image();
        this.add_variation();
    },
    isEmpty: function (obj) {
        for (var key in obj) {
            if (obj.hasOwnProperty(key))
                return false;
        }
        return true;
    },
    init_select2: function () {
        $('.select-variation').select2({maximumSelectionLength: 3, tags: false});
        $('.select-variation-value').select2({tags: true});
    },
    get_showed_variation: function () {
        var selected = [];
        $('.selected_variation').each(function (i, obj) {
            var id = parseInt($(obj).data('id'));
            if ($.inArray(id, selected) == -1) {
                selected.push(id);
            }
        });
        return selected;
    },
    select_variation: function () {
        $('.select-variation').change(function () {

            var selected_variation_id = Variation.get_showed_variation();

            var variation_ids = $('.select-variation').val();

            var template = $('#template_variation').html();

            $.each(variation_ids, function (index, value) {
                if ($.inArray(parseInt(value), selected_variation_id) == -1) {
                    var variation_name = Variation.get_variation_name(parseInt(value));
                    var rendered = Mustache.render(template, {
                        variation_id: value,
                        variation_name: variation_name,
                    });
                    $('.option-list').append(rendered);
                    Variation.init_select2();
                    Variation.get_variation_value(value);
                }
            });

            selected_variation_id = Variation.get_showed_variation();
            $.each(selected_variation_id, function (index, value) {
                if ($.inArray(value + '', variation_ids) == -1) {
                    $('#selected_variation_' + value).remove();
                }
            });

            Variation.select_variation_value();
        });
    },
    select_variation_value: function () {
        $('.select-variation-value').change(function () {
        })
    },
    create_product_variation: function () {
        var variation_value = [];

        $('.select-variation-value').each(function (i, obj) {
            var variation_id = $(obj).data('variation-id');
            var value = [];
            $(obj).find(":selected").each(function (k, v) {
                value.push({value_id: $(v).val(), value: $(v).text()});
            });
            variation_value.push({variation_id: variation_id, data: value});
        });

        //console.log(variation_value);
        var r = Variation.variation_combinations(variation_value);
        Variation.render_product_variation(r);
    },
    get_variation_name: function (variation_id) {
        var name = null;
        $.each(VARIATION_LIST, function (i, v) {
            if (parseInt(v.id) == variation_id) {
                name = v.name;
                return false;
            }
        })
        return name;
    },
    variation_combinations: function (variation_value) {
        var result = [[]];
        $.each(variation_value, function (i, v) {
            var variation_id = v.variation_id;
            var tmp = [];
            $.each(result, function (i2, v2) {
                $.each(v.data, function (i3, v3) {
                    tmp.push(v2.concat([{variation_id: variation_id, value: v3}]));
                })
            })
            result = tmp;
        })
        return result;

    },
    check_variation_id_exists: function (variation_value, variation_id) {
        var check = false;
        $.each(variation_value, function (index, value) {
            if (value.variation_id == variation_id) {
                check = true;
                return false;
            }
        })
        return check;
    },
    check_variation_value_exists: function (values, id) {
        var check = false;
        $.each(values, function (index, value) {
            if (value.value_id == id) {
                check = true;
                return false;
            }
        })
        return check;
    },
    get_variation_value: function (variation_id) {
        $.ajax({
            type: "GET",
            url: URL_VARIATION_VALUE,
            data: {variation_id: variation_id},
            cache: false,
            dataType: 'json',
            success: function (data) {
                $('#value-selection-' + variation_id).html('');
                $.each(data.r, function (index, value) {
                    $('#value-selection-' + variation_id).append('<option value="' + value.id + '">' + value.value + '</option>');
                });
            }
        });
    },
    render_product_variation: function (variation_combination) {
        var template = $('#template_product_variation').html();
        $('.product_variant_list').html('');
        $('.option-value').hide();

        $.each(variation_combination, function (index, value) {
            var combination = Variation.get_variation_combination_name(value);
            var rendered = Mustache.render(template, {
                product_variation_id: index,
                product_variation_name: combination.variation_value_names.join('/'),
                product_variation_combination: combination.variation_ids.join('|'),
                product_variation_value_combination: combination.variation_value_names.join('|'),
            });
            $('.product_variant_list').append(rendered);
            $('.option-value').show();
        });
    },
    get_variation_combination_name: function (variation_combination) {
        var variation_ids = [];
        var variation_value_ids = [];
        var variation_value_names = [];

        $.each(variation_combination, function (i, v) {
            variation_ids.push(v.variation_id);
            variation_value_ids.push(v.value.value_id);
            variation_value_names.push(v.value.value);
        });

        return {
            variation_ids: variation_ids,
            variation_value_ids: variation_value_ids,
            variation_value_names: variation_value_names
        };
    },
    delete_variation: function () {
        $(document).on('click', '.delete_variation', function () {
            var d = $(this);
            var agree = confirm('Bạn muốn xóa phiên bản này? Bạn không thể hoàn tác sau khi xóa!');
            if (agree == true) {
                var variation_id = d.data('id');
                var product_id = d.data('product_id');
                Variation.delete_variation_ajax([variation_id], product_id);
            }
        })
    },
    delete_variation_ajax: function (variation_ids, product_id) {
        $.ajax({
            type: "POST",
            url: URL_PRODUCT_VARIATION_DELETE,
            data: {product_variation_ids: variation_ids, product_id: product_id, _token: $('[name="_token"]').val()},
            cache: false,
            dataType: 'json',
            success: function (data) {
                if (data.e == 1) {
                    alert(data.r)
                } else {
                    $.each(variation_ids, function (i, v) {
                        $('#variation_id_' + v).remove();
                    })
                    Variation.show_select_variation();
                }
            }
        });
    },
    show_select_variation: function () {
        if ($('.product_variant_list_edit').children().length < 1) {
            $('.create_variation_form').removeClass('d-none');
            $('.edit_variation_form').addClass('d-none');
        } else {
            $('.create_variation_form').addClass('d-none');
            $('.edit_variation_form').removeClass('d-none');
        }
    },
    add_product_variation: function () {
        $('.add_product_variation').click(function () {
            $('#modal_variation').modal('show');
        });

        $('.form_add_product_variation').submit(function (e) {
            e.preventDefault();
            var data = $('.form_add_product_variation').serialize();

            $.ajax({
                type: "POST",
                url: URL_PRODUCT_VARIATION_ADD,
                data: data,
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        alert(data.r);
                        window.location.reload();
                    }
                }
            });
        });
    },
    product_variation_image: function () {
        $('.product_variation_images').click(function () {
            var product_variation_id = $(this).data('product-variation-id');
            var product_id = $(this).data('product-id');
            $.ajax({
                type: "GET",
                url: URL_PRODUCT_VARIATION_IMAGES,
                data: {product_variation_id: product_variation_id, product_id: product_id},
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        $('#modal_product_variation_image_content').html(data.r);
                        $('#modal_variation_images').modal('show');
                        sort_image_variation();
                    }
                }
            });
        });

        $(document).on('click', '.delete_product_variaton_image', function () {
            var d = $(this);
            var product_variation_id = $('.product_variation_images .product-variation-id').val();
            var product_id = $('.product_variation_images .product-id').val();
            var file_id = $(this).data('file_id');
            $.ajax({
                type: "POST",
                url: URL_PRODUCT_VARIATION_IMAGES_DELETE,
                data: {
                    product_variation_id: product_variation_id,
                    product_id: product_id,
                    file_id: file_id,
                    _token: $('[name="_token"]').val()
                },
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        d.parent().parent().remove();
                        $('tr#variation_id_' + product_variation_id).find('img').attr('src', data.i);
                    }
                }
            });
        });

        $(document).on('change', 'input#ProductVariationImage', function () {
            var product_variation_id = $('.product_variation_images .product-variation-id').val();
            var product_id = $('.product_variation_images .product-id').val();
            var max_size_allow = 2; //Mb
            var type = $(this).data('type');

            $(this.files).each(function (i, file) {

                var fd = new FormData();

                fd.append('product_id', product_id);
                fd.append('product_variation_id', product_variation_id);
                fd.append('image', file);
                fd.append('type', type);
                fd.append('_token', $('[name="_token"]').val());

                if (file.size > (1024000 * max_size_allow)) {
                    var msg = 'Kích thước file không được quá :max_size_allow';
                    alert(msg.replace(':max_size_allow', max_size_allow));
                } else {
                    Variation.upload_image_product_variation(fd);
                }

                delete fd;
            });
        });

        $(document).on('click', '.save_product_variaton_image_sort', function () {

            var product_variation_id = $('.product_variation_images .product-variation-id').val();
            var product_id = $('.product_variation_images .product-id').val();

            var file_ids = [];
            var el = $(document).find('.product_variation_thumb_img a');
            $.each(el, function (i, v) {
                file_ids.push($(v).data('file_id'));
            });

            $.ajax({
                type: "POST",
                url: URL_PRODUCT_VARIATION_IMAGES_SORT,
                data: {
                    product_variation_id: product_variation_id,
                    product_id: product_id,
                    file_ids: file_ids,
                    _token: $('[name="_token"]').val()
                },
                cache: false,
                dataType: 'json',
                success: function (data) {
                    alert("Lưu thành công!")
                }
            });
        })
    },
    upload_image_product_variation: function (formData) {
        $.ajax({
            url: URL_PRODUCT_VARIATION_IMAGES_UPLOAD,
            type: "POST",
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.e) {
                    alert(data.r);
                } else {
                    $('.product_variation_thumb_img').append(data.r);

                    $('tr#variation_id_' + formData.get('product_variation_id')).find('img').attr('src', data.i);
                }
                $('#ProductVariationImage').val('');
                sort_image_variation();
            }
        });
    },
    add_variation: function () {
        $('.add_variation').click(function () {
            $('#modal_add_variation').modal('show');
        });

        $('.form_add_variation').submit(function (e) {
            e.preventDefault();
            var data = $('.form_add_variation').serialize();

            $.ajax({
                type: "POST",
                url: URL_VARIATION_ADD,
                data: data,
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        alert('Thao tác thành công!');

                        VARIATION_LIST.push(data.v);
                        var newOption = new Option(data.r.text, data.r.id, false, false);
                        $('.select-variation').append(newOption).trigger('change');

                        $('.select-variation').select2('destroy').select2({maximumSelectionLength: 3, tags: false});
                        $('#modal_add_variation').modal('hide');
                    }
                }
            });
        });
    }
};

String.prototype.ucfirst = function () {
    return this.charAt(0).toUpperCase() + this.slice(1)
}

Variation.init();
