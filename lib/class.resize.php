<?
function image_resize_return($path,$view_path,$width,$height)
{

    $_return_image = "";
    $imgsize = getimagesize($path);

    if($imgsize[0]) {
        if($width < 1 && $height < 1 ){
            $_return_image = "<img src='" . $view_path . "' border='0' style='max-width:100%;'>";
        }else {
            if ($imgsize[0] > $width || $imgsize[1] > $height) {
                // 가로길이가 가로limit값보다 크거나 세로길이가 세로limit보다 클경우
                if ($imgsize[0] < $imgsize[1]) {
                    // 가로가 세로보다 클경우
                    $sumw = ($height) / $imgsize[1];
                    $img_width = ceil(($imgsize[0] * $sumw));
                    $img_height = $height;
                } else {
                    // 세로가 가로보다 클경우
                    $sumh = ($width) / $imgsize[0];
                    $img_height = ceil(($imgsize[1] * $sumh));
                    $img_width = $width;
                }
                $_return_image = "<img src='" . $view_path . "' border='0'  style='max-width:100%;'>";
            } else {
                // limit보다 크지 않는 경우는 원본 사이즈 그대로.....
                $img_width = $imgsize[0];
                $img_height = $imgsize[1];
                $_return_image = "<img src='" . $view_path . "' border='0' style='max-width:100%;'>";
            }
        }
    }

    return $_return_image;
}
?>