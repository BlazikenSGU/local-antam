<?php

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string, $encoding = 'utf8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}

if (!function_exists('mb_ucwords')) {
    function mb_ucwords($text)
    {
        $text = mb_strtolower($text);
        $upper_words = array();
        $words = explode(" ", $text);

        foreach ($words as $word) {
            $upper_words[] = mb_ucfirst($word);
        }
        return implode(" ", $upper_words);
    }
}

if (!function_exists('timeToHumanDate')) {
    function timeToHumanDate($time, $options = array('type' => 'vn', 'title' => '', 'second' => 'giây', 'seconds' => 'giây', 'minute' => 'phút', 'minutes' => 'phút', 'hour' => 'giờ', 'hours' => 'giờ', 'date' => 'ngày', 'dates' => 'ngày', 'week' => 'tuần', 'weeks' => 'tuần', 'month' => 'tháng', 'months' => 'tháng', 'year' => 'năm', 'years' => 'năm'))
    {
        //Get today
        $today = time();

        //Get start time of today
        $start = mktime(0, 0, 1, date('m', $today), date('d', $today), date('Y', $today));

        //Get end time of today
        $end = mktime(23, 59, 59, date('m', $today), date('d', $today), date('Y', $today));

        //Get String Week
        $strWeekCurrent = date('W', $today) . date('Y', $today);

        //Get String Week Input
        $strWeekInput = date('W', $time) . date('Y', $time);

        //Lower string type
        $options['type'] = strtolower($options['type']);

        //Check timestamp
        if (($start <= $time) && ($time <= $end)) {
            $options['title'] = 'Hôm nay, lúc ' . date('H:i', $time);
        } elseif ((($start - 24 * 3600 + 1) <= $time) && ($time < ($start - 1))) {
            $options['title'] = 'Hôm qua, lúc ' . date('H:i', $time);
        } elseif ($strWeekCurrent == $strWeekInput) {
            //Get week number
            $weekNumber = date('N', $time);

            //Check number to add string
            switch ($weekNumber) {
                case 1:
                    $options['title'] = 'Thứ hai, lúc ';
                    break;
                case 2:
                    $options['title'] = 'Thứ ba, lúc ';
                    break;
                case 3:
                    $options['title'] = 'Thứ tư, lúc ';
                    break;
                case 4:
                    $options['title'] = 'Thứ năm, lúc ';
                    break;
                case 5:
                    $options['title'] = 'Thứ sáu, lúc ';
                    break;
                case 6:
                    $options['title'] = 'Thứ bảy, lúc ';
                    break;
                case 7:
                    $options['title'] = 'Chủ nhật, lúc ';
                    break;
                default:
                    break;
            }

            //Add hours
            $options['title'] .= date('H:i', $time);
        } else {
            $options['title'] = date('d/m', $time) . ' lúc ' . date('H:i', $time);
        }

        //Return data
        return $options['title'];
    }
}

if (!function_exists('product_link')) {
    function product_link($slug, $id, $product_type_id)
    {
        $view_data = \View::getShared();
        $all_category = $view_data['all_category'];

        return $product_type_id ? $all_category[$product_type_id]['link'] . '/' . $slug : '';
    }
}

if (!function_exists('format_order_status')) {
    function format_order_status($status)
    {
        return match ($status) {
            'ready_to_pick' => 'Chờ lấy hàng',
            'picking' => 'Đang lấy hàng',
            'cancel' => 'Đơn hủy',
            'money_collect_picking' => 'Đang thu tiền người gửi',
            'picked' => 'Lấy hàng thành công',
            'storing' => 'Nhập kho',
            'transporting' => 'Đang trung chuyển',
            'sorting' => 'Đang phân loại',
            'delivering' => 'Đang giao hàng',
            'money_collect_delivering' => 'Nhân viên đang thu tiền',
            'delivered' => 'Giao hàng thành công',
            'delivery_fail' => 'Giao hàng thất bại',
            'waiting_to_return' => 'Chờ xác nhận giao lại',
            'return' => 'Chuyển hoàn',
            'return_transporting' => 'Đang luân chuyển hàng trả',
            'return_sorting' => 'Đang phân loại hàng trả',
            'returning' => 'Đang hoàn hàng',
            'return_fail' => 'Trả hàng thất bại',
            'returned' => 'Trả hàng thành công',
            'exception' => 'Hàng ngoại lệ',
            'damage' => 'Hàng hư hỏng',
            'lost' => 'Hàng thất lạc',
            default => 'Không xác định',
        };
    }
}


