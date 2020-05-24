<tr class="item-price-box" data-id="{%spec_key%}">
    <td><div id="spec-img-{%spec_key%}" class="spec_img"></div></td>
    <td>
        <textarea name="spec_des"
                  class="form-control"
                  onchange="priceItemChanged(\'{%spec_key%}\',\'spec_des\',$(this));" >{%spec_des%}</textarea>
    </td>
    <td>
        <input name="original_price"
               class="form-control"
               value="{%original_price%}"
               onchange="priceItemChanged(\'{%spec_key%}\',\'original_price\',$(this));"
               onkeyup="inputNumCheck($(this))"
               onblur="inputNumCheck($(this))" />
    </td>
    <td>
        <input name="goods_price" 
               class="form-control" 
               value="{%goods_price%}" 
               onchange="priceItemChanged(\'{%spec_key%}\',\'goods_price\',$(this));" 
               onkeyup="inputNumCheck($(this))" 
               onblur="inputNumCheck($(this))" />
    </td>
    <td>
        <input 
            name="store_count" 
            class="form-control" 
            value="{%store_count%}" 
            onchange="priceItemChanged(\'{%spec_key%}\',\'store_count\',$(this));" 
            onkeyup="inputNumCheck($(this))" 
            onblur="inputNumCheck($(this))" />
    </td>
    <td>
        <input 
            name="scene_num" 
            class="form-control" 
            value="{%scene_num%}" 
            onchange="priceItemChanged(\'{%spec_key%}\',\'scene_num\',$(this));" 
            onkeyup="inputNumCheck($(this))" 
            onblur="inputNumCheck($(this))" />
    </td>
    <td></td>
</tr>