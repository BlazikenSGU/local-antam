<script id="template_variation" type="x-tmpl-mustache">
<div class="row selected_variation" id="selected_variation_@{{variation_id}}" data-id="@{{variation_id}}">
    <div class="col-md-3">
        <div class="form-group">
        @{{variation_name}}
        </div>
    </div>

    <div class="col-md-9">
        <div class="form-group">
            <select class="form-control select2 select-variation-value"
            onchange="Variation.create_product_variation();"
            id="value-selection-@{{variation_id}}"
            data-variation-id="@{{variation_id}}"
            name="variation_values[@{{variation_id}}]"
            multiple="multiple">
            </select>
        </div>
    </div>
</div>
</script>