if (!function_exists('format_name_bank')) {
    function format_name_bank()
    {
        return  [

            1 => 'TPB-TPBank-Ngan hang TMCP Tien Phong',
            3    => 'ABC-Ngan hang Agricultural Bank of China Limited',
            4    => 'ACB-Ngan hang TMCP A Chau',
            2    => 'ABB-Ngan hang TMCP An Binh',
            5    => 'Agribank-Ngan hang Nong nghiep va Phat trien nong thon',
            6    => 'ANZ-Ngan hang ANZ Viet Nam',
            7    => 'BC-Bank of China (Hong Kong) Limited',
            8    => 'BIDC-Ngan hang TMCP Dau tu va phat trien Campuchia',
            9    => 'BIDV-Ngan hang TMCP Dau tu va phat trien Viet Nam',
            10    => 'BNP-Ngan hang BNP Paribas CN Ha Noi',
            11    => 'BNP-Ngan hang BNP Paribas CN Ho Chi Minh',
            12    => 'BOC-Bank of Communications',
            13    => 'BPCE-Ngan hang BPCE',
            14    => 'BVB-Ngan hang TMCP Bao Viet',
            15    => 'CB-NHTM TNHH MTV Xay dung Viet Nam',
            16    => 'CITI-Citi Bank CN Ha Noi',
            17    => 'CITI-Citi Bank CN Ho Chi Minh',
            18 =>    'COOPBANK-Ngan hang Hop tac xa Viet Nam',
            19    => 'CTBC-Ngan hang TNHH CTBC CN TP Ho Chi Minh',
            20    => 'DB-DEUTSCHE BANK',
            21    => 'DBS-Ngan hang DBS',
            22    => 'DongA-Ngan hang TMCP Dong A',
            23 =>    'Exim-Ngan hang TMCP XNK Viet Nam',
            24 =>    'FCB-First Commercial Bank CN Ha Noi',
            25 =>    'FCB-First Commercial Bank CN TP Ho Chi Minh',
            26 =>    'GBBANK-NHTM TNHH MTV Dau khi Toan Cau Hoi So Chinh',
            27 =>    'HD-Ngan hang TMCP Phat trien TP Ho Chi Minh',
            28    => 'HLO-Ngan hang HongLeong Viet Nam',
            29 =>    'HNCB-Hua Nan Commercial Bank',
            30 =>    'HSBC-Ngan hang TNHH Mot thanh vien HSBC Viet Nam',
            31    => 'IBK-Industrial Bank of Korea',
            32    => 'ICB-Industrial and Commercial Bank of China',
            33    => 'IVB-Indovina Bank',
            34 =>    'JP-Ngan hang JPMorgan Chase',
            35 =>    'KEB-Ngan hang KEB Hana CN Ha Noi',
            36    => 'KEB-Ngan hang KEB Hana CN TP Ho Chi Minh',
            37    => 'KIENLONG-Ngan hang TMCP Kien Long',
            38    => 'KMB-Ngan hang Kookmin',
            39    => 'LPB-NH TMCP Loc Phat Viet Nam',
            40    => 'Maybank-Malayan Banking Berhad TP HCM',
            41    => 'MB-Ngan hang TMCP Quan doi',
            42    => 'MICB-Mega ICBC Bank TP HCM',
            43 =>    'MSB-Ngan hang TMCP Hang Hai Viet Nam',
            44 =>    'MUFG-Ngan hang TNHH MUFG - CN Ha Noi',
            45 =>    'MUFG-Ngan hang TNHH MUFG - CN HCM',
            46    => 'MZH-Mizuho Bank CN Ha Noi',
            47 =>    'MZH-Mizuho Bank CN TP Ho Chi Minh',
            48 =>    'NAB-Ngan hang TMCP Nam A',
            49 =>    'NASB-Ngan hang TMCP Bac A',
            50    => 'NCB-Ngan hang TMCP Quoc dan',
            51    => 'OCBC-Oversea - Chinese banking TP HCM',
            52 =>    'OCB-Ngan hang TMCP Phuong Dong HCM',
            53    => 'Ocean-NH TM TNHH MTV Dai Duong CN Ha Noi',
            54    => 'PGB-Ngan Hang TMCP Thinh vuong va Phat trien CN Ha noi',
            55    => 'PVCom-Ngan hang TMCP Dai chung Viet Nam',
            56    => 'Sacom-Ngan hang TMCP Sai Gon thuong tin',
            57    => 'Saigonbank-Ngan hang TMCP Sai Gon cong thuong',
            58    => 'SCB-Ngan hang TMCP Sai Gon',
            59 =>    'SeAbank-Ngan hang TMCP Dong Nam A',
            60 =>    'SHB-Ngan hang TMCP Sai Gon - Ha Noi',
            61 =>    'Shinhan-Ngan hang TNHH MTV Shinhan',
            62 =>    'SMBC-Sumitomo Mitsui Banking Corporation',
            63    => 'SPB-Ngan hang SinoPac CN TP HCM',
            64    => 'STANDARD-Ngan hang STANDARD CHARTERED',
            65    => 'TCB-Ngan hang TMCP Ky thuong Viet Nam',
            66    => 'TFC-Ngan hang thuong mai Taipei Fubon CN Ha Noi',
            67    => 'TFC-Ngan hang thuong mai Taipei Fubon CN TP Ho Chi Minh',
            68    => 'UOB-Ngan hang TNHH MTV United Overseas Bank Viet Nam',
            69    => 'VAB-Ngan hang TMCP Viet A',
            70    => 'VBSP-NH Chinh sach xa hoi',
            71    => 'VCB-Ngan hang TMCP Ngoai thuong Viet Nam',
            72    => 'VDB-Ngan hang phat trien Viet Nam',
            73    => 'VIB-Ngan hang TMCP Quoc te',
            74    => 'VID-NH TNHH MTV Public Viet Nam',
            75    => 'VietBank-Ngan hang TMCP Viet Nam Thuong Tin',
            76    => 'Vietcapital-Ngan hang TMCP Ban Viet',
            77 =>    'Vietin-Ngan hang TMCP Cong thuong Viet Nam',
            78 =>    'VPBank-Ngan hang TMCP Viet Nam Thinh Vuong',
            79 =>    'VRB-Ngan hang Lien doanh Viet Nga',
            80 =>    'Woori-Ngan hang Woori Viet Nam',
        ];
    }
}
