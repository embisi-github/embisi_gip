#a Copyright
#  
#  This file 'Makefile' copyright Gavin J Stark 2003, 2004
#  

#a Global variables
ifeq ($(VERBOSE),1)
  Q =
else
  Q = @
endif

ARM_PREFIX := /usr/local/cross_arm/bin/arm-linux-
ARM_CC := ${ARM_PREFIX}gcc -fno-inline-functions -mapcs-32 -mcpu=gip -mbig-endian
ARM_LD := ${ARM_PREFIX}ld -EB
ARM_STRIP := ${ARM_PREFIX}strip
ARM_CC_INCLUDES :=  -I../../headers/c
ARM_CC_FLAGS := -Wall -fomit-frame-pointer -O4 -DREGRESSION
ARM_AS_FLAGS := -Wall -I../../headers/asm -I../../headers/c -mapcs-32 -mcpu=gip -mbig-endian -DREGRESSION
BIN_TO_MIF := ${TNBT_CVS_ROOT}/common/bin/bin_to_mif -s
MIF_TO_CDL := ${TNBT_CVS_ROOT}/common/bin/mif_to_cdl
# ARM_CC_FLAGS := 

TEST_LIST := src/test_list
CREATE_MAKE := scripts/create_make

ALL2: ALL obj/tests.make

#${TNBT_CVS_ROOT}/common/bin/bin_to_mif -s -f bin/monitor.rom.bin -c -o bin/monitor.rom.coe

obj/tests.make: ${CREATE_MAKE} ${TEST_LIST}
	${CREATE_MAKE} ${TEST_LIST} obj/tests.make

include obj/tests.make

ALL: $(TEST_BINARIES)

obj/wrapper.o: src/common/wrapper.s
	${ARMCC} ${ARM_CC_FLAGS} -c src/common/wrapper.s -o obj/wrapper.o

clean:
	rm -f obj/* bin/*

#depend:
#	makedepend -- ${CFLAGS} -- ${SRCS}

#a Editor preferences and notes
# Local Variables: ***
# mode: Makefile ***
# outline-regexp: "#[a!]\\\|#[	 ]*[b-z][	 ]" ***
# End: ***

# DO NOT DELETE
