<?
include_once('./_common.php');

/***/switch ($_GET[mode]){

    case "search":

        $query = "select it_id,it_name from {$g5['g5_shop_item_table']} where it_name like '%$_GET[wd]%' order by it_id desc";
        $res = sql_query($query);
        $num = sql_num_rows($res);

        $search_goods = "<table width='100%' cellspacing='0' cellpadding='0'>";
        $search_goods .= "<tr>";
        $search_goods .= "<td style='width:15%;height: 50px;text-align: center;'>이미지</td><td style='width:70%;text-align: center'>상품명</td><td style='width:15%;height: 50px;text-align: center'>선택</td>";
        $search_goods .= "</tr>";

        if($num > 0) {

            for ($i = 0; $row = sql_fetch_array($res); $i++) {
                $view_img = get_it_image($row['it_id'], 50, 50);
                $view_img = str_replace( "\"","", $view_img );


                $search_goods .= "<tr>";
                $search_goods .= "<td style='width:15%;height: 50px;text-align: center;'>" . $view_img . "</td><td style='width:70%;text-align: center'>" . $row[it_name] . "</td><td style='width:15%;height: 50px;text-align: center'><input type='button' class='btn btn-waring btn-sm' onclick='append_goods(\"$row[it_name]\",\"$row[it_id]\",\"$view_img\")' value='선택'></td>";
                $search_goods .= "</tr>";
            }

        }else{
            $search_goods .= "<tr><td colspan='3' style='height: 100px;text-align: center;font-weight: bold;'>검색된 데이터가 없습니다</td></tr>";
        }

        $search_goods .= "</table>";
        echo $search_goods;

        break;

    /***/}