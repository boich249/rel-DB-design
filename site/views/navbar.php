<?php

namespace dbProject;
require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.php');

$href = ($GLOBALS['p'] == 0) ? '':'../';
$href1 = ($GLOBALS['p'] == 0) ? 'pages/':'';
$current = '<span class="sr-only">(current)</span>';
$active = 'class="active"';

?>


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo($href.'index.php'); ?>">Home</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li <?php if($GLOBALS['p'] == 1){echo($active);} ?>>
                    <a href="<?php echo($href1.'employee_detail.php'); ?>">
                        Employee detail
                        <?php if($GLOBALS['p'] == 1){echo($current);} ?>
                    </a>
                </li>
                <li <?php if($GLOBALS['p'] == 2){echo($active);} ?>>
                    <a href="<?php echo($href1.'top_products.php'); ?>">
                        Top Products
                        <?php if($GLOBALS['p'] == 2){echo($current);} ?>
                    </a>
                </li>
                <li <?php if($GLOBALS['p'] == 3){echo($active);} ?>>
                    <a href="<?php echo($href1.'average_price.php'); ?>">
                        Average Price
                        <?php if($GLOBALS['p'] == 3){echo($current);} ?>
                    </a>
                </li>
                <li <?php if($GLOBALS['p'] == 4){echo($active);} ?>>
                    <a href="<?php echo($href1.'best_customers.php'); ?>">
                        Best Customers
                        <?php if($GLOBALS['p'] == 4){echo($current);} ?>
                    </a>
                </li>
                <li <?php if($GLOBALS['p'] == 5){echo($active);} ?>>
                    <a href="<?php echo($href1.'accounts_receivable.php'); ?>">
                        Accounts Receivable
                        <?php if($GLOBALS['p'] == 5){echo($current);} ?>
                    </a>
                </li>
                <li <?php if($GLOBALS['p'] == 6){echo($active);} ?>>
                    <a href="<?php echo($href1.'invoice.php'); ?>">
                        Invoice
                        <?php if($GLOBALS['p'] == 6){echo($current);} ?>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Edit <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo($href1.'edit_employee.php'); ?>">Employee & dependants</a></li>
                        <li><a href="<?php echo($href1.'edit_customer.php'); ?>">Customer</a></li>
                        <li><a href="<?php echo($href1.'edit_department.php'); ?>">Department</a></li>
                        <li><a href="<?php echo($href1.'edit_order.php'); ?>">Order</a></li>
                        <li><a href="<?php echo($href1.'edit_item.php'); ?>">Item</a></li>
                        <li><a href="<?php echo($href1.'edit_inventory.php'); ?>">Inventory</a></li>
                        <li><a href="<?php echo($href1.'edit_shipment.php'); ?>">Shipment</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>