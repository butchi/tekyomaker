import "https://code.jquery.com/jquery-3.6.3.min.js"
import "https://cdn.jsdelivr.net/gh/cgarciagl/face-api.js@0.22.2/dist/face-api.min.js"

const canvasElm = document.querySelector("canvas")
const videoElm = document.querySelector("video")

const initializeWithUserMedia = async _ => {
  let landmarkArr = []

  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: {
        width: { ideal: 1920 },
        height: { ideal: 1080 },
      },
      audio: false
    })
    videoElm.srcObject = stream
    videoElm.play()


    const ctx = canvasElm.getContext("2d")

    const ticker = async _ => {
      ctx.drawImage(videoElm, 0, 0)

      const personArr = landmarkArr
        .map(l => [l.landmarks.getLeftEye(), l.landmarks.getRightEye()].map(eyeLmArr => eyeLmArr.reduce((p, c) => ({
          x: p.x + c.x / eyeLmArr.length,
          y: p.y + c.y / eyeLmArr.length,
        }), { x: 0, y: 0 })))

      const ratio = 1

      personArr.forEach(eyeArr => {
        if (eyeArr == null) return

        const lx = eyeArr[0].x
        const ly = eyeArr[0].y
        const rx = eyeArr[1].x
        const ry = eyeArr[1].y

        const interval = Math.sqrt(Math.pow(lx - rx, 2) + Math.pow(ly - ry, 2))
        const size = interval / 2
        const rotation = Math.atan2(ry - ly, rx - lx)

        ctx.textAlign = "center"
        ctx.textBaseline = "top"

        eyeArr.forEach((eye, idx) => {
          ctx.save()

          ctx.lineWidth = 6
          ctx.lineJoin = "miter"
          ctx.miterLimit = 5
          ctx.strokeStyle = "black"
          ctx.fillStyle = "white"
          ctx.font = `bold ${size * ratio}px sans-serif`

          ctx.translate(eye.x, eye.y)
          ctx.rotate(rotation)

          ctx.strokeText("提供"[idx], 0, - size * ratio / 2)
          ctx.fillText("提供"[idx], 0, - size * ratio / 2)

          ctx.restore()
        })
      })

      requestAnimationFrame(ticker)
    }

    ticker()

    const detectTicker = async _ => {
      const urlBase64 = canvasElm.toDataURL('image/png');

      let img = await faceapi.fetchImage(urlBase64);
      landmarkArr = await faceapi
        .detectAllFaces(img)
        .withFaceLandmarks()

      detectTicker()
    }

    detectTicker()

  } catch (err) {
    console.log(err)
  }
}

$(globalThis).on("load", async _evt => {
  await faceapi.loadFaceLandmarkModel("./js/lib/weights")
  await faceapi.loadFaceRecognitionModel("./js/lib/weights")
  await faceapi.nets.ssdMobilenetv1.loadFromUri("./js/lib/weights")

  $(".loading").hide()

  initializeWithUserMedia()
})

