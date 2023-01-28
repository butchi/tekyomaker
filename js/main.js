import "https://code.jquery.com/jquery-3.6.3.min.js"
import "https://cdn.jsdelivr.net/gh/cgarciagl/face-api.js@0.22.2/dist/face-api.min.js"

const uploadImage = async _ => {
  const imgFile = document.querySelector("#upload input").files[0]
  const img = await faceapi.bufferToImage(imgFile)
  document.querySelector("#original").src = img.src

  const detectionsWithLandmarks = await faceapi
    .detectAllFaces(img)
    .withFaceLandmarks()

  const canvasElm = document.querySelector("canvas")
  canvasElm.width = img.width
  canvasElm.height = img.height
  const ctx = canvasElm.getContext("2d")

  const personArr = detectionsWithLandmarks
    .map(l => [l.landmarks.getLeftEye(), l.landmarks.getRightEye()].map(eyeLmArr => eyeLmArr.reduce((p, c) => ({
      x: p.x + c.x / eyeLmArr.length,
      y: p.y + c.y / eyeLmArr.length,
    }), { x: 0, y: 0 })))

  const ratio = 1

  const ticker = _ => {
    ctx.drawImage(img, 0, 0)

    personArr.forEach(eyeArr => {
      const lx = eyeArr[0].x
      const ly = eyeArr[0].y
      const rx = eyeArr[1].x
      const ry = eyeArr[1].y

      const interval = Math.sqrt(Math.pow(lx - rx, 2) + Math.pow(ly - ry, 2))
      const size = interval / 2
      const rotation = Math.atan2(ry - ly, rx - lx)

      ctx.font = `${size * ratio}px sans-serif`
      ctx.fillStyle = "white"
      ctx.textAlign = "center"
      ctx.textBaseline = "top"

      eyeArr.forEach((eye, idx) => {
        ctx.save()
        ctx.translate(eye.x, eye.y)
        ctx.rotate(rotation)
        ctx.fillText("提供"[idx], 0, - size * ratio / 2)
        ctx.restore()
      })
    })

    requestAnimationFrame(ticker)
  }

  ticker()
}

$(globalThis).on("load", async _evt => {
  await faceapi.loadFaceLandmarkModel("./js/lib/weights")
  await faceapi.loadFaceRecognitionModel("./js/lib/weights")
  await faceapi.nets.ssdMobilenetv1.loadFromUri("./js/lib/weights")

  $(".loading").hide()

  $("#upload input").on("change", _evt => {
    uploadImage()
  })
})

