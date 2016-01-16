window.licker = window.licker || {};

(function(ns) {
  'use strict';

  $(function main() {
    var $listSponsor = $('.list-sponsor');
    var $sponsorTmpl = $listSponsor.find('.item-sponsor').eq(0).clone();

    var $btnShooting = $('.btn-shooting button');
    var $btnUpload = $('.btn-upload button');

    $('.btn-add-sponsor').on('click', function() {
      $listSponsor.append($sponsorTmpl.clone());
    });

    $listSponsor.on('click', '.btn-delete-sponsor', function() {
      if($listSponsor.find('.item-sponsor').length > 1) {
        $(this).closest('.item-sponsor').remove();
      }
    });

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
    if(!camera.isSupport) {
      $btnStartCamera.hide();
      $navigateCamera.hide();
    }

    $('.btn-camera').on('click', () => {
      $('.screen[data-step="0"]').hide();
      $screenCamera.show();
    });

    $('.btn-upload').on('click', () => {
      $('.screen[data-step="0"]').hide();
      $screenUpload.show();
    });

    $('.btn-navigate-upload').on('click', () => {
      $screenUpload.hide();
      $screenCamera.show();
    });

    $('.btn-navigate-camera').on('click', () => {
      $screenCamera.hide();
      $screenUpload.show();
    });

    // カメラ起動
    $btnStartCamera.on('click', (e) => {
      e.preventDefault();
      camera.powerOn();
    });

    // 撮影ボタン
    $btnCapture.on('click', (e) => {
      e.preventDefault();
      var video = camera.getVideo();
      previewCanvas.draw(video);
      $btnShooting.attr('disabled', true);
      camera.powerOff();
      // @postImage(@previewCanvas)
    });

    // ファイルアップロード
    $form.find('input').on('change', (e) => {
      // $('.loading').show()
      $btnShooting.attr('disabled', true);
      $btnUpload.attr('disabled', true);
      $('.btn-upload .file').addClass('disabled');
      $navigateCamera.hide();
      $navigateUpload.hide();

      // @$form.submit()
    });

    // ドラッグアンドドロップ
    dnd.on(Events.DND_LOAD_IMG, (e, image, file) => {

      // if file.size >= 2097152
      //     alert('アップロードサイズ上限を超えています。')

      previewImage.draw(image, image.width, image.height);
      postImage(previewImage);
    });

    $navigateUpload.on('click', () => {
      $screenCamera.hide();
      $screenCamera.find('.error').hide();
      $screenUpload.show();
    });

    $navigateCamera.on('click', () => {
      $screenUpload.hide();
      $screenUpload.find('.error').hide();
      camera.powerOn();
      $screenCamera.show();
    });

    $('.block-input .btn-generate').on('click', (evt) => {
      console.info('generating');
      var canvas = ns.previewCanvas.getCanvas();
      var ctx = canvas.getContext('2d');

      var blob = ns.previewCanvas.getBlob();
      // var file = blobToFile(blob, 'test.png');

      var client = new FCClientJS(ns.API_KEY, ns.API_SECRET);
      var options = new Object();
      // options.detect_all_feature_points = true;
      client.facesDetect(null, [blob], options, callback);

      function callback(data) {
        console.log(data);
        JSON.parse(data).photos.forEach((photo) => {
          var width = photo.width;
          var height = photo.height;

          var faceArr = photo.tags;
          faceArr.forEach((tag, idx) => {
            var lx = tag.eye_left.x * width / 100;
            var ly = tag.eye_left.y * height / 100;
            var rx = tag.eye_right.x * width / 100;
            var ry = tag.eye_right.y * height / 100;
            var interval = Math.sqrt(Math.pow(lx - rx, 2)+Math.pow(ly - ry, 2));
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
