<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_CONTENTS_SKIN_URL.'/style.css">', 0);
?>

<!-- 상품진열 30 시작 { -->
<?php
for ($i=1; $row=sql_fetch_array($result); $i++) {
    if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i%$this->list_mod == 0) $cct_last = ' last_li'; // 줄 마지막
        else if ($i%$this->list_mod == 1) $cct_last = ' cct_clear'; // 줄 첫번째
        else $cct_last = '';
    } else { // 1줄 이미지 : 1개
        $cct_last = ' cct_clear';
    }


    if ($i == 1) {
        if ($this->css) {
            echo "<ul id=\"cmt_{$this->type}\" class=\"{$this->css}\">\n";
        } else {
            echo "<ul id=\"cmt_{$this->type}\" class=\"cct cct_30\">\n";
        }
    }

    echo "<li class=\"cct_li{$cct_last}\" style=\"width:{$this->img_width}px\">\n";

    if ($this->href) {
        echo "<div class=\"cct_img cct_img_rel\"><p class=\"goods_tit\"><a href=\"{$this->href}{$row['it_id']}\" class=\"cct_a\">\n";
    }

    if ($this->view_it_img) {
        echo cm_get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
    }

    if ($this->href) {
        echo "<span></span></a></div>\n";
    }

    if ($this->view_it_icon) {
        echo "<div class=\"sct_icon\">".cm_item_icon($row)."</div>\n";
    }

    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;".stripslashes($row['it_id'])."&gt;</div>\n";
    }

    if ($this->href) {
        echo "<div class=\"cct_tit\"><span class=\"goods_tit\"><a href=\"{$this->href}{$row['it_id']}\" class=\"cct_span_a\">\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name'])."\n";
    }
    if ($this->href) {
        echo "</a></span>\n";
    }


    if ($this->view_it_basic && $row['it_basic']) {
        echo "<span class=\"goods_basic\">".stripslashes($row['it_basic'])."</span>\n";
    }

    echo "</div>\n";


    if ($this->view_it_price || $this->view_it_sum_qty || $this->view_it_wish_qty) {
        echo "<div class=\"cct_price\">\n";
        if($this->view_it_wish_qty)
            echo "<span class=\"wish_c\">찜 ".number_format($row['it_wish_qty'])."</span>\n";
        if($this->view_it_sum_qty)
            echo "<span class=\"buy_c\">구매 ".number_format($row['it_sum_qty'])."</span>\n";
        echo "<span class=\"goods_price\">" .cm_display_price(cm_get_price($row), $row['it_tel_inq'])." </span>\n";
        echo "</div>\n";
    }

    if ($this->view_sns) {
        $sns_top = $this->img_height + 10;
        $sns_url  = G5_CONTENTS_URL.'/item.php?it_id='.$row['it_id'];
        $sns_title = get_text($row['it_name']).' | '.get_text($config['cf_title']);
        echo "<div class=\"cct_sns\" style=\"top:{$sns_top}px\">";
        echo cm_get_sns_share_link('facebook', $sns_url, $sns_title, G5_CONTENTS_SKIN_URL.'/img/sns_fb_s.png');
        echo cm_get_sns_share_link('twitter', $sns_url, $sns_title, G5_CONTENTS_SKIN_URL.'/img/sns_twt_s.png');
        echo cm_get_sns_share_link('googleplus', $sns_url, $sns_title, G5_CONTENTS_SKIN_URL.'/img/sns_goo_s.png');
        echo "</div>\n";
    }

    echo "</li>\n";
}

if ($i > 1) echo "</ul>\n";

if($i == 1) echo "<p class=\"cct_noitem\">등록된 상품이 없습니다.</p>\n";
?>

<script>
(function($) {
    var methods = {
        init: function(option)
        {
            if(this.length < 1)
                return false;

            var $anc = this.find("li a");

            // 기본 설정값
            var settings = $.extend({
                duration: 400
            }, option);

            $anc.on("mouseover focusin", function() {
                $(this).find("img").stop().animate({ opacity: 0.7 }, settings.duration);
                $(this).find("span").stop().animate({ bottom: "0px" }, settings.duration);
            });

            $anc.on("mouseout focusout", function() {
                $(this).find("img").stop().animate({ opacity: 1 }, settings.duration);
                $(this).find("span").stop().animate({ bottom: "-70px" }, settings.duration);
            });
        }
    };

    $.fn.UIHover = function(option) {
        if (methods[option])
            return methods[option].apply(this, Array.prototype.slice.call(arguments, 1));
        else
            return methods.init.apply(this, arguments);
    }
}(jQuery));

$(function() {
    $("#cmt_<?php echo $this->type; ?>").UIHover();
    // 기본 설정값을 변경하려면 아래처럼 사용
    //$("#cmt_<?php echo $this->type; ?>").UIHover({ duration: 400 });
});
</script>
<!-- } 상품진열 30 끝 -->