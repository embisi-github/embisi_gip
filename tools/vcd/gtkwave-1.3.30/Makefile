# Generated automatically from Makefile.in by configure.
prefix = /usr/local
exec_prefix = ${prefix}
bindir = ${exec_prefix}/bin

all:	config.log bin/gtkwave bin/mvl2vcd bin/tex2vcd bin/mvl2lxt bin/vcd2lxt

config.log:
	./configure;

clean:	
	cd src;make clean;cd helpers;make clean

install:
	cd bin;\
	install -m 0755 gtkwave $(bindir)/gtkwave;\
	install -m 0755 mvl2vcd $(bindir)/mvl2vcd;\
	install -m 0755 mvl2lxt $(bindir)/mvl2lxt;\
	install -m 0755 tex2vcd $(bindir)/tex2vcd;\
	install -m 0755 vcd2lxt $(bindir)/vcd2lxt

dist:
	make clean
	rm -f config.* Makefile src/Makefile src/helpers/Makefile

bin/gtkwave:
	# Compiling GTKWave...
	cd src;make all

bin/mvl2vcd:
	# Compiling mvl2vcd/tex2vcd...
	cd src/helpers;make all

bin/mvl2lxt:
	# Compiling mvl2vcd/tex2vcd...
	cd src/helpers;make all

bin/tex2vcd:
	# Compiling tex2vcd/mvl2vcd...
	cd src/helpers;make all

bin/vcd2lxt:
	# Compiling tex2vcd/mvl2vcd...
	cd src/helpers;make all

