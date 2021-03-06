/*a Includes
 */
#include <stdlib.h> // for NULL
#include "gip_support.h"
#include "../common/wrapper.h"
#include "ethernet.h"
#include "uart.h"
#include "memory.h"
#include "cmd.h"

/*a Defines
 */

/*a Test entry point
 */
/*f test_entry_point
 */
extern int test_entry_point()
{
    mon_uart_init();
    extra_init();
    cmd_init(NULL); // after extra_init()
    while (1)
    {
        mon_uart_poll();
    }

}
