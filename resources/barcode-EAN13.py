import subprocess,asyncio,json,time,argparse,re
from barcode import EAN13
from barcode.writer import ImageWriter

parser = argparse.ArgumentParser()
parser.add_argument("content", help="content")
parser.add_argument("img", help="img location")
args = parser.parse_args()

with open(args.img, 'wb') as f:
    EAN13(args.content, writer=ImageWriter()).write(f)
