<?php
include_once("./_common.php");
include_once(G5_LIB_PATH.'/PHPExcel.php');

if (!$is_admin =="super")
    alert_close("최고 관리자 영역 입니다.");

function column_char($i) { return chr( 65 + $i ); }


$headers = array('등록일시', '아이디', '이름', '병원명', '내용',);
$widths  = array(20, 15, 15, 50, 100);
$header_bgcolor = 'FFABCDEF';
$last_char = column_char(count($headers) - 1);

$write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
$member_table = $g5['member_table']; // 게시판 테이블 전체이름

$sql_common = " from $write_table t1
LEFT JOIN $member_table t2 ON t1.mb_id = t2.mb_id";
$sql_search = " where wr_parent = '$wr_id' and wr_is_comment = 1";
$sst = "wr_comment, wr_comment_reply";
$sod = "desc";
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($total_count > 0) {
    $qry = sql_query("select * {$sql_common} {$sql_search} {$sql_order}");

    for($i=1; $row=sql_fetch_array($qry); $i++) {
        $rows[] =
            array(
                $row[wr_datetime],
                $row[mb_id],
                $row[wr_name],
                $row[mb_11],
                $row[wr_content]
            );
    }

    $data = array_merge(array($headers), $rows);

    $TD_COLOR = array(

        //배경색 설정
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb'=>'888888'),
        ),

        //글자색 설정
        'font' => array(
            'bold' => 'true',
            'size' => '12',
            'color' => array('rgb'=>'FFFFFF')
        ),

        //테두리 설정
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('argb'=>'000000')
            )
        ),

    );

    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
    $excel->getActiveSheet()->getStyle("A1:${last_char}1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //가운데 정렬
    $excel->getActiveSheet()->getStyle("A1:${last_char}1")->applyFromArray($TD_COLOR);
    $excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
    foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
    $excel->getActiveSheet()->fromArray($data,NULL,'A1');

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"comment-".date("ymdhis", time()).".xls\"");
    header("Cache-Control: max-age=0");

    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    $writer->save('php://output');
}else{
    alert_close("다운받을 댓글이 없습니다.");
}
?>