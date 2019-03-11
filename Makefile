# Draft Makefile. You will need:
# - xml2rfc (https://xml2rfc.tools.ietf.org/)

DRAFT=draft-mayrhofer-i-dunno
VERSION=01

XML=$(DRAFT)-$(VERSION).xml
HTML=$(DRAFT)-$(VERSION).html
TXT=$(DRAFT)-$(VERSION).txt

.PHONY: clean

all: $(HTML) $(TXT)

$(HTML): $(XML); xml2rfc --html -o $@ $<
$(TXT): $(XML); xml2rfc --text -o $@ $<

clean: ; rm $(HTML) $(TXT)
