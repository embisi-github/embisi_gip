#a Copyright
#  
#  This file 'Makefile' copyright Embisi Inc., 2004

#a Targets
all: simulation regressions

.PHONY: simulation

regressions:
	cd ../repository/regressions/gip && make

simulation:
	cd ../build && make sim

clean:
	cd ../build &&  make simclean


#a Editor preferences and notes
# Local Variables: ***
# mode: Makefile ***
# outline-regexp: "#[a!]\\\|#[\t ]*[b-z][\t ]" ***
# End: ***

