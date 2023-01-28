import "https://code.jquery.com/jquery-3.6.3.min.js"
import clm from "https://cdnjs.cloudflare.com/ajax/libs/clmtrackr/1.1.2/clmtrackr.module.min.js"

const ctracker = new clm.tracker()

const canvasElm = document.querySelector("canvas")
const videoElm = document.querySelector("video")

const initializeWithUserMedia = async _ => {
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

    ctracker.init();
    ctracker.start(videoElm);


    const ctx = canvasElm.getContext("2d")

    const ticker = async _ => {
      const posArr = ctracker.getCurrentPosition()
      const eyeArr = [posArr[27], posArr[32]]

      ctx.drawImage(videoElm, 0, 0)


      const ratio = 1

      const [lx, ly] = eyeArr[0] || [null, null]
      const [rx, ry] = eyeArr[1] || [null, null]

      const interval = Math.sqrt(Math.pow(lx - rx, 2) + Math.pow(ly - ry, 2))
      const size = interval / 2
      const rotation = Math.atan2(ry - ly, rx - lx)

      ctx.textAlign = "center"
      ctx.textBaseline = "top"

      eyeArr.forEach((eye, idx) => {
        const [x, y] = eye || [null, null]

        ctx.save()

        ctx.lineWidth = 6
        ctx.lineJoin = "miter"
        ctx.miterLimit = 5
        ctx.strokeStyle = "black"
        ctx.fillStyle = "white"
        ctx.font = `bold ${size * ratio}px sans-serif`

        ctx.translate(x, y)
        ctx.rotate(rotation)

        ctx.strokeText("提供"[idx], 0, - size * ratio / 2)
        ctx.fillText("提供"[idx], 0, - size * ratio / 2)

        ctx.restore()
      })

      requestAnimationFrame(ticker)
    }

    ticker()
  } catch (err) {
    console.log(err)
  }
}

initializeWithUserMedia()
