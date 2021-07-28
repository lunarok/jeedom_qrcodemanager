import subprocess,asyncio,json,time,argparse,re
import qrcode
from PIL import Image

parser = argparse.ArgumentParser()
parser.add_argument("content", help="content")
parser.add_argument("img", help="img location")
args = parser.parse_args()

qr.add_data(args.content)
qr.make(fit=True)

img = qr.make_image(fill_color="black", back_color="white")
img.save(args.img)
