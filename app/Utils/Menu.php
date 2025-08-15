<?php

namespace App\Utils;

class Menu
{
    public static function get_menu($items, $class = 'dd-list')
    {
        $html = '';
        $html .= "<ol class='{$class}' id='menu-id'>";

        if (!empty($items)) {
            foreach ($items as $key => $value) {
                $icon = $value['status'] == 1 ? "mdi-eye" : "mdi-eye-off";

                $img_icon = $img_thumb = '';
                if (isset($value['icon']['file_src']))
                    $img_icon = '<img  src="' . $value['icon']['file_src'] . '" href="' . $value['icon']['file_src'] . '" height="30" class="image-popup-no-margins"/>';

                if (isset($value['thumbnail']['file_src']))
                    $img_thumb = '<img  src="' . $value['thumbnail']['file_src'] . '" href="' . $value['thumbnail']['file_src'] . '" height="30" class="image-popup-no-margins"/>';

                $html .= "<li class='dd-item' data-id='{$value['id']}'>
                               <div class='dd-handle'> {$value['name']}</div>
                               <div class='ddmenu-right button-group'>
                                    {$img_icon}
                                    {$img_thumb}
                                    <a href='javascript:;' class='status-button btn btn-xs btn-outline-primary' data-id='{$value['id']}'>
                                        <i class='mdi {$icon}'> </i>
                                    </a>

                                    <a href='" . route('backend.menu.edit', $value['id']) . "' class='edit-button btn btn-xs btn-outline-info' data-id='{$value['id']}'>
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    <a href='javascript:;' class='del-button btn btn-xs btn-outline-danger' data-id='{$value['id']}'>
                                        <i class='fa fa-trash'></i>
                                    </a>
								</div>";
                if (array_key_exists('child', $value)) {
                    $html .= self::get_menu($value['child'], 'child');
                }
                $html .= "</li>";
            }
        }
        $html .= "</ol>";
        return $html;
    }

    public static function buildTree($data = array(), $parent = 0)
    {
        $branch = array();
        if (!empty($data)) {
            foreach ($data as $row) {
                if ((int)$row['parent_id'] == $parent) {
                    $aX = self::buildTree($data, $row['id']);
                    if ($aX) {
                        $row['child'] = $aX;
                    }
                    $branch[$row['id']] = $row;
                }
            }
        }
        return $branch;
    }

    public static function build_array($jsonArray, $parentID = 0)
    {
        $return = array();
        foreach ($jsonArray as $subArray) {
            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = self::build_array($subArray['children'], $subArray['id']);
            }

            $return[] = array('id' => $subArray['id'], 'parent_id' => $parentID);
            $return = array_merge($return, $returnSubSubArray);
        }
        return $return;
    }

    public static function build_select_tree($data, $parent = 0, $text = "", $select = array(), &$html = '')
    {
        foreach ($data as $k => $value) {
            if ((int)$value['parent_id'] == $parent) {
                $id = $value['id'];
                if ($select != 0 && in_array($id, $select)) {
                    $html .= "<option value='$value[id]' selected='selected'>" . $text . $value['name'] . "</option>";
                } else {
                    $html .= "<option value='$value[id]'>" . $text . $value['name'] . "</option>";
                }
                unset($data[$k]);
                self::build_select_tree($data, $id, $text . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#124;&#95;&#95;", $select, $html);
            }
        }
        return $html;
    }
}
