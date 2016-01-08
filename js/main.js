window.licker = window.licker || {};

(function(ns) {
  'use strict';

  $(function main() {
    var $listSponsor = $('.list-sponsor');
    var $sponsorTmpl = $listSponsor.find('.item-sponsor').eq(0).clone();

    $('.btn-add-sponsor').on('click', function() {
      $listSponsor.append($sponsorTmpl.clone());
    });

    $listSponsor.on('click', '.btn-delete-sponsor', function() {
      if($listSponsor.find('.item-sponsor').length > 1) {
        $(this).closest('.item-sponsor').remove();
      }
    });

    $('.form-main').on('submit', (evt) => {
      evt.preventDefault();
      submitHandler();
    });

    function submitHandler() {
      var imageUrl = $('#imageUrlText').val();
      console.log(imageUrl);

      var client = new FCClientJS(ns.API_KEY, ns.API_SECRET);
      var options = new Object();
      // options.detect_all_feature_points = true;
      client.facesDetect(imageUrl, null, options, callback);

      function callback(data) {
        console.log(data);

        $(function(){
          data.photos.forEach((photo) => {
            var $stage = $('<div></div>').addClass('stage');
            $stage.append(`<img class="picture" src="${imageUrl}">`);
            $('.result').prepend($stage);

            var width = photo.width;
            var height = photo.height;

            var faceArr = photo.tags;
            faceArr.forEach((tag, idx) => {
              var sponsorName = $listSponsor.find('.item-sponsor').eq(idx).find('.input-sponsor').val();

              var $teikyo = $('<div></div>')
              .addClass('teikyo');
              var $tei = $('<span>提</span>')
              .addClass('tei');
              var $kyo = $('<span>供</span>')
              .addClass('kyo');
              var $sponsor = $('<div></div>')
              .addClass('sponsor')
              .text(sponsorName);
              $teikyo.append($tei, $kyo, $sponsor);

              var lx = tag.eye_left.x * width / 100;
              var ly = tag.eye_left.y * height / 100;
              var rx = tag.eye_right.x * width / 100;
              var ry = tag.eye_right.y * height / 100;
              var interval = Math.sqrt(Math.pow(lx - rx, 2)+Math.pow(ly - ry, 2));
              var size = interval / 2;
              var rotation = Math.atan2(ly - ry, lx - rx);

              $tei.css({
                position: 'absolute',
                left: rx - size / 2,
                top: ry - size / 2,
                'font-size': size,
                transform: `rotate(${rotation}rad)`,
              });

              $kyo.css({
                position: 'absolute',
                left: lx - size / 2,
                top: ly - size / 2,
                'font-size': size,
                transform: `rotate(${rotation}rad)`,
              });

              $sponsor.css({
                position: 'absolute',
                width: '2000px',
                left: (- Math.sin(rotation) * interval + (rx + lx) / 2) - 1000,
                top:  (  Math.cos(rotation) * interval + (ry + ly) / 2) - 10,
                'text-align': 'center',
                'font-size': size + 'px',
                transform: `rotate(${rotation}rad)`,
              });

              $stage.append($teikyo);

              // if(count($face)==0 && $imageUrl!="") {
              //   // 顔が検出されなかったとき
              //   print(' <div class="teikyo" id="teikyo">
              //   <div class="tei">提</div>
              //   <div class="kyo">供</div>
              //   <div class="sponsor">'.$_GET['sponsor'][0].'</div>
              // </div>');
              // }
            });

            if(faceArr.length === 0) {
              $("#result").text("顔が検出されませんでした。");
              $(".picture").bind("load", function() {
                var size = ($(".picture").width() < $(".picture").height())? $(".picture").width()*0.1 : $(".picture").width()*0.1;
                $(".tei").css({
                  "position": "absolute",
                  "left": 0.4*$(this).width()-size/2+"px",
                  "top": 0.4*$(this).height()-size/2+"px",
                  "font-size": size+"px"
                });
                $(".kyo").css({
                  "position": "absolute",
                  "left": 0.6*$(this).width()-size/2+"px",
                  "top": 0.4*$(this).height()-size/2+"px",
                  "font-size": size+"px"
                });
                $(".sponsor").css({
                  "position": "absolute",
                  "width": $(".picture").width()+"px",
                  "top": 0.6*$(this).height()-size/2+"px",
                  "text-align": "center",
                  "font-size": size+"px"
                });
              });
            }
          });
        });
      }
    }
  });
})(window.licker);
