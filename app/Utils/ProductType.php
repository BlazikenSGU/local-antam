<?php

namespace App\Utils;

class ProductType
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
                                    
                                    <a href='javascript:;' class='edit-button btn btn-xs btn-outline-info' data-id='{$value['id']}'>
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

    public static function treeToArray($tree, &$data = [])
    {
        foreach ($tree as $k => $v) {

            $child = isset($v['child']) ? $v['child'] : [];

            unset($v['child']);

            $v['link'] = $v['link'] . '.' . $v['id'];

            $data[$v['id']] = $v;

            self::treeToArray($child, $data);
        }
        return $data;
    }

    public static function buildTree($data = array(), $parent = 0)
    {
        $branch = array();
        if (!empty($data)) {
            foreach ($data as $row) {
                $row['link'] = $row['slug'];
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

    public static function get_all_child_categories($allCate, $cat_id, $all_child = array())
    {
        if (empty($allCate)) {
            return array();
        }
        foreach ($allCate as $cat) {
            if ($cat['parent_id'] == $cat_id) {
                $all_child[] = $cat['id'];
                $all_child = self::get_all_child_categories($allCate, $cat['id'], $all_child);
            }
        }

        return $all_child;
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

    public static function build_select_tree($data, $parent = 0, $text = "", $select = array(), &$html = '', $last_name = '')
    {
        foreach ($data as $k => $value) {

            if ((int)$value['parent_id'] == $parent) {
                $id = $value['id'];
                $value['name'] = $last_name . ' > ' . self::mb_ucfirst($value['name']);

                if ($select != 0 && in_array($id, $select)) {
                    $html .= "<option value='$value[id]' selected='selected'>" . $text . $value['name'] . "</option>";
                } else {
                    $html .= "<option value='$value[id]'>" . $text . $value['name'] . "</option>";
                }

                unset($data[$k]);
                self::build_select_tree($data, $id, $text . "&nbsp;&nbsp;-", $select, $html, $value['name']);
            }
        }
        return $html;
    }

    public static function mb_ucfirst($string, $encoding = 'utf8')
    {
        $string = mb_strtolower($string);
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    public static function sidebar_menu_category($tree_categories, $categories, $category_id = 0)
    {
        $html = '';
        foreach ($tree_categories as $cate) {
            if (!isset($cate['child'])) {
                $active = $category_id == $cate['id'] ? 'active' : '';
                $html .= "<li class='{$active}'>
                        <a href='{$cate['link']}' title='{$cate['name']}'>
                            {$cate['name']}
                        </a>
                    </li>";
            } else {

                $parent_id = self::find_root_parent($categories, $category_id);

                $a_active = ($category_id == $cate['id'] || (isset($categories[$category_id]['parent_id']) && $categories[$category_id]['parent_id'] == $cate['id'])) ? 'active' : '';
                $haschild_active = ($category_id == $cate['id'] || (isset($categories[$category_id]['parent_id']) && $categories[$category_id]['parent_id'] == $cate['id']) || $parent_id == $cate['id']) ? 'haschild-active' : '';

                $html .= "<li class='haschild {$haschild_active}'>
                        <a href='{$cate['link']}' title='{$cate['name']}' class='{$a_active}'>
                            {$cate['name']}
                        </a>";
                $html .= "<ul class='subcategory'>";
                $html .= self::sidebar_menu_category($cate['child'], $categories, $category_id);
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        return $html;
    }

    public static function find_root_parent($categories, $category_id)
    {
        if (empty($category_id) || !isset($categories[$category_id]['parent_id']))
            return 0;

        if ($categories[$category_id]['parent_id'] == 0) {
            return $category_id;
        } else {
            return self::find_root_parent($categories, $categories[$category_id]['parent_id']);
        }
    }
}


