.text
	.align 4

    .global wrapper_entry_point
  	.type	 wrapper_entry_point,function
wrapper_entry_point:
	mov sp, #0x10000
	bl	test_entry_point
	cmp r0, #0
	bne test_failed

test_passed:
    mov r0, r0
    mov r0, r0
    mov r0, r0
    mov r0, r0
	.word 0xf0000090
	b loop

    .word 0
    .word 0
    .word 0

test_failed:	
	.word 0xf0000091
	b loop

    .word 0
    .word 0
    .word 0

loop:
	b loop
    .word 0
    .word 0
    .word 0
    .word 0

    .global dprintf
	.type	 dprintf,function
dprintf:
    mov r0, r0
    mov r1, r1
    mov r2, r2
    mov r3, r3
    .word 0xf00000a0
    mov pc, lr
    
.Lfe1:
	.size	 wrapper_entry_point,.Lfe1-wrapper_entry_point