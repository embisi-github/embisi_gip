<?php

include "web_locals.php";
include "${toplevel}web_assist/web_globals.php";

site_set_location($location);

$page_title = "GIP Documentation";

include "${toplevel}web_assist/web_header.php";

page_header( "GIP Register File" );

page_sp();
?>

<?php page_section( "overview", "Overview" ); ?>

<p>
The GIP register file module contains both the actual register file and registering of the control signals required to access the register file, plus the register scoreboarding. It implements both the register file read pipeline stage and the register file write pipeline stage.
</p>

<p>
In order to support use of the PC as an operand in instructions, and as the register file does not hold the PC, there is an input to the
register file read stage which is used as a PC value (or PC+offset), and the register file read stage can multiplex this value
onto either of its read outputs if the PC is desired by the ALU as opposed to a true register file value
</p>

<?php
page_ep();

include "${toplevel}web_assist/web_footer.php"; ?>
