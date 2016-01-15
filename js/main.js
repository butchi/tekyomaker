'use strict';

window.licker = window.licker || {};

(function (ns) {
  'use strict';

  $(function main() {
    var $listSponsor = $('.list-sponsor');
    var $sponsorTmpl = $listSponsor.find('.item-sponsor').eq(0).clone();

    $('.btn-add-sponsor').on('click', function () {
      $listSponsor.append($sponsorTmpl.clone());
    });

    $listSponsor.on('click', '.btn-delete-sponsor', function () {
      if ($listSponsor.find('.item-sponsor').length > 1) {
        $(this).closest('.item-sponsor').remove();
      }
    });

    $('.form-main').on('submit', function (evt) {
      evt.preventDefault();
      submitHandler();
    });

    function submitHandler() {
      var imageUrl = $('.intput-file').val();
      console.log(imageUrl);

      var client = new FCClientJS(ns.API_KEY, ns.API_SECRET);
      var options = new Object();
      // options.detect_all_feature_points = true;
      client.facesDetect(imageUrl, null, options, callback);

      function callback(data) {
        console.log(data);

        $(function () {
          data.photos.forEach(function (photo) {
            var $stage = $('<div></div>').addClass('stage');
            $stage.append('<img class="picture" src="' + imageUrl + '">');
            $('.result').prepend($stage);

            var width = photo.width;
            var height = photo.height;

            var faceArr = photo.tags;
            faceArr.forEach(function (tag, idx) {
              var sponsorName = $listSponsor.find('.item-sponsor').eq(idx).find('.input-sponsor').val();

              var $teikyo = $('<div></div>').addClass('teikyo');
              var $tei = $('<span>提</span>').addClass('tei');
              var $kyo = $('<span>供</span>').addClass('kyo');
              var $sponsor = $('<div></div>').addClass('sponsor').text(sponsorName);
              $teikyo.append($tei, $kyo, $sponsor);

              var lx = tag.eye_left.x * width / 100;
              var ly = tag.eye_left.y * height / 100;
              var rx = tag.eye_right.x * width / 100;
              var ry = tag.eye_right.y * height / 100;
              var interval = Math.sqrt(Math.pow(lx - rx, 2) + Math.pow(ly - ry, 2));
              var size = interval / 2;
              var rotation = Math.atan2(ly - ry, lx - rx);

              $tei.css({
                position: 'absolute',
                left: rx - size / 2,
                top: ry - size / 2,
                'font-size': size,
                transform: 'rotate(' + rotation + 'rad)'
              });

              $kyo.css({
                position: 'absolute',
                left: lx - size / 2,
                top: ly - size / 2,
                'font-size': size,
                transform: 'rotate(' + rotation + 'rad)'
              });

              $sponsor.css({
                position: 'absolute',
                width: '2000px',
                left: -Math.sin(rotation) * interval + (rx + lx) / 2 - 1000,
                top: Math.cos(rotation) * interval + (ry + ly) / 2 - 10,
                'text-align': 'center',
                'font-size': size + 'px',
                transform: 'rotate(' + rotation + 'rad)'
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

            // if(faceArr.length === 0) {
            //   $("#result").text("顔が検出されませんでした。");
            //   $(".picture").bind("load", function() {
            //     var size = ($(".picture").width() < $(".picture").height())? $(".picture").width()*0.1 : $(".picture").width()*0.1;
            //     $(".tei").css({
            //       "position": "absolute",
            //       "left": 0.4*$(this).width()-size/2+"px",
            //       "top": 0.4*$(this).height()-size/2+"px",
            //       "font-size": size+"px"
            //     });
            //     $(".kyo").css({
            //       "position": "absolute",
            //       "left": 0.6*$(this).width()-size/2+"px",
            //       "top": 0.4*$(this).height()-size/2+"px",
            //       "font-size": size+"px"
            //     });
            //     $(".sponsor").css({
            //       "position": "absolute",
            //       "width": $(".picture").width()+"px",
            //       "top": 0.6*$(this).height()-size/2+"px",
            //       "text-align": "center",
            //       "font-size": size+"px"
            //     });
            //   });
            // }
          });
        });
      }
    }
  });

  $(function media() {
    var $btnShooting = $('.btn-shooting button');
    var $btnUpload = $('.btn-upload button');

    var Events = Staircase.Events;
    Staircase.initialize();

    var camera = new Staircase.Camera('#Video');
    var previewCanvas = new Staircase.PreviewCanvas('#Canvas');
    ns.previewCanvas = previewCanvas;
    previewCanvas.type = 'camera';
    var previewImage = new Staircase.PreviewCanvas('#CanvasDummy');
    previewImage.type = 'file';
    var dnd = new Staircase.DragAndDrop('#DragAndDrop');
    var modal = new Staircase.Modal({ id: '#Modal', page: '.container' });

    var $form = $('#Upload');
    var $btnStartCamera = $('#StartCamera');
    var $btnCapture = $('#Capture');
    var $screenSelect = $('.screen--select');
    var $screenCamera = $('.screen--camera');
    var $screenUpload = $('.screen--upload');
    var $navigateUpload = $screenCamera.find('.btn-navigate-upload');
    var $navigateCamera = $screenUpload.find('.btn-navigate-camera');

    // カメラがあるか
    if (!camera.isSupport) {
      $btnStartCamera.hide();
      $navigateCamera.hide();
    }

    // カメラ起動
    $btnStartCamera.on('click', function (e) {
      e.preventDefault();
      camera.powerOn();
    });

    // 撮影ボタン
    $btnCapture.on('click', function (e) {
      e.preventDefault();
      var video = camera.getVideo();
      previewCanvas.draw(video);
      $btnShooting.attr('disabled', true);
      camera.powerOff();
      // @postImage(@previewCanvas)
    });

    // ファイルアップロード
    $form.find('input').on('change', function (e) {
      // $('.loading').show()
      $btnShooting.attr('disabled', true);
      $btnUpload.attr('disabled', true);
      $('.btn-upload .file').addClass('disabled');
      $navigateCamera.hide();
      $navigateUpload.hide();

      // @$form.submit()
    });

    // ドラッグアンドドロップ
    dnd.on(Events.DND_LOAD_IMG, function (e, image, file) {

      // if file.size >= 2097152
      //     alert('アップロードサイズ上限を超えています。')

      previewImage.draw(image, image.width, image.height);
      postImage(previewImage);
    });

    $navigateUpload.on('click', function () {
      $screenCamera.hide();
      $screenCamera.find('.error').hide();
      $screenUpload.show();
    });

    $navigateCamera.on('click', function () {
      $screenUpload.hide();
      $screenUpload.find('.error').hide();
      camera.powerOn();
      $screenCamera.show();
    });
  });

  $(function test() {
    // function blobToFile(theBlob, fileName){
    //     //A Blob() is almost a File() - it's just missing the two properties below which we will add
    //     theBlob.lastModifiedDate = new Date();
    //     theBlob.name = fileName;
    //     return theBlob;
    // }

    var $btn = $('.btn-draw');
    $btn.on('click', function () {
      var canvas = ns.previewCanvas.getCanvas();
      var ctx = canvas.getContext('2d');

      var blob = ns.previewCanvas.getBlob();
      // var file = blobToFile(blob, 'test.png');

      var client = new FCClientJS(ns.API_KEY, ns.API_SECRET);
      var options = new Object();
      // options.detect_all_feature_points = true;
      client.facesDetect(null, [blob], options, callback);

      function callback(data) {
        JSON.parse(data).photos.forEach(function (photo) {
          var width = photo.width;
          var height = photo.height;

          var faceArr = photo.tags;
          faceArr.forEach(function (tag, idx) {
            var lx = tag.eye_left.x * width / 100;
            var ly = tag.eye_left.y * height / 100;
            var rx = tag.eye_right.x * width / 100;
            var ry = tag.eye_right.y * height / 100;
            var interval = Math.sqrt(Math.pow(lx - rx, 2) + Math.pow(ly - ry, 2));
            var size = interval / 2;
            var rotation = Math.atan2(ly - ry, lx - rx);

            ctx.fillStyle = "white";
            ctx.font = "30px 'ＭＳ ゴシック'";
            ctx.textAlign = "left";
            ctx.textBaseline = "top";

            ctx.fillText("提", rx - 15, ry - 15, 200);
            ctx.fillText("供", lx - 15, ly - 15, 200);
          });
        });
      }
    });
  });
})(window.licker);