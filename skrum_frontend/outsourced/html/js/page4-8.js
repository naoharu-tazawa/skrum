"use strict";

jQuery(function($){
    (function(){
        // 右側サイドバーの開閉
        var openButton = $('.right_bar_show');
        var closeBar = $('.right_bar > nav');
        var rightBar = $(".right_bar");
        var rightBarRight = rightBar.css('right');
        // 表示切替アニメーションの時間(ms)
        var duration = 800;
        // アニメーションタイプ
        var easing = 'swing';
        // オープンボタンクリック
        openButton.click(function(){
            // オープンボタンを消してから開く
            openButton.hide();
            rightBar.animate({right: 0}, duration, easing);
        });
        // クローズバークリック
        closeBar.click(function(){
            rightBar.animate(
                {right: rightBarRight}, duration, easing,
                function(){
                    // 閉じ終わったらオープンボタンを表示
                    openButton.show();
                }
            );
        });
    })();
    // サイドバーのコメントエリアの高さ調整
    (function(){
        var comment = $('.right_bar .body .comment');
        var send = $('.right_bar .body .send');
        var sendHeight = send.outerHeight();
        var commentOffsetHeight = comment.offset().top;
        // IEでずれる問題の調整
        if(navigator.userAgent.toLowerCase().indexOf("trident") !== -1){
            commentOffsetHeight += 20;
        }
        function setCommentHeight(){
            var windowHeight = $(window).outerHeight();
            comment.css('flex-basis', windowHeight - commentOffsetHeight - sendHeight);
        }
        $(window).resize(setCommentHeight);
        setCommentHeight();
    })();
});
