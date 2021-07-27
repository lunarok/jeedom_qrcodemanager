import subprocess,asyncio,json,time,argparse,re
from pyzbar.pyzbar import decode
from PIL import Image

parser = argparse.ArgumentParser()
parser.add_argument("img", help="img location")
args = parser.parse_args()

img = Image.open(args.img)
result = decode(img)
for i in result:
    print(i.data.decode("utf-8"))
