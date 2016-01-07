window.licker = window.licker || {};

(function(ns) {
  'use strict';
  function addSponsor() {
    if($('#sponsorsInput > div').size()<6) {
      $('#sponsorsInput').append('  <div><input type="text" name="sponsor[]" placeholder="例) 株式会社月極" /><span class="deleteSponsorBtn" onClick="deleteSponsor(this);">×</span></div>');
    }
  }

  function deleteSponsor(elem) {
    elem.parentNode.parentNode.removeChild(elem.parentNode);
  }

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
    client.facesDetect(imageUrl, null, options, callback)

    function callback(data) {
      console.log(data);

      $(function(){
        data.photos.forEach((photo) => {
          $('.main').append(`<div id="result"></div>
    <div class="stage">
    <img class="picture" src="${imageUrl}" />
    </div><div class="attention">※注意 画像を保存しても提供は付きません。</div>`);

          var width = photo.width;
          var height = photo.height;

          var faceArr = photo.tags;
          faceArr.forEach((tag) => {
            var $teikyo = $('<div></div>')
            .addClass('teikyo');
            var $tei = $('<div>提</div>')
            .addClass('tei');
            var $kyo = $('<div>供</div>')
            .addClass('kyo');
            var $sponsor = $('<div>カヤック</div>')
            .addClass('sponsor');
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

            $('.main').find('.stage').append($teikyo);

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

  function chkUrl(){
    var checked = $('#urlRadio').attr('checked');
    $('#imageUrlText').attr('disabled', !checked);
    $('#twitterText').attr('disabled', checked);
  }
  function chkTwitter(){
    var checked = $('#twitterRadio').attr('checked');
    $('#imageUrlText').attr('disabled', checked);
    $('#twitterText').attr('disabled', !checked);
  }
})(window.licker);
