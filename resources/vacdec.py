#!/usr/bin/env python

import sys
import zlib
import pprint

import PIL.Image
import pyzbar.pyzbar
import base45
import cbor2

data = pyzbar.pyzbar.decode(PIL.Image.open(sys.argv[1]))
cert = data[0].data.decode()

b45data = cert.replace("HC1:", "")

zlibdata = base45.b45decode(b45data)

cbordata = zlib.decompress(zlibdata)

decoded = cbor2.loads(cbordata)

pprint.pprint(cbor2.loads(decoded.value[2]))
