#define imm_bits(byte,shft)

    // Note that for even left shift (say 12) we want bits ror (32-shift), so the ror in the instructions is 16-shift/2, put in bits 8-11 - this is ((33-12)&0x1e)=20 put in 7 through 11
    // Note that for odd left shift (say 13) we want (bits<<1) ror (33-shift), so the ror in the instructions is 16-(shift-1)/2, put in bits 8-11 - this is ((33-13)&0x1e)=20 put in 7 through 11
    // effectively for odd left shifts are the same as even left shifts with the bits left-shifted-by-1 and the left shift reduced by 1, i.e. rotate right increased by 1.
    .macro gip_read_and_clear_semaphores, bits, shift, regnum
    swi 0xfe0000 | (\bits<<(\shift&1)) | (((33-\shift)&0x1e)<<7) | (\regnum<<12)
    .endm

    .macro gip_read_and_set_semaphores, bits, shift, regnum
    swi 0xee0000 | (\bits<<(\shift&1)) | (((33-\shift)&0x1e)<<7) | (\regnum<<12)
    .endm

    .macro gip_clear_semaphores, bits, shift
    swi 0xf60000 | (\bits<<(\shift&1)) | (((33-\shift)&0x1e)<<7)
    .endm

    .macro gip_set_semaphores, bits, shift
    swi 0xe60000 | (\bits<<(\shift&1)) | (((33-\shift)&0x1e)<<7)
    .endm

    .macro gip_test_semaphores, bits, shift
    gip_extrn 0x80
    tst r0, #\bits<<\shift
    .endm

    .macro restart_at_int_dis_system
    .word 0xec00c86e
    mov r0, #((TRAP_SEM | INTEN_SEM)<<4)|1 // set our flag dependencies and restart mode
    .word 0xec007000 | ( (mkt_int_dis_system_mode - (.+8)) & 0xffe )
    .endm

    .macro restart_at_basic_system
    .word 0xec00c86e
    mov r0, #((TRAP_SEM | HWINT_SEM)<<4)|1 // set our flag dependencies and restart mode
    .word 0xec007000 | ( (mkt_basic_system_mode - (.+8)) & 0xffe )
    .endm

    .macro gip_atomic, num_inst
    .word 0xec007201 | (\num_inst<<1)
    .endm
    
    .macro gip_atomic_block, num_inst
    .word 0xec007281 | (\num_inst<<1)
    .endm
    
    .macro gip_extrd, full_reg
    .word 0xec00c00e | (\full_reg<<4)
    .endm
    
    .macro gip_extrm, full_reg
    .word 0xec00ce00 | (\full_reg>>4)
    .endm
    
    .macro gip_extrn, full_reg
    .word 0xec00de00 | (\full_reg>>4)
    .endm
    
    .macro gip_extrdrn, full_rd, full_rn
    .word 0xec00d000 | (\full_rd<<4) | (\full_rn>>4)
    .endm
    
    .macro gip_mov_full_reg, full_reg, reg
    gip_extrd \full_reg
    mov r0, \reg
    .endm
    
    .macro gip_mov_full_imm, full_reg, immediate
    gip_extrd \full_reg
    mov r0, \immediate
    .endm

    .macro gip_deschedule_block
    .word 0xec007385
    nop
    .endm

    .macro gip_restart_at, label
//    .ifgt (\label-(.+8))-0x7ff
//    b .+0x80000000
    //    .err "gip restart at out of range"
//    .endif
//    .iflt (\label-(.+8))+0x7ff
//    b .+0x80000000
    //    .err "gip restart at out of range"
//    .endif
    .word 0xec007000 | ( (\label - (.+8)) & 0xffe )
    .endm
                    
    .macro gip_utximm c
    .word 0xec00c40e
    ldr r0, = 0x16000096 + (\c<<8)
    .endm
    
    .macro gip_utx c, tmp            
    mov \tmp, \c, lsl #8
    orr \tmp, \tmp, #0x96
    orr \tmp, \tmp, #0x16000000
    .word 0xec00c40e
    mov \tmp, \tmp
    .endm

    .macro gip_gpio_instat reg                               
    .word 0xec00ce05
    mov \reg, r0
    .endm

    .macro gip_trap trap_num, tmp
    swi \trap_num | 0x400
    mov r0, r0
   .endm
