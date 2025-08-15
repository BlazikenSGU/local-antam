<script id="template_product_variation" type="x-tmpl-mustache">
<tr>
    <td>
      <input type="checkbox" value="1" checked="checked"
             id="select_@{{product_variation_id}}"
             name="product_variations[@{{product_variation_id}}][selected]"/>
        <label for="select_@{{product_variation_id}}"
               class="filled-in chk-col-red"></label>

      <input type="hidden" value="@{{product_variation_combination}}" name="product_variations[@{{product_variation_id}}][variation_combination]"/>
      <input type="hidden" value="@{{product_variation_value_combination}}" name="product_variations[@{{product_variation_id}}][variation_value_combination]"/>
      <input type="hidden" value="@{{product_variation_name}}" name="product_variations[@{{product_variation_id}}][name]"/>
    </td>

    <td><span class="color_green">@{{product_variation_name}}</span></td>

    <td>
      <input type="number" min="0" name="product_variations[@{{product_variation_id}}][price]"/>
    </td>

    <td>
      <input type="text" class="next-input inline" name="product_variations[@{{product_variation_id}}][product_code]"/>
    </td>

    <td>
      <input type="checkbox" class="next-input inline filled-in" id="inventory_management_@{{product_variation_id}}"
             name="product_variations[@{{product_variation_id}}][inventory_management]"/>
        <label for="inventory_management_@{{product_variation_id}}"
               class="chk-col-red"></label>
    </td>

    <td>
        <input type="number" class="next-input inline" value="0"
               name="product_variations[@{{product_variation_id}}][inventory]" min="0"/>
    </td>

    <td>
        <input type="checkbox" class="next-input inline filled-in"
               id="inventory_policy_@{{product_variation_id}}"
               name="product_variations[@{{product_variation_id}}][inventory_policy]"/>
        <label for="inventory_policy_@{{product_variation_id}}"
               class="chk-col-red"></label>
    </td>
</tr>
</script>
