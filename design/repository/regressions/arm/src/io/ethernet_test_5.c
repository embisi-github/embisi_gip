/*a Includes
 */
#include <stdlib.h> // for NULL
#include "gip_support.h"
#include "gip_system.h"
#include "postbus.h"
#include "../common/wrapper.h"
#include "ethernet.h"
#include "postbus_config.h"

/*a Defines
 */
#define DRAM_START ((void *)(0x80010000))
#define BUFFER_SIZE (2048)

/*a Types
 */

/*a Static variables
 */
static t_eth_buffer buffers[16];
static int failures;
static int done;
static int rxed_number;
static int txed_number;

static unsigned char test_tx_packet_0[] = 
{
    0xef, 0xbe, 0xed, 0xfe, 
    0x04, 0x03, 0x02, 0x01, 0x08, 0x07, 0x06, 0x05,     0x0c, 0x0b, 0x0a, 0x09, 0x10, 0x0f, 0x0e, 0x0d,   
    0x14, 0x13, 0x12, 0x11, 0x18, 0x17, 0x16, 0x15,     0x1c, 0x1b, 0x1a, 0x19, 0x20, 0x1f, 0x1e, 0x1d, 
    0x24, 0x23, 0x22, 0x21, 0x28, 0x27, 0x26, 0x25,     0x2c, 0x2b, 0x2a, 0x29, 0x30, 0x2f, 0x2e, 0x2d, 
    0x34, 0x33, 0x32, 0x31, 0x38, 0x37, 0x36, 0x35,     0x3c, 0x3b, 0x3a, 0x39, 0x40, 0x3f, 0x3e, 0x3d, 
};
static unsigned char test_tx_packet_1[] = 
{
    0xef, 0xbe, 0xed, 0xfe, 
    0x44, 0x43, 0x42, 0x41, 0x48, 0x47, 0x46, 0x45,     0x4c, 0x4b, 0x4a, 0x49, 0x50, 0x4f, 0x4e, 0x4d,    
    0x54, 0x53, 0x52, 0x51, 0x58, 0x57, 0x56, 0x55,     0x5c, 0x5b, 0x5a, 0x59, 0x60, 0x5f, 0x5e, 0x5d, 
    0x64, 0x63, 0x62, 0x61, 0x68, 0x67, 0x66, 0x65,     0x6c, 0x6b, 0x6a, 0x69, 0x70, 0x6f, 0x6e, 0x6d, 
    0x74, 0x73, 0x72, 0x71, 0x78, 0x77, 0x76, 0x75,     0x7c, 0x7b, 0x7a, 0x79, 0x80, 0x7f, 0x7e, 0x7d, 
};
static unsigned char test_tx_packet_2[] = 
{
    0xef, 0xbe, 0xed, 0xfe, 
    0x84, 0x83, 0x82, 0x81, 0x88, 0x87, 0x86, 0x85,     0x8c, 0x8b, 0x8a, 0x89, 0x90, 0x8f, 0x8e, 0x8d,   
    0x94, 0x93, 0x92, 0x91, 0x98, 0x97, 0x96, 0x95,     0x9c, 0x9b, 0x9a, 0x99, 0xa0, 0x9f, 0x9e, 0x9d, 
    0xa4, 0xa3, 0xa2, 0xa1, 0xa8, 0xa7, 0xa6, 0xa5,     0xac, 0xab, 0xaa, 0xa9, 0xb0, 0xaf, 0xae, 0xad, 
    0xb4, 0xb3, 0xb2, 0xb1, 0xb8, 0xb7, 0xb6, 0xb5,     0xbc, 0xbb, 0xba, 0xb9, 0xc0, 0xbf, 0xbe, 0xbd, 
};

/*a Static functions
 */
/*f tx_callback
 */
static void tx_callback( void *handle, t_eth_buffer *buffer )
{
    GIP_EXTBUS_DATA_WRITE( txed_number );
    GIP_BLOCK_ALL();
    GIP_EXTBUS_DATA_WRITE( buffer );
    txed_number++;
    if (txed_number>=3)
        done = 1;
}

/*f rx_callback
 */
static void rx_callback( void *handle, t_eth_buffer *buffer, int rxed_byte_length )
{
}


/*a Test entry point
 */
/*f test_entry_point
 */
extern int test_entry_point()
{
    int i;
    failures = 0;
    done = 0;
    rxed_number = 0;
    txed_number = 0;

    postbus_config( 16, 16, 0, 0 ); // use 16 for tx and 16 for rx, no sharing
    GIP_CLEAR_SEMAPHORES( -1 );

    ethernet_init( IO_A_SLOT_ETHERNET_0, 1, 0 );
    ethernet_set_tx_callback( tx_callback, NULL );
    ethernet_set_rx_callback( rx_callback, NULL );

    GIP_LED_OUTPUT_CFG_WRITE( 0xaaaa );
    GIP_BLOCK_ALL();
    GIP_EXTBUS_CONFIG_WRITE( 0x111 );
    GIP_BLOCK_ALL();
    GIP_EXTBUS_ADDRESS_WRITE( 0xc0000002 ); // Debug display address
    GIP_BLOCK_ALL();
    GIP_EXTBUS_DATA_WRITE( 0xffff );

    buffers[0].data = test_tx_packet_0;
    buffers[0].buffer_size = 68;
    ethernet_tx_buffer( &buffers[0] );

    buffers[1].data = test_tx_packet_1;
    buffers[1].buffer_size = 68;
    ethernet_tx_buffer( &buffers[1] );

    buffers[2].data = test_tx_packet_2;
    buffers[2].buffer_size = 68;
    ethernet_tx_buffer( &buffers[2] );

    for (i=3; i<8; i++)
    {
        buffers[i].data = DRAM_START+BUFFER_SIZE*i;
        buffers[i].buffer_size = BUFFER_SIZE;
        ethernet_add_rx_buffer( &buffers[i] );
    }

    while (!done)
    {
        ethernet_poll();
    }
    return failures;
}
