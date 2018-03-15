#!/usr/bin/env python3

import argparse
import ipaddress
import unicodedata

bits_per_char = [(0, 7), (0x80, 11), (0x800, 16), (0x10000, 21)]

def top_bits(bits, bitlen, nbits):
    if bitlen >= nbits:
        return bits >> (bitlen - nbits)
    return bits << (nbits - bitlen)

def use_bits(bits, bitlen, nbits):
    if nbits >= bitlen:
        return (0, 0)
    mask = (1 << (bitlen - nbits)) - 1
    return (bits & mask, bitlen - nbits)

def possible_next_char(bits, bitlen):
    res = []
    for b in bits_per_char:
        c = b[0] + top_bits(bits, bitlen, b[1])
        if c > 0 and c < 0x110000:
            ch = chr(c)
            if unicodedata.category(ch)[0] in "LMNPSC":
                res.append((ch, b[1]))
    return res

def print_idunno(id):
    for c in id:
        printc = " "
        if unicodedata.category(c)[0] in "LNPS":
            printc = c
        print('U+{:0=6X} {} {}'.format(ord(c), printc, unicodedata.name(c, "Unknown")))
    print()

def form_idunno_next(bits, bitlen, idunno):
    if bitlen > 0:
        for c in possible_next_char(bits, bitlen):
            nbits, nbitlen = use_bits(bits, bitlen, c[1])
            nidunno = idunno + c[0]
            form_idunno_next(nbits, nbitlen, nidunno)
    else:
        print_idunno(idunno)

def form_idunno(bits, bitlen):
    form_idunno_next(bits, bitlen, '')

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument("address", help="The IP address from which to form I-DUNNO")
    args = parser.parse_args()

    addr = ipaddress.ip_address(args.address)
    form_idunno(int(addr), addr.max_prefixlen)
